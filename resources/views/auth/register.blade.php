<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - Foubadj</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Style personnalisé -->
    <style>
        body {
            background: linear-gradient(to right, #e8f5e9, #f1f8e9);
        }
        .form-container {
            background: rgba(255, 255, 255, 0.95);
        }
        .input-field {
            transition: all 0.3s ease;
        }
        .input-field:focus {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 py-12">

    <div class="w-full max-w-2xl form-container p-8 rounded-2xl shadow-2xl">
        <!-- En-tête -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-green-600 to-green-700 rounded-full mb-4">
                <i class="bi bi-person-plus-fill text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Rejoignez Foubadj</h1>
            <p class="text-gray-600">Créez votre compte en quelques secondes</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <!-- Grille 2 colonnes pour desktop -->
            <div class="grid md:grid-cols-2 gap-5">
                
                <!-- Nom complet -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="bi bi-person text-green-600"></i> Nom complet *
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           required
                           placeholder="Ex: Mouhamed Dieme"
                           class="input-field w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none"
                           value="{{ old('name') }}">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <i class="bi bi-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="bi bi-envelope text-green-600"></i> Adresse email *
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           required
                           placeholder="votre@email.com"
                           class="input-field w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none"
                           value="{{ old('email') }}">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <i class="bi bi-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Téléphone -->
                <div>
                    <label for="telephone" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="bi bi-telephone text-green-600"></i> Numéro de téléphone *
                    </label>
                    <input type="tel" 
                           id="telephone" 
                           name="telephone" 
                           required
                           placeholder="+221 77 123 45 67"
                           class="input-field w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none"
                           value="{{ old('telephone') }}">
                    @error('telephone')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <i class="bi bi-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Rôle -->
                <div>
                    <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="bi bi-briefcase text-green-600"></i> Je suis *
                    </label>
                    <select name="role" 
                            id="role" 
                            required
                            class="input-field w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        <option value="">-- Sélectionnez votre rôle --</option>
                        <option value="acheteur" {{ old('role') == 'acheteur' ? 'selected' : '' }}>
                            🛒 Acheteur
                        </option>
                        <option value="producteur" {{ old('role') == 'producteur' ? 'selected' : '' }}>
                            🌾 Producteur
                        </option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <i class="bi bi-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Adresse complète (pleine largeur) -->
            <div>
                <label for="adresse" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="bi bi-geo-alt text-green-600"></i> Adresse complète *
                </label>
                <textarea id="adresse" 
                          name="adresse" 
                          required
                          rows="2"
                          placeholder="Ex: Quartier Tivaouane Peulh, Dakar, Sénégal"
                          class="input-field w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none resize-none">{{ old('adresse') }}</textarea>
                @error('adresse')
                    <p class="text-red-500 text-sm mt-1 flex items-center">
                        <i class="bi bi-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Mots de passe -->
            <div class="grid md:grid-cols-2 gap-5">
                
                <!-- Mot de passe -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="bi bi-lock text-green-600"></i> Mot de passe *
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required
                           placeholder="••••••••"
                           class="input-field w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <i class="bi bi-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Minimum 8 caractères</p>
                </div>

                <!-- Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="bi bi-lock-fill text-green-600"></i> Confirmer le mot de passe *
                    </label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           required
                           placeholder="••••••••"
                           class="input-field w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    @error('password_confirmation')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <i class="bi bi-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Message d'information -->
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
                <div class="flex items-start">
                    <i class="bi bi-info-circle text-green-600 mr-2 mt-1"></i>
                    <div class="text-sm text-gray-700">
                        <p class="font-semibold mb-1">Vérification d'email requise</p>
                        <p class="text-xs text-gray-600">Après votre inscription, vous recevrez un email de vérification. Veuillez cliquer sur le lien pour activer votre compte.</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4">
                <a href="{{ route('login') }}" class="text-sm text-green-600 hover:text-green-800 font-medium flex items-center">
                    <i class="bi bi-arrow-left mr-1"></i> Déjà inscrit ? Se connecter
                </a>
                <button type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 border border-transparent rounded-lg font-semibold text-white hover:from-green-700 hover:to-green-800 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="bi bi-check-circle mr-2"></i>
                    Créer mon compte
                </button>
            </div>
        </form>

        <!-- Pied de page -->
        <div class="mt-8 pt-6 border-t border-gray-200 text-center">
            <p class="text-xs text-gray-500">
                En vous inscrivant, vous acceptez nos 
                <a href="#" class="text-green-600 hover:underline">Conditions d'utilisation</a> et notre 
                <a href="#" class="text-green-600 hover:underline">Politique de confidentialité</a>
            </p>
        </div>
    </div>

</body>
</html>