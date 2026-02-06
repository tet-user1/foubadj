<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 
        'description', 
        'prix', 
        'quantite', 
        'image',
        'user_id',
        'categorie' 
    ];

    protected $casts = [
        'prix' => 'decimal:2',
        'quantite' => 'integer',
    ];

    // Ajouter l'accesseur dans les attributs calculés
    protected $appends = ['image_url'];

    /**
     * Relation avec l'utilisateur (producteur)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accesseur pour obtenir l'URL complète de l'image
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            $imagePath = public_path('images/produits/' . $this->image);
            if (file_exists($imagePath)) {
                return asset('images/produits/' . $this->image);
            }
        }
        return null;
    }

    /**
     * Méthode pour vérifier si le produit a une image
     */
    public function hasImage()
    {
        return $this->image && file_exists(public_path('images/produits/' . $this->image));
    }

    /**
     * Supprimer l'image lors de la suppression du produit
     */
    protected static function boot()
    {
        parent::boot();
        
        static::deleting(function ($produit) {
            if ($produit->image) {
                $imagePath = public_path('images/produits/' . $produit->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        });
    }

    /**
     * Scope pour les produits avec image
     */
    public function scopeWithImage($query)
    {
        return $query->whereNotNull('image');
    }

    /**
     * Scope pour les produits sans image
     */
    public function scopeWithoutImage($query)
    {
        return $query->whereNull('image');
    }

    /**
     * Scope pour les produits en stock
     */
    public function scopeInStock($query)
    {
        return $query->where('quantite', '>', 0);
    }

    /**
     * Scope pour les produits d'un utilisateur spécifique
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function commandes()
    {
        return $this->belongsToMany(Commande::class, 'commande_produit')
                    ->withPivot('quantite')
                    ->withTimestamps();
    }
}