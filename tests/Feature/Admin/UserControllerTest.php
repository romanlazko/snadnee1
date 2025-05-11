<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('user index cannot be accessed by guests', function () {
    $this->get(route('admin.user.index'))
        ->assertRedirect(route('login'));
});

it('user index cannot be accessed by non-admin users', function () {
    $user = User::factory()->create()->assignRole('user');

    $this->actingAs($user)
        ->get(route('admin.user.index'))
        ->assertForbidden();
});

it('user index can be rendered for admin users and displays users', function () {
    $admin = User::factory()->create()->assignRole('admin');
    User::factory(3)->create();

    $this->actingAs($admin)
        ->get(route('admin.user.index'))
        ->assertOk()
        ->assertViewIs('admin.user.index')
        ->assertViewHas('users', function ($users) {
            return $users->count() >= 4; 
        });
});

it('user create page cannot be accessed by guests', function () {
    $this->get(route('admin.user.create'))
        ->assertRedirect(route('login'));
});

it('user create page cannot be accessed by non-admin users', function () {
    $user = User::factory()->create()->assignRole('user');

    $this->actingAs($user)
        ->get(route('admin.user.create'))
        ->assertForbidden();
});

it('user create page can be rendered for admin users', function () {
    $admin = User::factory()->create()->assignRole('admin');

    $this->actingAs($admin)
        ->get(route('admin.user.create'))
        ->assertOk()
        ->assertViewIs('admin.user.create');
});

it('admin can store a new user with valid data', function () {
    $admin = User::factory()->create()->assignRole('admin');
    $userData = [
        'name' => 'New Store User',
        'email' => 'newstoreuser@example.com',
    ];

    $response = $this->actingAs($admin)
        ->post(route('admin.user.store'), $userData);
    
    $response->assertRedirect(route('admin.user.index'));

    $this->assertDatabaseHas('users', [
        'name' => 'New Store User',
        'email' => 'newstoreuser@example.com',
    ]);
});

// Edit tests
it('user edit page cannot be accessed by guests', function () {
    $user = User::factory()->create();

    $this->get(route('admin.user.edit', $user))
        ->assertRedirect(route('login'));
});

it('user edit page cannot be accessed by non-admin users', function () {
    $user = User::factory()->create()->assignRole('user');

    $this->actingAs($user)
        ->get(route('admin.user.edit', $user))
        ->assertForbidden();
});

it('user edit page can be rendered for admin users', function () {
    $admin = User::factory()->create()->assignRole('admin');
    $user = User::factory()->create();

    $this->actingAs($admin)
        ->get(route('admin.user.edit', $user))
        ->assertOk()
        ->assertViewIs('admin.user.edit')
        ->assertViewHas('user', $user);
});

it('admin can update an existing user with valid data', function () {
    $admin = User::factory()->create()->assignRole('admin');
    $user = User::factory()->create(); 
    $updateData = [
        'name' => 'Updated Name Again',
        'email' => 'updated_again@example.com',
    ];

    $this->actingAs($admin)
        ->put(route('admin.user.update', $user), $updateData)
        ->assertRedirect(route('admin.user.index'));

    $this->assertDatabaseHas('users', array_merge(['id' => $user->id], $updateData));
});

it('guest cannot destroy user', function () {
    $user = User::factory()->create();

    $this->delete(route('admin.user.destroy', $user))
        ->assertRedirect(route('login'));

    $this->assertModelExists($user);
});

it('non-admin user cannot destroy user', function () {
    $user = User::factory()->create()->assignRole('user');
    $userToDelete = User::factory()->create();

    $this->actingAs($user)
        ->delete(route('admin.user.destroy', $userToDelete))
        ->assertForbidden();

    $this->assertModelExists($userToDelete);
});

it('admin can destroy a user', function () {
    $admin = User::factory()->create()->assignRole('admin');
    $user = User::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.user.destroy', $user))
        ->assertRedirect(route('admin.user.index'));

    $this->assertModelMissing($user);
});