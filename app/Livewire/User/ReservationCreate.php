<?php

namespace App\Livewire\User;

use App\Livewire\User\Forms\ReservationForm;
use App\Models\Table;
use Livewire\Component;

class ReservationCreate extends Component
{
    public ReservationForm $form;

    public function create()
    {
        $this->form->store();
 
        return $this->redirect(route('user.reservation.index'));
    }

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function render()
    {
        $tables = Table::isAvailable($this->form->date)->get();

        return view('livewire.user.reservation-create', [
            'tables' => $tables,
        ]);
    }
}
