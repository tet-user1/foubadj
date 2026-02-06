<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Les attributs pouvant être assignés en masse
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',  // Rôle de l'utilisateur : acheteur, producteur, admin
        'telephone',  // Numéro de téléphone
        'adresse',    // Adresse de l'utilisateur
    ];

    /**
     * Les attributs à cacher pour la sérialisation
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les casts d'attributs
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Vérifie si l'utilisateur a un rôle spécifique
     *
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Vérifie si l'utilisateur est administrateur
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Vérifie si l'utilisateur est producteur
     *
     * @return bool
     */
    public function isProducteur(): bool
    {
        return $this->hasRole('producteur');
    }

    /**
     * Vérifie si l'utilisateur est acheteur
     *
     * @return bool
     */
    public function isAcheteur(): bool
    {
        return $this->hasRole('acheteur');
    }

    /**
     * Obtenir le rôle formaté
     */
    public function getRoleFormattedAttribute()
    {
        $roles = [
            'admin' => 'Administrateur',
            'producteur' => 'Producteur',
            'acheteur' => 'Acheteur',
        ];
        
        return $roles[$this->role] ?? 'Utilisateur';
    }

    /**
     * Relation : un producteur possède plusieurs produits
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function produits()
    {
        return $this->hasMany(Produit::class, 'user_id');
    }

    /**
     * Optionnel : récupérer les commandes d'un utilisateur
     */
    public function commandes()
    {
        return $this->hasMany(Commande::class, 'user_id');
    }
}