<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeFinancement extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'nom',
        'email',
        'telephone',
        'type_utilisateur',
        'titre_projet',
        'montant_demande',
        'duree_remboursement',
        'description_projet',
        'utilisation_fonds',
        'piece_identite',
        'justificatif_domicile',
        'plan_affaires',
        'autres_documents',
        'statut',
        'numero_dossier',
        'notes_admin',
        'newsletter_optin',
        'date_approbation',
        'date_refus',
        'date_annulation',
    ];
    
    protected $casts = [
        'montant_demande' => 'decimal:2',
        'autres_documents' => 'array',
        'newsletter_optin' => 'boolean',
        'date_approbation' => 'datetime',
        'date_refus' => 'datetime',
        'date_annulation' => 'datetime',
    ];
    
    // Constantes pour les statuts
    const STATUT_EN_ATTENTE = 'en_attente';
    const STATUT_EN_ETUDE = 'en_etude';
    const STATUT_EN_TRAITEMENT = 'en_traitement';
    const STATUT_APPROUVE = 'approuve';
    const STATUT_REFUSE = 'refuse';
    const STATUT_ANNULE = 'annule';
    
    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Scope pour les demandes en cours
     */
    public function scopeEnCours($query)
    {
        return $query->whereIn('statut', [
            self::STATUT_EN_ATTENTE,
            self::STATUT_EN_ETUDE,
            self::STATUT_EN_TRAITEMENT
        ]);
    }
    
    /**
     * Scope pour les demandes terminées
     */
    public function scopeTerminees($query)
    {
        return $query->whereIn('statut', [
            self::STATUT_APPROUVE,
            self::STATUT_REFUSE,
            self::STATUT_ANNULE
        ]);
    }
    
    /**
     * Formater le montant
     */
    public function getMontantFormateAttribute()
    {
        return number_format($this->montant_demande, 0, ',', ' ') . ' FCFA';
    }
    
    /**
     * Formater la durée
     */
    public function getDureeFormateeAttribute()
    {
        return $this->duree_remboursement . ' mois';
    }
    
    /**
     * Formater le statut
     */
    public function getStatutFormateAttribute()
    {
        $statuts = [
            self::STATUT_EN_ATTENTE => ['label' => 'En attente', 'class' => 'badge bg-warning'],
            self::STATUT_EN_ETUDE => ['label' => 'En étude', 'class' => 'badge bg-info'],
            self::STATUT_EN_TRAITEMENT => ['label' => 'En traitement', 'class' => 'badge bg-primary'],
            self::STATUT_APPROUVE => ['label' => 'Approuvé', 'class' => 'badge bg-success'],
            self::STATUT_REFUSE => ['label' => 'Refusé', 'class' => 'badge bg-danger'],
            self::STATUT_ANNULE => ['label' => 'Annulé', 'class' => 'badge bg-secondary'],
        ];
        
        return $statuts[$this->statut] ?? ['label' => 'Inconnu', 'class' => 'badge bg-dark'];
    }
    
    /**
     * Vérifier si la demande est annulable
     */
    public function getEstAnnulableAttribute()
    {
        return in_array($this->statut, [
            self::STATUT_EN_ATTENTE,
            self::STATUT_EN_ETUDE
        ]);
    }
}