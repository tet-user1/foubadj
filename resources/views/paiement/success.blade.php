@extends('layouts.app')

@section('title', 'Paiement Réussi - FUBAD')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle fa-5x text-success"></i>
                    </div>
                    
                    <h2 class="text-success mb-3">Paiement Réussi !</h2>
                    
                    <p class="text-muted mb-4">
                        Votre commande a été traitée avec succès. Vous recevrez un email de confirmation sous peu.
                    </p>

                    <div class="alert alert-info text-start">
                        <strong>Référence:</strong> {{ $reference }}<br>
                        @if($transaction_id)
                        <strong>Transaction ID:</strong> {{ $transaction_id }}<br>
                        @endif
                        @if($amount)
                        <strong>Montant:</strong> {{ number_format($amount, 0, ',', ' ') }} FCFA
                        @endif
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('accueil') }}" class="btn btn-success btn-lg">
                            <i class="fas fa-home me-2"></i>Retour à l'accueil
                        </a>
                        <a href="{{ route('commandes') }}" class="btn btn-outline-primary btn-lg ms-2">
                            <i class="fas fa-list me-2"></i>Voir mes commandes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Vider le panier après paiement réussi
localStorage.removeItem('panier');
</script>
@endsection