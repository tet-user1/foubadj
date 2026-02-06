<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\DemandeFinancement;

class FinancementController extends Controller
{
    /**
     * Page d'accueil du financement
     */
    public function index()
    {
        $demandesEnCours = collect();
        $demandesHistorique = collect();
        
        if (Auth::check()) {
            $user = Auth::user();
            
            // Utiliser la méthode isAdmin()
            if (!$user->isAdmin()) {
                try {
                    $demandesEnCours = DemandeFinancement::where('user_id', $user->id)
                        ->whereIn('statut', ['en_attente', 'en_etude', 'en_traitement'])
                        ->orderBy('created_at', 'desc')
                        ->get();
                        
                    $demandesHistorique = DemandeFinancement::where('user_id', $user->id)
                        ->whereIn('statut', ['approuve', 'refuse', 'annule'])
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();
                } catch (\Exception $e) {
                    // Table non existante, on continue avec des collections vides
                }
            }
        }
        
        return view('financement.index', compact('demandesEnCours', 'demandesHistorique'));
    }
    
    /**
     * Afficher le formulaire de demande
     */
    public function demande()
    {
        $user = Auth::user();
        
        // Utiliser la méthode isAdmin()
        if ($user->isAdmin()) {
            return redirect()->route('financement.index')
                ->with('error', 'Les administrateurs ne peuvent pas soumettre de demandes.');
        }
        
        return view('financement.demande');
    }
    
    /**
     * Ancienne méthode create (pour compatibilité)
     */
    public function create()
    {
        // Rediriger vers la nouvelle vue
        return redirect()->route('financement.demande');
    }
    
    /**
     * Stocker une nouvelle demande
     */
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            // Informations personnelles
            'prenom' => 'required|string|max:100',
            'nom' => 'required|string|max:100',
            'email' => 'required|email',
            'telephone' => 'required|string|max:20',
            'cni' => 'required|string|max:20',
            'ville' => 'required|string|max:100',
            'adresse' => 'nullable|string|max:255',
            
            // Informations projet
            'titre_projet' => 'required|string|max:255',
            'montant_demande' => 'required|numeric|min:500000|max:10000000',
            'duree_remboursement' => 'required|in:6,12,18,24,36',
            'description_projet' => 'required|string|min:100',
            'utilisation_fonds' => 'required|string',
            
            // Documents obligatoires
            'piece_identite' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'certificat_residence' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            
            // Documents optionnels
            'releve_bancaire' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'plan_affaires' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'contrats' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            
            // Conditions
            'conditions' => 'required|accepted',
            'consentement' => 'nullable|boolean',
        ]);
        
        // Générer un numéro de dossier unique
        $numeroDossier = 'FIN-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -8));
        
        // Traitement des fichiers
        $pieceIdentitePath = $this->storeDocument($request->file('piece_identite'), 'piece_identite');
        $certificatResidencePath = $this->storeDocument($request->file('certificat_residence'), 'certificat_residence');
        
        $releveBancairePath = null;
        $planAffairesPath = null;
        $contratsPath = null;
        
        if ($request->hasFile('releve_bancaire')) {
            $releveBancairePath = $this->storeDocument($request->file('releve_bancaire'), 'releve_bancaire');
        }
        
        if ($request->hasFile('plan_affaires')) {
            $planAffairesPath = $this->storeDocument($request->file('plan_affaires'), 'plan_affaires');
        }
        
        if ($request->hasFile('contrats')) {
            $contratsPath = $this->storeDocument($request->file('contrats'), 'contrats');
        }
        
        // Déterminer le type d'utilisateur
        $user = Auth::user();
        $typeUtilisateur = 'acheteur'; // Par défaut
        if ($user->isProducteur()) {
            $typeUtilisateur = 'producteur';
        }
        
        // Créer la demande
        $demande = DemandeFinancement::create([
            'user_id' => $user->id,
            
            // Informations personnelles
            'prenom' => $validated['prenom'],
            'nom' => $validated['nom'],
            'nom_complet' => $validated['prenom'] . ' ' . $validated['nom'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'],
            'cni' => $validated['cni'],
            'ville' => $validated['ville'],
            'adresse' => $validated['adresse'],
            'type_utilisateur' => $typeUtilisateur,
            
            // Informations projet
            'titre_projet' => $validated['titre_projet'],
            'montant_demande' => $validated['montant_demande'],
            'duree_remboursement' => $validated['duree_remboursement'],
            'description_projet' => $validated['description_projet'],
            'utilisation_fonds' => $validated['utilisation_fonds'],
            
            // Documents
            'piece_identite_path' => $pieceIdentitePath,
            'certificat_residence_path' => $certificatResidencePath,
            'releve_bancaire_path' => $releveBancairePath,
            'plan_affaires_path' => $planAffairesPath,
            'contrats_path' => $contratsPath,
            
            // Structure financière (Orange Bank par défaut)
            'structure_financiere' => 'orange_bank',
            
            // Suivi
            'numero_dossier' => $numeroDossier,
            'statut' => 'en_attente',
            'consentement_partage' => $request->has('consentement'),
            
            // Dates
            'date_demande' => now(),
        ]);
        
        // Redirection avec message de succès
        return redirect()->route('financement.index')
            ->with('success', 'Votre demande de financement a été soumise avec succès ! Numéro de dossier : ' . $demande->numero_dossier);
    }
    
    /**
     * Stocker un document
     */
    private function storeDocument($file, $type)
    {
        if (!$file) {
            return null;
        }
        
        $userId = Auth::id();
        $date = date('Y-m-d');
        $extension = $file->getClientOriginalExtension();
        $filename = $type . '_' . time() . '_' . uniqid() . '.' . $extension;
        
        // Chemin : financement/{user_id}/{date}/{type}/{filename}
        $path = $file->storeAs(
            "financement/{$userId}/{$date}/{$type}",
            $filename,
            'public'
        );
        
        return $path;
    }
    
    /**
     * Afficher une demande spécifique
     */
    public function show($id)
    {
        try {
            $demande = DemandeFinancement::where('user_id', Auth::id())
                ->findOrFail($id);
                
            return view('financement.show', compact('demande'));
        } catch (\Exception $e) {
            return redirect()->route('financement.index')
                ->with('error', 'Demande non trouvée.');
        }
    }
    
    /**
     * Historique des demandes
     */
    public function historique()
    {
        try {
            $demandes = DemandeFinancement::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->paginate(10);
                
            return view('financement.historique', compact('demandes'));
        } catch (\Exception $e) {
            return redirect()->route('financement.index')
                ->with('error', 'Impossible d\'accéder à l\'historique.');
        }
    }
    
    /**
     * Annuler une demande
     */
    public function annuler($id)
    {
        try {
            $demande = DemandeFinancement::where('user_id', Auth::id())
                ->where('statut', 'en_attente')
                ->findOrFail($id);
                
            $demande->update([
                'statut' => 'annule',
                'date_annulation' => now(),
            ]);
            
            return redirect()->route('financement.historique')
                ->with('success', 'La demande a été annulée avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('financement.index')
                ->with('error', 'Impossible d\'annuler cette demande.');
        }
    }
}