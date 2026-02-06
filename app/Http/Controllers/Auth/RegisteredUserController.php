<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Afficher le formulaire d'inscription
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Traiter l'inscription
     */
    public function store(Request $request)
{
    // Validation des champs
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'telephone' => ['required', 'string', 'max:20'],
        'adresse' => ['required', 'string', 'max:500'],
        'role' => ['required', 'in:acheteur,producteur'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ], [
        // Messages personnalisés...
    ]);

    // Créer l'utilisateur
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'telephone' => $request->telephone,
        'adresse' => $request->adresse,
        'role' => $request->role,
        'password' => Hash::make($request->password),
    ]);

    // Commentez ou supprimez cette ligne
    // event(new Registered($user));

    // Connecter automatiquement l'utilisateur
    Auth::login($user);

    // Rediriger vers le dashboard (ou la page d'accueil)
    // Rediriger selon le rôle de l'utilisateur
    if ($user->role === 'acheteur') {
        return redirect()->route('dashboard.acheteur')->with('success', 'Inscription réussie ! Bienvenue sur Foubadj.');
    } elseif ($user->role === 'producteur') {
        return redirect()->route('dashboard.producteur')->with('success', 'Inscription réussie ! Bienvenue sur Foubadj.');
    }

    // Redirection par défaut (au cas où)
    return redirect('/dashboard')->with('success', 'Inscription réussie ! Bienvenue sur Foubadj.');

}
}