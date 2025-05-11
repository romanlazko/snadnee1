<?php

use App\Models\User;
use App\Models\Table;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('table index cannot be accessed by guests', function () {
    $this->get(route('admin.table.index'))
        ->assertRedirect(route('login'));
});

it('table index cannot be accessed by non-admin users', function () {
    $user = User::factory()->create()->assignRole('user');

    $this->actingAs($user)
        ->get(route('admin.table.index'))
        ->assertForbidden();
});

it('table index can be rendered for admin users and displays tables', function () {
    $admin = User::factory()->create()->assignRole('admin');
    Table::factory(5)->create();

    $this->actingAs($admin)
        ->get(route('admin.table.index'))
        ->assertOk()
        ->assertViewIs('admin.table.index')
        ->assertViewHas('tables', function ($tables) {
            return $tables->count() === 5;
        });
});

it('table create page cannot be accessed by guests', function () {
    $this->get(route('admin.table.create'))
        ->assertRedirect(route('login'));
});

it('table create page cannot be accessed by non-admin users', function () {
    $user = User::factory()->create()->assignRole('user');

    $this->actingAs($user)
        ->get(route('admin.table.create'))
        ->assertForbidden();
});

it('table create page can be rendered for admin users', function () {
    $admin = User::factory()->create()->assignRole('admin');

    $this->actingAs($admin)
        ->get(route('admin.table.create'))
        ->assertOk()
        ->assertViewIs('admin.table.create');
});

it('admin can store a new table with valid data', function () {
    $admin = User::factory()->create()->assignRole('admin');
    $table = Table::factory()->make();

    $this->actingAs($admin)
        ->post(route('admin.table.store'), $table->toArray())
        ->assertRedirect(route('admin.table.index'));

    $this->assertDatabaseHas('tables', [
        'name' => $table->name,
        'description' => $table->description,
        'seat_count' => $table->seat_count
    ]);
});

it('table edit page cannot be accessed by guests', function () {
    $table = Table::factory()->create();

    $this->get(route('admin.table.edit', $table))
        ->assertRedirect(route('login'));
});

it('table edit page cannot be accessed by non-admin users', function () {
    $user = User::factory()->create()->assignRole('user');
    $table = Table::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.table.edit', $table))
        ->assertForbidden();
});

it('table edit page can be rendered for admin users with table data', function () {
    $admin = User::factory()->create()->assignRole('admin');
    $table = Table::factory()->create();

    $this->actingAs($admin)
        ->get(route('admin.table.edit', $table))
        ->assertOk()
        ->assertViewIs('admin.table.edit')
        ->assertViewHas('table', $table);
});

it('admin can update an existing table with valid data', function () {
    $admin = User::factory()->create()->assignRole('admin');
    $table = Table::factory()->create();
    $updateData = Table::factory()->make()->toArray();

    $this->actingAs($admin)
        ->put(route('admin.table.update', $table), $updateData)
        ->assertRedirect(route('admin.table.index'));

    $this->assertDatabaseHas('tables', array_merge(['id' => $table->id], $updateData));

    if ($table->name !== $updateData['name']) {
        $this->assertDatabaseMissing('tables', ['name' => $table->getOriginal('name')]);
    }
});

it('admin cannot destroy table if guest', function () {
    $table = Table::factory()->create();

    $this->delete(route('admin.table.destroy', $table))
        ->assertRedirect(route('login'));

    $this->assertModelExists($table);
});

it('admin cannot destroy table if non-admin user', function () {
    $user = User::factory()->create()->assignRole('user');
    $table = Table::factory()->create();

    $this->actingAs($user)
        ->delete(route('admin.table.destroy', $table))
        ->assertForbidden();

    $this->assertModelExists($table);
});

it('admin can destroy a table', function () {
    $admin = User::factory()->create()->assignRole('admin');
    $table = Table::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.table.destroy', $table))
        ->assertRedirect(route('admin.table.index'));

    $this->assertModelMissing($table);
});