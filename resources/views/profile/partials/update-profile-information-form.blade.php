<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Modifier les informations du profil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Mettez à jour les informations de votre compte.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="name" class="form-label">{{ __('Nom complet') }}</label>
            <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            @error('name')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Adresse email') }}</label>
            <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="email" />
            @error('email')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="telephone" class="form-label">{{ __('Téléphone') }}</label>
            <input id="telephone" name="telephone" type="text" class="form-control" value="{{ old('telephone', $user->telephone) }}" autocomplete="tel" placeholder="Ex: +221 77 123 45 67" />
            @error('telephone')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="adresse" class="form-label">{{ __('Adresse') }}</label>
            <textarea id="adresse" name="adresse" class="form-control" rows="3" autocomplete="street-address" placeholder="Votre adresse complète">{{ old('adresse', $user->adresse) }}</textarea>
            @error('adresse')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center gap-4">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save me-2"></i>{{ __('Enregistrer les modifications') }}
            </button>

            <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                <i class="fas fa-times me-2"></i>{{ __('Annuler') }}
            </a>

            @if (session('status') === 'profile-updated')
                <div class="alert alert-success mb-0">
                    <i class="fas fa-check me-2"></i>{{ __('Profil mis à jour avec succès !') }}
                </div>
            @endif
        </div>
    </form>
</section>