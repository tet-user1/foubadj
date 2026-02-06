@extends('layouts.app')

@section('title', 'Mes Produits')

@section('header')
    <h2 class="text-xl font-semibold text-gray-800">Mes Produits</h2>
@endsection

@section('content')
    <div class="flex justify-end mb-6">
        <a href="#" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">➕ Ajouter un produit</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- Exemple de produits --}}
        @for ($i = 1; $i <= 4; $i++)
            <div class="bg-white p-4 rounded shadow hover:shadow-md transition">
                <img src="https://via.placeholder.com/300x180" class="rounded mb-3" alt="Produit {{ $i }}">
                <h3 class="text-lg font-semibold text-gray-800 mb-1">Produit {{ $i }}</h3>
                <p class="text-sm text-gray-500 mb-2">Catégorie: Fruits</p>
                <p class="text-green-600 font-bold mb-3">Prix: 2 000 F CFA</p>
                <div class="flex justify-between">
                    <button class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500">Modifier</button>
                    <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Supprimer</button>
                </div>
            </div>
        @endfor
    </div>
@endsection
