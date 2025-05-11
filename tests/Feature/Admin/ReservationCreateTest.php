<?php

use App\Livewire\Admin\ReservationCreate;
use App\Models\Reservation;
use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create()->assignRole('admin');
    $this->actingAs($this->admin);
});

it('renders successfully for admin users', function () {
    Livewire::test(ReservationCreate::class)
        ->assertStatus(200);
});

it('can create a reservation with valid data', function () {
    $table = Table::factory()->create(['seat_count' => 4]);
    $date = now()->addDay();
    $user = User::factory()->create();

    Livewire::test(ReservationCreate::class)
        ->set('form.user', $user->id)
        ->set('form.date', $date)
        ->set('form.time', '14:00')
        ->set('form.table', $table->id)
        ->set('form.number_of_people', 2)
        ->set('form.phone', '+1234567890')
        ->set('form.comment', 'Test comment')
        ->call('create')
        ->assertRedirect(route('admin.reservation.index'));

    $this->assertDatabaseHas('reservations', [
        'user_id' => $user->id,
        'table_id' => $table->id,
        'date' => $date,
        'time' => '14:00',
        'number_of_people' => 2,
        'phone' => '+1234567890',
        'comment' => 'Test comment',
    ]);
});

it('passes available tables and users to the view', function () {
    $table = Table::factory()->create();
    $users = User::factory(2)->create();

    Livewire::test(ReservationCreate::class)
        ->assertViewHas('tables', function ($tables) use ($table) {
            return $tables->contains('id', $table->id);
        })
        ->assertViewHas('users', function ($formUsers) use ($users) {
            return $formUsers->whereIn('id', $users->pluck('id')->toArray())->isNotEmpty();
        });
});

it('validate in real-time is triggered on property update for the form', function ($field, $value = '') {
    Livewire::test(ReservationCreate::class)
        ->set("form.{$field}", $value)
        ->assertHasErrors(["form.{$field}" => 'required']);
})->with([
    'date',
    'time',
    'table',
    'number_of_people',
    'phone',
]);

it('validate fails for required fields', function ($field, $value = '') {
    Livewire::test(ReservationCreate::class)
        ->set("form.{$field}", $value)
        ->call('create')
        ->assertHasErrors(["form.{$field}" => 'required']);
})->with([
    'date',
    'time',
    'table',
    'number_of_people',
    'phone',
]);

test('validation fails for date rule (past or unavailable)', function () {
    $table = Table::factory()->create();
    $user = User::factory()->create();

    Livewire::test(ReservationCreate::class)
        ->set('form.user', $user->id)
        ->set('form.date', now()->subDay())
        ->set('form.time', '14:00')
        ->set('form.table', $table->id)
        ->set('form.number_of_people', 1)
        ->set('form.phone', '+1234567890')
        ->call('create')
        ->assertHasErrors(['form.date']);

    Reservation::factory()->create([
        'date' => now()->addDay(),
        'table_id' => $table->id,
    ]);

    Livewire::test(ReservationCreate::class)
        ->set('form.user', $user->id)
        ->set('form.date', now()->addDay())
        ->set('form.time', '14:00')
        ->set('form.table', $table->id)
        ->set('form.number_of_people', 1)
        ->set('form.phone', '+1234567890')
        ->call('create')
        ->assertHasErrors(['form.date']);
});

test('validation fails for invalid time format', function () {
    Livewire::test(ReservationCreate::class)
        ->set('form.time', 'invalid-time')
        ->call('create')
        ->assertHasErrors(['form.time' => 'date_format']);
});

test('validation fails for non-existent table', function () {
    Livewire::test(ReservationCreate::class)
        ->set('form.table', 999)
        ->call('create')
        ->assertHasErrors(['form.table' => 'exists']);
});

test('validation fails for invalid phone format', function () {
    Livewire::test(ReservationCreate::class)
        ->set('form.phone', 'invalid-phone')
        ->call('create')
        ->assertHasErrors(['form.phone' => 'regex']);
});

test('validation fails for number of people (min and available seats)', function () {
    $table = Table::factory()->create(['seat_count' => 2]);
    $user = User::factory()->create();

    Livewire::test(ReservationCreate::class)
        ->set('form.user', $user->id)
        ->set('form.date', now()->addDay()->toDateString())
        ->set('form.time', '14:00')
        ->set('form.table', $table->id)
        ->set('form.number_of_people', 0)
        ->set('form.phone', '+1234567890')
        ->call('create')
        ->assertHasErrors(['form.number_of_people' => 'min']);

    Livewire::test(ReservationCreate::class)
        ->set('form.user', $user->id)
        ->set('form.date', now()->addDay()->toDateString())
        ->set('form.time', '14:00')
        ->set('form.table', $table->id)
        ->set('form.number_of_people', $table->seat_count + 1)
        ->set('form.phone', '+1234567890')
        ->call('create')
        ->assertHasErrors(['form.number_of_people']);
});

test('comment is optional and can be null', function () {
    $table = Table::factory()->create(['seat_count' => 4]);
    $user = User::factory()->create();
    $date = now()->addDay()->toDateString();

    Livewire::test(ReservationCreate::class)
        ->set('form.user', $user->id)
        ->set('form.date', $date)
        ->set('form.time', '15:00')
        ->set('form.table', $table->id)
        ->set('form.number_of_people', 1)
        ->set('form.phone', '+1987654321')
        ->set('form.comment', null)
        ->call('create')
        ->assertRedirect(route('admin.reservation.index'))
        ->assertHasNoErrors('form.comment');

    $this->assertDatabaseHas('reservations', [
        'user_id' => $user->id,
        'table_id' => $table->id,
        'phone' => '+1987654321',
        'comment' => null,
    ]);
});