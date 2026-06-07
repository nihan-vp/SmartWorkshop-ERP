<?php

namespace Tests\Feature;

use App\Models\ProductKey;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SubscriptionDashboardTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Workshop $workshop;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up clean database data on-the-fly
        $this->workshop = Workshop::create([
            'name' => 'Test Workshop',
            'phone' => '1234567890',
            'email' => 'test@workshop.com',
            'subscription_status' => 'active',
            'trial_ends_at' => now()->addDays(10),
            'restrict_features_on_expiry' => true,
        ]);

        $this->user = User::create([
            'name' => 'Workshop Manager',
            'email' => 'clinicppm@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'workshop_id' => $this->workshop->id,
        ]);
    }

    /**
     * Test client dashboard renders successfully and contains the redesigned subscription card.
     */
    public function test_client_dashboard_renders_subscription_card(): void
    {
        $response = $this->actingAs($this->user)->get('/dashboard');
        file_put_contents(base_path('tests/debug.html'), $response->getContent());

        $response->assertStatus(200);
        $response->assertSee('Subscription');
        $response->assertSee('Trial');
        $response->assertSee('Account Status');
        $response->assertSee('Total Duration');
        $response->assertSee('Update Subscription');
    }

    /**
     * Test invalid license key redemption.
     */
    public function test_invalid_license_key_redemption(): void
    {
        $response = $this->actingAs($this->user)->post('/activate-license', [
            'product_key' => 'INVALID-KEY-1234',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Incorrect activation key. Please enter a valid product key.');
    }

    /**
     * Test valid license key redemption.
     */
    public function test_valid_license_key_redemption(): void
    {
        $key = 'SUHAIM-TEST-TEMP-9999';
        ProductKey::create([
            'key' => $key,
            'duration_days' => 45,
            'status' => 'unused',
        ]);

        $response = $this->actingAs($this->user)->post('/activate-license', [
            'product_key' => $key,
        ]);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success');

        $this->workshop->refresh();
        $this->assertEquals('active', $this->workshop->subscription_status);
        $this->assertNotNull($this->workshop->trial_ends_at);
        $this->assertTrue($this->workshop->trial_ends_at->isFuture());

        // Key should be marked used by this workshop
        $productKey = ProductKey::where('key', $key)->first();
        $this->assertEquals('used', $productKey->status);
        $this->assertEquals($this->workshop->id, $productKey->used_by_workshop_id);
    }
}
