@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Détails de l'utilisateur</h1>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">← Retour</a>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informations personnelles</h5>
                            <table class="table">
                                <tr>
                                    <th>ID</th>
                                    <td>{{ $user->id }}</td>
                                </tr>
                                <tr>
                                    <th>Nom</th>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Téléphone</th>
                                    <td>{{ $user->telephone ?? 'Non renseigné' }}</td>
                                </tr>
                                <tr>
                                    <th>Adresse</th>
                                    <td>{{ $user->adresse ?? 'Non renseignée' }}</td>
                                </tr>
                                <tr>
                                    <th>Rôle</th>
                                    <td>
                                        <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'producteur' ? 'success' : 'primary') }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Statut</th>
                                    <td>
                                        @if($user->is_active ?? true)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-danger">Inactif</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Email vérifié</th>
                                    <td>
                                        @if($user->email_verified_at)
                                            <span class="badge bg-success">Oui ({{ $user->email_verified_at->format('d/m/Y H:i') }})</span>
                                        @else
                                            <span class="badge bg-warning">Non</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Inscrit le</th>
                                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Dernière mise à jour</th>
                                    <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            @if($user->role === 'producteur')
                                <h5>Produits</h5>
                                <p class="text-muted">{{ $user->produits->count() }} produit(s)</p>
                                @if($user->produits->count() > 0)
                                    <ul class="list-group">
                                        @foreach($user->produits as $produit)
                                            <li class="list-group-item">
                                                {{ $produit->nom }} - {{ $produit->prix }} FCFA
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            @endif

                            @if($user->role === 'acheteur')
                                <h5>Commandes</h5>
                                <p class="text-muted">{{ $user->commandes->count() }} commande(s)</p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">← Retour à la liste</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection