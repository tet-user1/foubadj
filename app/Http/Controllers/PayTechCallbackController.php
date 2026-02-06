<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Commande;
use App\Services\PayTechService;

class PayTechCallbackController extends Controller
{
    protected $paytechService;

    public function __construct(PayTechService $paytechService)
    {
        $this->paytechService = $paytechService;
    }

    /**
     * 🔹 Méthode appelée automatiquement par PayTech (IPN)
     */
    public function handle(Request $request)
    {
        Log::info('📩 [PayTech Callback reçu]', $request->all());

        // Vérification de la signature
        $signature = $request->header('Paytech-Signature');
        if (!$this->paytechService->validateSignature($request->all(), $signature)) {
            Log::warning('❌ Signature PayTech invalide.');
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $data = $request->all();
        $custom = json_decode($data['custom_field'] ?? '{}', true);

        if (empty($custom['commande_id'])) {
            Log::error('❌ Données manquantes dans le callback PayTech');
            return response()->json(['error' => 'Missing commande_id'], 400);
        }

        $commande = Commande::find($custom['commande_id']);
        if (!$commande) {
            Log::error("❌ Commande introuvable : {$custom['commande_id']}");
            return response()->json(['error' => 'Commande not found'], 404);
        }

        // Vérifier le statut du paiement
        if ($data['status'] === 'completed' || $data['status'] === 'success') {
            $commande->update(['status' => 'payée']);
            Log::info("✅ Commande #{$commande->id} marquée comme payée.");
        } else {
            $commande->update(['status' => 'échouée']);
            Log::warning("⚠️ Commande #{$commande->id} marquée comme échouée.");
        }

        return response()->json(['success' => true]);
    }
}
