<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_edit_and_delete_a_cashier_account(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->get(route('users.index'))->assertOk();
        $this->actingAs($admin)->get(route('users.create'))->assertOk();

        $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'Kasir Baru',
            'email' => 'kasir-baru@example.com',
            'role' => 'kasir',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('users.index'));

        $cashier = User::where('email', 'kasir-baru@example.com')->firstOrFail();
        $this->assertSame('kasir', $cashier->role);
        $this->assertTrue(Hash::check('password123', $cashier->password));

        $this->actingAs($admin)->get(route('users.edit', $cashier))->assertOk();
        $this->actingAs($admin)->put(route('users.update', $cashier), [
            'name' => 'Kasir Diperbarui',
            'email' => 'kasir-baru@example.com',
            'role' => 'kasir',
            'password' => '',
            'password_confirmation' => '',
        ])->assertRedirect(route('users.index'));

        $this->assertSame('Kasir Diperbarui', $cashier->refresh()->name);
        $this->assertTrue(Hash::check('password123', $cashier->password));

        $this->actingAs($admin)->delete(route('users.destroy', $cashier))
            ->assertRedirect(route('users.index'));
        $this->assertDatabaseMissing('users', ['id' => $cashier->id]);
    }

    public function test_admin_cannot_remove_or_demote_their_own_account(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->put(route('users.update', $admin), [
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => 'kasir',
            'password' => '',
            'password_confirmation' => '',
        ])->assertSessionHasErrors('role');

        $this->actingAs($admin)->delete(route('users.destroy', $admin))
            ->assertSessionHasErrors('user');
        $this->assertSame('admin', $admin->refresh()->role);
    }
}
