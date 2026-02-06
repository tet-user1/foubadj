@extends('layouts.app')

@section('title', 'Paiement Annulé - FUBAD')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-times-circle fa-5x text-danger"></i>
                    </div>
                    
                    <h2 class="text-danger mb-3">Paiement Annulé</h2>
                    
                    <p class="text-muted mb-4">
                        Vous avez annulé le processus de paiement. Aucun montant n'a été débité.
                    </p>

                    @if($reference)
                    <div class="alert alert-warning text-start">
                        <strong>Référence:</strong> {{ $reference }}
                    </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('panier') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-shopping-cart me-2"></i>Retour au panier
                        </a>
                        <a href="{{ route('accueil') }}" class="btn btn-outline-secondary btn-lg ms-2">
                            <i class="fas fa-home me-2"></i>Retour à l'accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection