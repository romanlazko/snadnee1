<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);

        return view('admin.user.index', [
            'users' => $users,
        ]);
    }

    public function create(): View
    {
        return view('admin.user.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make(str()->random(10)),
        ]);

        return redirect()->route('admin.user.index');
    }

    public function edit(User $user): View
    {
        return view('admin.user.edit', [
            'user' => $user,
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $user->update($request->validated());

        return redirect()->route('admin.user.index');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.user.index');
        }

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.user.index');
        }

        $user->delete();

        return redirect()->route('admin.user.index');
    }
}
