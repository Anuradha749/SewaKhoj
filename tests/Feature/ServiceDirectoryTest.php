<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\ServiceProvider;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceDirectoryTest extends TestCase
{
    use RefreshDatabase;

    private function provider(array $attributes = []): ServiceProvider
    {
        $category = Category::firstOrCreate(['name' => 'Plumber'], ['is_active' => true]);

        return ServiceProvider::create(array_merge([
            'name' => 'Local Plumbing', 'phone' => '9800000001',
            'address' => 'Itahari', 'category_id' => $category->id,
            'experience_years' => 4, 'is_active' => true,
        ], $attributes));
    }

    public function test_directory_filters_by_service_location_and_category(): void
    {
        $provider = $this->provider();
        $this->provider(['name' => 'Other Town', 'phone' => '9800000002', 'address' => 'Dharan']);
        $this->get('/services?search=Plumber&location=Itahari&category='.$provider->category_id)
            ->assertOk()->assertSee('Local Plumbing')->assertDontSee('Other Town');
        $this->get('/services?search=Electrician')->assertOk()->assertSee('No matching providers yet');
    }

    public function test_inactive_and_uncategorized_providers_are_not_listed(): void
    {
        $provider = $this->provider(['name' => 'Hidden Provider', 'is_active' => false]);
        $this->get('/')->assertOk()->assertDontSee('Hidden Provider');
        $provider->update(['is_active' => true]);
        $provider->category->update(['is_active' => false]);
        $this->get('/')->assertOk()->assertDontSee('Hidden Provider');
        $provider->category->delete();
        $this->get('/')->assertOk()->assertDontSee('Hidden Provider');
    }

    public function test_guests_and_customers_cannot_manage_provider_profiles(): void
    {
        $this->get('/provider/profile')->assertRedirect('/login');
        $this->post('/provider/profile', [])->assertRedirect('/login');
        $this->actingAs(User::factory()->create(['role' => 'customer']))
            ->get('/provider/profile')->assertForbidden();
        $this->post('/provider/profile', [])->assertForbidden();
    }

    public function test_provider_can_create_and_update_only_their_own_listing(): void
    {
        $other = $this->provider();
        $user = User::factory()->create(['role' => 'provider']);
        $payload = ['name' => 'My Service', 'phone' => '9800000003', 'email' => '',
            'address' => 'Dharan', 'bio' => 'Repairs', 'experience_years' => 2,
            'category_id' => $other->category_id, 'user_id' => 999, 'is_verified' => true];
        $this->actingAs($user)->get('/provider/profile')->assertOk();
        $this->post('/provider/profile', $payload)->assertSessionHasNoErrors()->assertRedirect(route('provider.profile'));
        $this->assertDatabaseHas('service_providers', ['user_id' => $user->id, 'name' => 'My Service', 'is_verified' => false]);
        $payload['name'] = 'Updated Service';
        $this->post('/provider/profile', $payload)->assertSessionHasNoErrors();
        $this->assertDatabaseCount('service_providers', 2);
        $this->assertSame('Local Plumbing', $other->fresh()->name);
        $this->get('/provider/profile')->assertOk()->assertSee('Updated Service');
    }

    public function test_inactive_categories_and_duplicate_phones_are_rejected(): void
    {
        $provider = $this->provider();
        $provider->category->update(['is_active' => false]);
        $payload = ['name' => 'New', 'phone' => $provider->phone, 'address' => 'Itahari',
            'experience_years' => 2, 'category_id' => $provider->category_id];
        $this->actingAs(User::factory()->create(['role' => 'provider']))
            ->post('/provider/profile', $payload)->assertSessionHasErrors(['phone', 'category_id']);
    }

    public function test_editing_does_not_reactivate_a_disabled_listing(): void
    {
        $user = User::factory()->create(['role' => 'provider']);
        $provider = $this->provider(['user_id' => $user->id, 'is_active' => false]);
        $this->actingAs($user)->post('/provider/profile', [
            'name' => $provider->name, 'phone' => $provider->phone, 'address' => 'Itahari',
            'experience_years' => 4, 'category_id' => $provider->category_id,
        ])->assertSessionHasNoErrors();
        $this->assertFalse($provider->fresh()->is_active);
    }

    public function test_account_deletion_removes_public_provider_details(): void
    {
        $user = User::factory()->create(['role' => 'provider']);
        $provider = $this->provider(['user_id' => $user->id]);
        $this->actingAs($user)->delete('/profile', ['password' => 'password'])->assertRedirect('/');
        $this->assertDatabaseMissing('service_providers', ['id' => $provider->id]);
    }

    public function test_category_seeder_can_be_run_twice(): void
    {
        $this->seed(CategorySeeder::class);
        $this->seed(CategorySeeder::class);
        $this->assertDatabaseCount('categories', 6);
    }

    public function test_provider_registration_and_invalid_roles(): void
    {
        $payload = ['name' => 'Provider', 'email' => 'provider@example.com',
            'password' => 'password', 'password_confirmation' => 'password', 'role' => 'admin'];
        $this->post('/register', $payload)->assertSessionHasErrors('role');
        $payload['role'] = 'provider';
        $this->post('/register', $payload)->assertSessionHasNoErrors()->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', ['email' => 'provider@example.com', 'role' => 'provider']);
        $this->get('/dashboard')->assertOk()->assertSee('Manage My Profile');
    }
}
