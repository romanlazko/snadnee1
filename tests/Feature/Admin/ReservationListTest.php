<?php

use App\Livewire\Admin\ReservationList;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('renders successfully for admin users', function () {
    $admin = User::factory()->create()->assignRole('admin');
    $this->actingAs($admin);

    Livewire::test(ReservationList::class)
        ->assertStatus(200);
});

it('mounts with current date and fetches today\'s reservations by default', function () {
    $admin = User::factory()->create()->assignRole('admin');
    $this->actingAs($admin);

    $todayString = now()->toDateString();

    Reservation::factory()->create(['date' => $todayString, 'time' => '10:00:00']);
    Reservation::factory()->create(['date' => $todayString, 'time' => '12:00:00']);
    Reservation::factory()->create(['date' => now()->subDay()->toDateString(), 'time' => '11:00:00']);
    Reservation::factory()->create(['date' => now()->addDay()->toDateString(), 'time' => '13:00:00']);

    Livewire::test(ReservationList::class)
        ->assertSet('date', $todayString)
        ->assertViewHas('reservations', function ($reservations) use ($todayString) {
            return $reservations->count() === 2 && $reservations->every(fn (Reservation $r) => $r->date->toDateString() === $todayString);
        });
});

it('fetches reservations for a specific date when date property is updated', function () {
    $admin = User::factory()->create()->assignRole('admin');
    $this->actingAs($admin);
    $targetDateString = now()->addDays(3)->toDateString();

    Reservation::factory()->create(['date' => $targetDateString, 'time' => '14:00:00']);
    Reservation::factory()->create(['date' => $targetDateString, 'time' => '15:00:00']);
    Reservation::factory()->create(['date' => now()->toDateString(), 'time' => '16:00:00']);

    Livewire::test(ReservationList::class)
        ->set('date', $targetDateString)
        ->assertViewHas('reservations', function ($reservations) use ($targetDateString) {
            return $reservations->count() === 2 && $reservations->every(fn (Reservation $r) => $r->date->toDateString() === $targetDateString);
        });
});

it('shows no reservations if none exist for the selected date', function () {
    $admin = User::factory()->create()->assignRole('admin');
    $this->actingAs($admin);
    $targetDateString = now()->addDays(5)->toDateString();

    Reservation::factory()->create(['date' => now()->toDateString(), 'time' => '17:00:00']);

    Livewire::test(ReservationList::class)
        ->set('date', $targetDateString)
        ->assertViewHas('reservations', function ($reservations) {
            return $reservations->isEmpty();
        });
});

it('paginates reservations', function () {
    $admin = User::factory()->create()->assignRole('admin');
    $this->actingAs($admin);
    $targetDateString = now()->addDay()->toDateString();

    Reservation::factory(15)->create(['date' => $targetDateString]);
    Reservation::factory()->create(['date' => now()->toDateString()]);

    Livewire::test(ReservationList::class)
        ->set('date', $targetDateString)
        ->assertViewHas('reservations', function ($reservations) {
            return $reservations->count() === 10;
        })
        ->call('nextPage')
        ->assertViewHas('reservations', function ($reservations) {
            return $reservations->count() === 5;
        });
});