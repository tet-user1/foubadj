<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vérification Email - Foubadj</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(to right, #e8f5e9, #f1f8e9);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-2xl">
        <!-- Icône -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-yellow-100 rounded-full mb-4">
                <i class="bi bi-envelope-check text-yellow-600 text-4xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Vérifiez votre email</h1>
            <p class="text-gray-600 text-sm">
                Merci de vous être inscrit ! Avant de commencer, veuillez vérifier votre adresse email en cliquant sur le lien que nous venons de vous envoyer.
            </p>
        </div>

        <!-- Message de succès -->
        @if (session('message'))
            <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 rounded">
                <div class="flex items-center">
                    <i class="bi bi-check-circle text-green-600 mr-2"></i>
                    <p class="text-sm text-green-700">{{ session('message') }}</p>
                </div>
            </div>
        @endif

        <!-- Informations -->
        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
            <div class="flex items-start">
                <i class="bi bi-info-circle text-blue-600 mr-2 mt-0.5"></i>
                <div class="text-sm text-gray-700">
                    <p class="font-semibold mb-1">Email envoyé à :</p>
                    <p class="text-blue-600">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>

        <!-- Formulaire de renvoi -->
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" 
                    class="w-full py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg font-semibold hover:from-green-700 hover:to-green-800 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center">
                <i class="bi bi-arrow-repeat mr-2"></i>
                Renvoyer l'email de vérification
            </button>
        </form>

        <!-- Déconnexion -->
        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button type="submit" class="w-full text-sm text-gray-600 hover:text-gray-800 py-2">
                <i class="bi bi-box-arrow-right mr-1"></i>
                Se déconnecter
            </button>
        </form>
    </div>

</body>
</html>