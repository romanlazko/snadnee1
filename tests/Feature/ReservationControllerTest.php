<?php

use App\Models\User;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('cannot be accessed by guests', function () {
    $this->get(route('user.reservation.index'))
        ->assertRedirect(route('login'));
});

it('displays reservations for authenticated user', function () {
    $user = User::factory()->create();
    $reservation1 = Reservation::factory()->create(['user_id' => $user->id, 'date' => now()->addDay()->toDateString()]);
    $reservation2 = Reservation::factory()->create(['user_id' => $user->id, 'date' => now()->addDays(2)->toDateString()]);

    Reservation::factory()->create(['user_id' => $user->id, 'date' => now()->subDay()->toDateString()]);

    $this->actingAs($user)
        ->get(route('user.reservation.index'))
        ->assertOk()
        ->assertViewIs('reservation.index')
        ->assertViewHas('reservations', function ($reservations) use ($reservation1, $reservation2) {
            return $reservations->contains($reservation1) &&
                   $reservations->contains($reservation2) &&
                   count($reservations) === 2;
        });
});

it('paginates reservations on the index page', function () {
    $user = User::factory()->create();
    Reservation::factory(15)->create(['user_id' => $user->id, 'date' => now()->addDay()->toDateString()]);

    $this->actingAs($user)
        ->get(route('user.reservation.index'))
        ->assertOk()
        ->assertViewHas('reservations', function ($reservations) {
            return $reservations->count() === 10; // Default pagination is 10
        });
});


it('cannot be accessed by guests for create', function () {
    $this->get(route('user.reservation.create'))
        ->assertRedirect(route('login'));
});

it('shows the create reservation page for authenticated user', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('user.reservation.create'))
        ->assertOk()
        ->assertViewIs('reservation.create');
});


it('cannot be accessed by guests for destroy', function () {
    $reservation = Reservation::factory()->create();
    
    $this->delete(route('user.reservation.destroy', $reservation))
        ->assertRedirect(route('login'));
});

it('allows an authenticated user to delete their own reservation', function () {
    $user = User::factory()->create();
    $reservation = Reservation::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->delete(route('user.reservation.destroy', $reservation))
        ->assertRedirect(route('user.reservation.index'));

    $this->assertDatabaseMissing('reservations', ['id' => $reservation->id]);
});

it('prevents an authenticated user from deleting another user\\\'s reservation', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $reservation = Reservation::factory()->create(['user_id' => $user2->id]);

    $this->actingAs($user1)
        ->delete(route('user.reservation.destroy', $reservation))
        ->assertNotFound();

    $this->assertDatabaseHas('reservations', ['id' => $reservation->id]);
});