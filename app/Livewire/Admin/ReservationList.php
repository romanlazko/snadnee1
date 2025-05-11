<?php

namespace App\Livewire\Admin;

use App\Models\Reservation;
use Livewire\Component;
use Livewire\WithPagination;

class ReservationList extends Component
{
    use WithPagination;

    public $date;

    public function mount()
    {
        $this->date = now()->toDateString();
    }

    public function render()
    {
        $reservations = Reservation::when($this->date, fn ($query) => 
                $query->whereDate('date', $this->date)
            )
            ->with(['table', 'user'])
            ->orderBy('time')
            ->paginate(10);

        return view('livewire.admin.reservation-list', [
            'reservations' => $reservations,
        ]);
    }
}
