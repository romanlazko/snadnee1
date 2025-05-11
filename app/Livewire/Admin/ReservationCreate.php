<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Forms\ReservationForm;
use App\Models\Table;
use App\Models\User;
use Livewire\Component;

class ReservationCreate extends Component
{
    public ReservationForm $form;

    public function create()
    {
        $this->form->store();
 
        return $this->redirect(route('admin.reservation.index'));
    }

    public function updated($property)
    {
        $this->validateOnly($property);
    }
    
    public function render()
    {
        $tables = Table::isAvailable($this->form->date)->get();
        $users = User::all();

        return view('livewire.admin.reservation-create', [
            'tables' => $tables,
            'users' => $users,
        ]);
    }
}
