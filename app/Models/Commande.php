<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'numero_commande',
        'statut',
        'total',
        'nombre_articles',
        'date_commande',
        'date_livraison',
        'adresse_livraison',
        'ville_livraison',
        'telephone_livraison',
        'mode_paiement',
        'statut_paiement',
        'notes_client',
        'notes_admin',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'nombre_articles' => 'integer',
        'date_commande' => 'datetime',
        'date_livraison' => 'datetime',
    ];

    // Constantes pour les statuts
    const STATUT_EN_ATTENTE = 'en_attente';
    const STATUT_CONFIRMEE = 'confirmee';
    const STATUT_EN_PREPARATION = 'en_preparation';
    const STATUT_EN_LIVRAISON = 'en_livraison';
    const STATUT_LIVREE = 'livree';
    const STATUT_ANNULEE = 'annulee';

    const STATUT_PAIEMENT_EN_ATTENTE = 'en_attente';
    const STATUT_PAIEMENT_PAYE = 'paye';
    const STATUT_PAIEMENT_REMBOURSE = 'rembourse';

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec les articles de la commande
     */
    public function items()
    {
        return $this->hasMany(CommandeItem::class);
    }

    /**
     * Relation avec les produits via les items
     */
    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'commande_items')
                    ->withPivot('quantite', 'prix_unitaire', 'sous_total')
                    ->withTimestamps();
    }

    /**
     * Scope pour les commandes d'un utilisateur
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope pour les commandes par statut
     */
    public function scopeByStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    /**
     * Scope pour les commandes récentes
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('date_commande', '>=', Carbon::now()->subDays($days));
    }

    /**
     * Accessor pour le statut formaté
     */
    public function getStatutFormatteAttribute()
    {
        $statuts = [
            self::STATUT_EN_ATTENTE => 'En attente',
            self::STATUT_CONFIRMEE => 'Confirmée',
            self::STATUT_EN_PREPARATION => 'En préparation',
            self::STATUT_EN_LIVRAISON => 'En livraison',
            self::STATUT_LIVREE => 'Livrée',
            self::STATUT_ANNULEE => 'Annulée',
        ];

        return $statuts[$this->statut] ?? $this->statut;
    }

    /**
     * Accessor pour le statut de paiement formaté
     */
    public function getStatutPaiementFormatteAttribute()
    {
        $statuts = [
            self::STATUT_PAIEMENT_EN_ATTENTE => 'En attente',
            self::STATUT_PAIEMENT_PAYE => 'Payé',
            self::STATUT_PAIEMENT_REMBOURSE => 'Remboursé',
        ];

        return $statuts[$this->statut_paiement] ?? $this->statut_paiement;
    }

    /**
     * Vérifier si la commande peut être annulée
     */
    public function peutEtreAnnulee()
    {
        return in_array($this->statut, [
            self::STATUT_EN_ATTENTE,
            self::STATUT_CONFIRMEE
        ]);
    }

    /**
     * Vérifier si la commande est terminée
     */
    public function estTerminee()
    {
        return in_array($this->statut, [
            self::STATUT_LIVREE,
            self::STATUT_ANNULEE
        ]);
    }

    /**
     * Calculer le nombre de jours depuis la commande
     */
    public function getJoursDepuisCommandeAttribute()
    {
        return $this->date_commande->diffInDays(Carbon::now());
    }
}
