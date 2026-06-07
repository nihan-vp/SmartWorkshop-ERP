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
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        $quantity = (int) $validated['quantity'];
        $durationDays = (int) $validated['duration_days'];

        DB::transaction(function () use ($quantity, $durationDays) {
            for ($i = 0; $i < $quantity; $i++) {
                $key = ProductKey::generateSecureKey();
                ProductKey::create([
                    'key' => $key,
                    'duration_days' => $durationDays,
                    'status' => 'unused',
                ]);
            }
        });

        return redirect()
            ->back()
            ->with('success', "Successfully generated {$quantity} new product keys of {$durationDays} days duration!");
    }

    /**
     * Remove the specified product key from database (if unused).
     */
    public function destroy(ProductKey $productKey)
    {
        if ($productKey->isUsed()) {
            return redirect()
                ->back()
                ->with('error', 'Cannot delete a product key that has already been redeemed.');
        }

        $productKey->delete();

        return redirect()
            ->back()
            ->with('success', 'Product key deleted successfully!');
    }

    /**
     * Redeem a product key to activate/extend a workshop's subscription.
     */
    public function activateLicense(Request $request)
    {
        $request->validate([
            'product_key' => 'required|string',
        ]);

        $inputKey = trim($request->product_key);

        $productKey = ProductKey::where('key', $inputKey)->first();

        if (!$productKey) {
            return redirect()
                ->back()
                ->with('error', 'Incorrect activation key. Please enter a valid product key.');
        }

        if ($productKey->isUsed()) {
            return redirect()
                ->back()
                ->with('error', 'This product key has already been redeemed.');
        }

        $user = auth()->user();
        $workshop = $user->workshop;

        if (!$workshop) {
            return redirect()
                ->back()
                ->with('error', 'You must belong to a workshop to activate a license.');
        }

        DB::transaction(function () use ($productKey, $workshop) {
            // Calculate new expiration date
            // If currently active and not expired, extend from existing expiration
            if ($workshop->isActive() && $workshop->trial_ends_at && $workshop->trial_ends_at->isFuture()) {
                $newExpiration = $workshop->trial_ends_at->copy()->addDays($productKey->duration_days);
            } else {
                // If expired or suspended, extend starting from now
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

        return redirect()
            ->route('dashboard')
            ->with('success', "License activated successfully! Your subscription is now active until " . $expiryDate->format('M d, Y') . ".");
    }

    /**
     * Update the specified product key duration.
     */
    public function update(Request $request, ProductKey $productKey)
    {
        if ($productKey->isUsed()) {
            return redirect()
                ->back()
                ->with('error', 'Cannot edit a product key that has already been redeemed.');
        }

        $validated = $request->validate([
            'duration_days' => 'required|integer|min:1',
        ]);

        $productKey->update([
            'duration_days' => (int) $validated['duration_days'],
        ]);

        return redirect()
            ->back()
            ->with('success', 'Product key updated successfully!');
    }
}
