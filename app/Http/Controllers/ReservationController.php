<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function index(): View
    {
        $reservations = auth()->user()
            ->reservations()
            ->with(['table', 'user'])
            ->where('date', '>=', now()->toDateString())
            ->paginate(10);

        return view('reservation.index', [
            'reservations' => $reservations
        ]);
    }

    public function create(): View
    {
        return view('reservation.create');
    }

    public function destroy(Reservation $reservation): RedirectResponse
    {
        auth()->user()->reservations()->findOrFail($reservation->id)->delete();

        return redirect()->route('user.reservation.index');
    }
}
