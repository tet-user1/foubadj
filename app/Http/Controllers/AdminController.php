<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Afficher le dashboard avec la liste des utilisateurs et statistiques
     */
    public function index(Request $request)
    {
        // 📊 STATISTIQUES GLOBALES
        $stats = [
            // Totaux
            'total' => User::count(),
            'acheteurs' => User::where('role', 'acheteur')->count(),
            'producteurs' => User::where('role', 'producteur')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'not_verified' => User::whereNull('email_verified_at')->count(),
            
            // Inscriptions par période
            'inscriptions_today' => User::whereDate('created_at', Carbon::today())->count(),
            'inscriptions_week' => User::whereBetween('created_at', [
                Carbon::now()->startOfWeek(), 
                Carbon::now()->endOfWeek()
            ])->count(),
            'inscriptions_month' => User::whereMonth('created_at', Carbon::now()->month)
                                        ->whereYear('created_at', Carbon::now()->year)
                                        ->count(),
            'new_this_month' => User::whereMonth('created_at', Carbon::now()->month)
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->count(),
        ];

        // Calculer les pourcentages
        if ($stats['total'] > 0) {
            $stats['acheteurs_percent'] = round(($stats['acheteurs'] / $stats['total']) * 100);
            $stats['producteurs_percent'] = round(($stats['producteurs'] / $stats['total']) * 100);
        } else {
            $stats['acheteurs_percent'] = 0;
            $stats['producteurs_percent'] = 0;
        }

        // 🔍 RÉCUPÉRER LES UTILISATEURS AVEC FILTRES
        $query = User::withCount(['produits', 'commandes']);

        // Filtre par recherche (nom, email, téléphone)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%");
            });
        }

        // Filtre par rôle
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filtre par email vérifié
        if ($request->filled('email_verified')) {
            if ($request->email_verified === 'verified') {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        // Filtre par statut (actif/inactif)
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } else {
                $query->where('is_active', false);
            }
        }

        // Pagination
        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.dashboard', compact('users', 'stats'));
    }

    /**
     * Afficher les détails d'un utilisateur
     */
    public function show(User $user)
    {
        // Charger les relations avec compteurs
        $user->loadCount(['produits', 'commandes']);
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Changer le rôle d'un utilisateur
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:acheteur,producteur,admin'
        ]);

        // Empêcher de modifier son propre rôle
        if ($user->id === auth()->id()) {
            return back()->with('error', '❌ Vous ne pouvez pas modifier votre propre rôle');
        }

        $oldRole = $user->role;
        $user->update(['role' => $request->role]);

        return back()->with('success', "✅ Rôle changé de {$oldRole} à {$request->role} avec succès");
    }

    /**
     * Vérifier manuellement l'email d'un utilisateur
     */
    public function verifyEmail(User $user)
    {
        if ($user->hasVerifiedEmail()) {
            return back()->with('info', 'ℹ️ Cet utilisateur a déjà vérifié son email');
        }

        $user->markEmailAsVerified();

        return back()->with('success', "✅ Email de {$user->name} vérifié manuellement avec succès");
    }

    /**
     * Activer/Désactiver un utilisateur
     */
    public function toggleStatus(User $user)
    {
        // Empêcher de désactiver son propre compte
        if ($user->id === auth()->id()) {
            return back()->with('error', '❌ Vous ne pouvez pas désactiver votre propre compte');
        }

        $isActive = $user->is_active ?? true;
        $user->update(['is_active' => !$isActive]);

        $status = !$isActive ? 'activé' : 'désactivé';
        return back()->with('success', "✅ Utilisateur {$status} avec succès");
    }

    /**
     * Supprimer un utilisateur
     */
    public function destroy(User $user)
    {
        // Empêcher de supprimer son propre compte
        if ($user->id === auth()->id()) {
            return back()->with('error', '❌ Vous ne pouvez pas supprimer votre propre compte');
        }

        $userName = $user->name;
        $user->delete();

        return back()->with('success', "✅ Utilisateur {$userName} supprimé avec succès");
    }
}