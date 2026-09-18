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
use Illuminate\Validation\ValidationException;
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
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $nomField = $request->has('nom') ? 'nom' : 'name';
        $nom = $request->input($nomField);

        $request->validate([
            $nomField => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            // Le rôle administrateur est attribué uniquement depuis l'espace
            // d'administration ; il ne peut pas être choisi à l'inscription.
            'role' => ['nullable', 'string', 'in:proprietaire,acheteur'],
        ]);

        $user = User::create([
            'nom' => $nom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->input('role', User::ROLE_ACHETEUR),
        ]);

        event(new Registered($user));

        Auth::login($user);

        if ($user->isAdmin()) {
            return redirect(route('admin.dashboard', absolute: false));
        }

        return redirect(route('dashboard', absolute: false));
    }
}
