<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panier extends Model
{
    use HasFactory;

    protected $table = 'paniers';

    protected $fillable = [
        'user_id',
        'produit_id',
        'quantite',
    ];

    protected $casts = [
        'quantite' => 'integer',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec le produit
     */
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    /**
     * Calculer le sous-total
     */
    public function getSousTotal()
    {
        return $this->quantite * $this->produit->prix;
    }

    /**
     * Scope pour récupérer le panier d'un utilisateur
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Vérifier si la quantité demandée est disponible
     */
    public function isQuantityAvailable()
    {
        return $this->quantite <= $this->produit->quantite;
    }
}
