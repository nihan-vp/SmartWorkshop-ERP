<?php

namespace App\Http\Controllers;

use App\Models\ProductKey;
use App\Models\Workshop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductKeyController extends Controller
{
    /**
     * Store a newly generated batch of product keys.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'duration_days' => 'required|integer|min:1',
            'quantity'      => 'required|integer|min:1|max:100',
            'key'           => 'nullable|string|max:255|unique:product_keys,key',
        ]);

        $quantity = (int) $validated['quantity'];
        $durationDays = (int) $validated['duration_days'];
        $customKey = !empty($validated['key']) ? strtoupper(trim($validated['key'])) : null;

        DB::transaction(function () use ($quantity, $durationDays, $customKey) {
            // Delete any existing unused keys so there is only ever one unused key at a time
            ProductKey::where('status', 'unused')->delete();

            if ($customKey) {
                ProductKey::create([
                    'key'           => $customKey,
                    'duration_days' => $durationDays,
                    'status'        => 'unused',
                ]);
            } else {
                for ($i = 0; $i < $quantity; $i++) {
                    $key = ProductKey::generateSecureKey();
                    ProductKey::create([
                        'key'           => $key,
                        'duration_days' => $durationDays,
                        'status'        => 'unused',
                    ]);
                }
            }
        });

        $message = $customKey 
            ? "Successfully created product key '{$customKey}' of {$durationDays} days duration!"
            : "Successfully generated {$quantity} new product keys of {$durationDays} days duration!";

        return redirect()
            ->back()
            ->with('success', $message);
    }

    /**
     * Remove the specified product key from database (if unused).
     */
    public function destroy(ProductKey $productKey, Request $request)
    {
        $productKey->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()
            ->back()
            ->with('success', 'Product key deleted successfully!');
    }



    /**
     * Delete ALL product keys (used and unused) in bulk.
     */
    public function destroyAll()
    {
        $count = ProductKey::count();
        ProductKey::truncate();

        return redirect()
            ->back()
            ->with('success', "All {$count} product keys deleted successfully.");
    }

    /**
     * Redeem a product key to activate/extend a workshop's subscription.
     */
    public function activateLicense(Request $request)
    {
        $request->validate([
            'product_key' => 'required|string',
        ]);

        $inputKey = strtoupper(trim($request->product_key ?? ''));

        $productKey = ProductKey::where('key', $inputKey)->first();

        if (!$productKey) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => 'Incorrect activation key. Please enter a valid product key.'], 422);
            }
            return redirect()
                ->back()
                ->with('error', 'Incorrect activation key. Please enter a valid product key.');
        }

        if ($productKey->isUsed()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => 'This product key has already been redeemed.'], 422);
            }
            return redirect()
                ->back()
                ->with('error', 'This product key has already been redeemed.');
        }

        $user = auth()->user();
        $workshop = null;
        if ($user->isSuperAdmin() && session()->has('active_workshop_id')) {
            $workshop = Workshop::find(session('active_workshop_id'));
        } else {
            $workshop = $user->workshop;
        }

        if (!$workshop) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => 'You must belong to a workshop to activate a license.'], 422);
            }
            return redirect()
                ->back()
                ->with('error', 'You must belong to a workshop to activate a license.');
        }

        DB::transaction(function () use ($productKey, $workshop) {
            // Auto-calculate extension: if current trial_ends_at is in the future, extend it. Otherwise start from now.
            $currentExpiry = $workshop->trial_ends_at;
            if ($currentExpiry && $currentExpiry->isFuture()) {
                $newExpiration = $currentExpiry->addDays($productKey->duration_days);
            } else {
                $newExpiration = now()->addDays($productKey->duration_days);
            }

            // Update workshop
            $workshop->update([
                'subscription_status' => 'active',
                'trial_ends_at' => $newExpiration,
            ]);

            // Mark key as redeemed
            $productKey->update([
                'status' => 'used',
                'used_by_workshop_id' => $workshop->id,
                'used_at' => now(),
            ]);
        });

        // Get the updated trial ends at date
        $expiryDate = $workshop->fresh()->trial_ends_at;

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "License activated successfully! Your subscription is now active until " . $expiryDate->format('M d, Y') . ".",
                'workshop' => [
                    'subscription_status' => $workshop->subscription_status,
                    'trial_ends_at' => $workshop->trial_ends_at ? $workshop->trial_ends_at->format('d M Y, h:i A') : 'Never (Lifetime)',
                    'days_remaining' => $workshop->getTrialDaysRemaining(),
                    'total_duration' => $workshop->getTotalDurationDays(),
                    'subscription_day' => $workshop->getSubscriptionDay(),
                    'is_expired' => $workshop->isTrialExpired(),
                    'has_expiry' => (bool)$workshop->trial_ends_at,
                ],
                'redeemed_keys' => $workshop->productKeys()->orderBy('used_at', 'desc')->get()->map(function($key) {
                    $parts = explode('-', $key->key);
                    $masked = (count($parts) >= 3) ? ($parts[0] . '-XXXX-XXXX-' . end($parts)) : (substr($key->key, 0, 8) . '...');
                    return [
                        'key' => $masked,
                        'duration_days' => $key->duration_days,
                        'used_at' => $key->used_at ? $key->used_at->format('d M Y, h:i A') : $key->updated_at->format('d M Y, h:i A'),
                    ];
                })
            ]);
        }

        return redirect()
            ->route('dashboard')
            ->with('success', "License activated successfully! Your subscription is now active until " . $expiryDate->format('M d, Y') . ".");
    }

    /**
     * Update the specified product key.
     */
    public function update(Request $request, ProductKey $productKey)
    {
        $validated = $request->validate([
            'duration_days' => 'required|integer|min:1',
            'key'           => 'nullable|string|max:255|unique:product_keys,key,' . $productKey->id,
        ]);

        $updates = [
            'duration_days' => (int) $validated['duration_days'],
        ];

        if (!empty($validated['key'])) {
            $updates['key'] = strtoupper(trim($validated['key']));
        }

        $productKey->update($updates);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()
            ->back()
            ->with('success', 'Product key updated successfully!');
    }
}
