<?php

use App\Models\User;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('admin reservation index cannot be accessed by guests', function () {
    $this->get(route('admin.reservation.index'))
        ->assertRedirect(route('login'));
});

it('admin reservation index cannot be accessed by non-admin users', function () {
    $user = User::factory()->create()->assignRole('user');
    $this->actingAs($user)
        ->get(route('admin.reservation.index'))
        ->assertForbidden();
});

it('admin reservation index can be rendered for admin users', function () {
    $admin = User::factory()->create()->assignRole('admin');
    $this->actingAs($admin)
        ->get(route('admin.reservation.index'))
        ->assertOk()
        ->assertViewIs('admin.reservation.index');
});

it('admin reservation create page cannot be accessed by guests', function () {
    $this->get(route('admin.reservation.create'))
        ->assertRedirect(route('login'));
});

it('admin reservation create page cannot be accessed by non-admin users', function () {
    $user = User::factory()->create()->assignRole('user');
    $this->actingAs($user)
        ->get(route('admin.reservation.create'))
        ->assertForbidden();
});

it('admin reservation create page can be rendered for admin users', function () {
    $admin = User::factory()->create()->assignRole('admin');
    $this->actingAs($admin)
        ->get(route('admin.reservation.create'))
        ->assertOk()
        ->assertViewIs('admin.reservation.create');
});

it('admin cannot destroy reservation if guest', function () {
    $reservation = Reservation::factory()->create();
    $this->delete(route('admin.reservation.destroy', $reservation))
        ->assertRedirect(route('login'));
});

it('admin cannot destroy reservation if non-admin user', function () {
    $user = User::factory()->create()->assignRole('user');
    $reservation = Reservation::factory()->create();
    $this->actingAs($user)
        ->delete(route('admin.reservation.destroy', $reservation))
        ->assertForbidden();
});

it('admin can destroy a reservation', function () {
    $admin = User::factory()->create()->assignRole('admin');
    $reservation = Reservation::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.reservation.destroy', $reservation))
        ->assertRedirect(route('admin.reservation.index'));

    $this->assertDatabaseMissing('reservations', ['id' => $reservation->id]);
});