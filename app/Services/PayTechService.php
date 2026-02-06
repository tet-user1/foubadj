<?php
// app/Services/PayTechService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayTechService
{
    private $apiKey;
    private $secretKey;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.paytech.api_key', 'test_api_key');
        $this->secretKey = config('services.paytech.secret_key', 'test_api_secret');
        $this->baseUrl = config('services.paytech.base_url', 'https://paytech.sn');
    }

    /**
     * 🔹 Initier un paiement PayTech (version simplifiée pour test)
     */
    public function initiatePayment(array $data)
    {
        Log::info('🚀 PayTechService: Initiation du paiement', $data);

        // Pour le TEST, on utilise l'URL directe sans appeler l'API
        // Une fois en production, vous utiliserez l'API avec les vraies clés

        try {
            // URL directe vers la page de checkout PayTech (solution de test)
            $paytechParams = [
                'item_name' => $data['item_name'] ?? 'Commande',
                'item_price' => intval($data['amount'] ?? 0),
                'currency' => 'XOF',
                'ref_command' => $data['reference'] ?? 'CMD_' . time(),
                'command_name' => $data['description'] ?? 'Commande FUBAD',
                'env' => 'test', // 'prod' pour la production
                'ipn_url' => url('/webhook/paytech'),
                'success_url' => url('/paiement/success'),
                'cancel_url' => url('/paiement/cancel'),
                'custom_field' => json_encode([
                    'commande_id' => $data['commande_id'] ?? 0,
                    'user_id' => $data['user_id'] ?? 0,
                    'panier' => $data['panier'] ?? []
                ])
            ];

            $redirectUrl = $this->baseUrl . '/payment/checkout/eey3kpmgz22n1i?' . http_build_query($paytechParams);

            Log::info('🔗 Redirection PayTech générée', ['url' => $redirectUrl]);

            return [
                'success' => 1,
                'redirect_url' => $redirectUrl,
                'message' => 'Redirection vers PayTech'
            ];

        } catch (\Exception $e) {
            Log::error('❌ Erreur PayTechService: ' . $e->getMessage());
            return [
                'success' => 0,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * 🔹 Version API (pour la production)
     */
    public function initiatePaymentAPI(array $data)
    {
        $payload = [
            'item_name'      => $data['item_name'],
            'item_price'     => intval($data['amount']),
            'currency'       => 'XOF',
            'ref_command'    => $data['reference'],
            'command_name'   => $data['description'] ?? 'Commande FUBAD',
            'env'            => app()->environment('production') ? 'prod' : 'test',
            'ipn_url'        => url('/webhook/paytech'),
            'success_url'    => url('/paiement/success'),
            'cancel_url'     => url('/paiement/cancel'),
            'custom_field'   => json_encode([
                'commande_id' => $data['commande_id'],
                'user_id'     => $data['user_id'],
                'panier'      => $data['panier'] ?? []
            ]),
        ];

        Log::info('🔗 Requête API PayTech envoyée', $payload);

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'API_KEY'       => $this->apiKey,
                    'API_SECRET'    => $this->secretKey,
                    'Content-Type'  => 'application/x-www-form-urlencoded',
                ])
                ->asForm()
                ->post($this->baseUrl . '/api/payment/request-payment', $payload);

            $responseData = $response->json();

            Log::info('✅ Réponse API PayTech reçue', [
                'status'   => $response->status(),
                'response' => $responseData,
            ]);

            if (isset($responseData['success']) && $responseData['success'] == 1 && isset($responseData['redirect_url'])) {
                return [
                    'success'       => 1,
                    'redirect_url'  => $responseData['redirect_url'],
                    'token'         => $responseData['token'] ?? null,
                ];
            }

            return [
                'success' => 0,
                'error'   => $responseData['message'] ?? $responseData['errors'] ?? 'Erreur inconnue PayTech',
            ];

        } catch (\Exception $e) {
            Log::error('❌ Erreur API PayTech: ' . $e->getMessage());
            return [
                'success' => 0,
                'error'   => $e->getMessage(),
            ];
        }
    }

    /**
     * 🔹 Vérifier le statut d'un paiement PayTech
     */
    public function checkPaymentStatus(string $token)
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'API_KEY'    => $this->apiKey,
                    'API_SECRET' => $this->secretKey,
                ])
                ->get($this->baseUrl . '/api/payment/status', [
                    'token' => $token,
                ]);

            return $response->json();

        } catch (\Exception $e) {
            Log::error('❌ Erreur de vérification PayTech: ' . $e->getMessage());
            return [
                'success' => 0,
                'error'   => $e->getMessage(),
            ];
        }
    }

    /**
     * 🔹 Vérifier la signature PayTech
     */
    public function validateSignature($data, $signature)
    {
        $computedSignature = hash_hmac('sha256', json_encode($data), $this->secretKey);
        return hash_equals($computedSignature, $signature);
    }
}