<?php

namespace App\Livewire\Admin\Forms;

use App\Models\Reservation;
use App\Rules\Available;
use App\Rules\AvailableSeats;
use Livewire\Form;

class ReservationForm extends Form
{
    public $date;
    public $time;
    public $table;
    public $number_of_people;
    public $user;
    public $phone;
    public $comment;

    public function rules(): array
    {
        return [
            'date' => ['required', 'date', new Available(), 'after_or_equal:today'],
            'time' => ['required', 'date_format:H:i'],
            'table' => ['required', 'exists:tables,id'],
            'number_of_people' => [
                'required',
                'numeric',
                'min:1',
                new AvailableSeats(
                    $this->table,
                )
            ],
            'user' => ['required', 'exists:users,id'],
            'phone' => ['required', 'string', 'max:255', 'regex:/^\+?[0-9\s\-\(\)]+$/'],
            'comment' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function store()
    {
        $this->validate();

        Reservation::create([
            'table_id' => $this->table,
            'date' => $this->date,
            'time' => $this->time,
            'number_of_people' => $this->number_of_people,
            'user_id' => $this->user,
            'phone' => $this->phone,
            'comment' => $this->comment,
        ]);
    }
}
