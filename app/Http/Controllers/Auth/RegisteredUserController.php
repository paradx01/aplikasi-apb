<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'min:8', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.required' => 'The name field is required',
            'name.max' => 'The name must not be greater than 255 characters',
            'email.required' => 'The email field is required',
            'email.email' => 'The email must be a valid email address',
            'email.lowercase' => 'The email must be lowercase',
            'email.unique' => 'The email has already been taken',
            'password.required' => 'The password field is required',
            'password.min' => 'The password must be at least 8 characters',
            'password.confirmed' => 'The password confirmation does not match',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('buyer');

        event(new Registered($user));

        Auth::login($user);

        // Redirect sesuai role
        if ($user->hasRole('apoteker')) {
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('profile.complete'); // Onboarding: isi data profil medis
        }
    }
}
