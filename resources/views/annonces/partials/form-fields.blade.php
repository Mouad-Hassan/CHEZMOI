@php $annonce = $annonce ?? null; @endphp

<div class="space-y-6">
    <div>
        <label for="titre" class="block text-sm font-semibold text-charcoal mb-1">Titre de l'annonce <span class="text-red-500">*</span></label>
        <input id="titre" name="titre" type="text" required value="{{ old('titre', $annonce?->titre) }}"
               placeholder="Ex. Appartement lumineux au centre-ville"
               class="brand-input">
        @error('titre')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-semibold text-charcoal mb-1">Description <span class="text-red-500">*</span></label>
        <textarea id="description" name="description" rows="5" required
                  placeholder="Décrivez le bien, ses équipements et son environnement..."
                  class="brand-input">{{ old('description', $annonce?->description) }}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label for="type_bien_id" class="block text-sm font-semibold text-charcoal mb-1">Type de bien <span class="text-red-500">*</span></label>
            <select id="type_bien_id" name="type_bien_id" required class="brand-input">
                <option value="">— Choisir un type —</option>
                @foreach ($types as $type)
                    <option value="{{ $type->id }}" @selected((string) old('type_bien_id', $annonce?->type_bien_id) === (string) $type->id)>
                        {{ $type->type }}
                    </option>
                @endforeach
            </select>
            @error('type_bien_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>


    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label for="prix" class="block text-sm font-semibold text-charcoal mb-1">Prix (DH) <span class="text-red-500">*</span></label>
            <input id="prix" name="prix" type="number" step="0.01" min="0" required
                   value="{{ old('prix', $annonce?->prix) }}"
                   placeholder="Ex. 850000"
                   class="brand-input">
            @error('prix')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="surface" class="block text-sm font-semibold text-charcoal mb-1">Surface (m²) <span class="text-red-500">*</span></label>
            <input id="surface" name="surface" type="number" step="0.01" min="0" required
                   value="{{ old('surface', $annonce?->surface) }}"
                   placeholder="Ex. 85"
                   class="brand-input">
            @error('surface')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="nombre_chambres" class="block text-sm font-semibold text-charcoal mb-1">Nombre de chambres</label>
            <input id="nombre_chambres" name="nombre_chambres" type="number" min="0"
                   value="{{ old('nombre_chambres', $annonce?->nombre_chambres) }}"
                   placeholder="Ex. 3"
                   class="brand-input">
            @error('nombre_chambres')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="nombre_salles_bain" class="block text-sm font-semibold text-charcoal mb-1">Nombre de salles de bain</label>
            <input id="nombre_salles_bain" name="nombre_salles_bain" type="number" min="0"
                   value="{{ old('nombre_salles_bain', $annonce?->nombre_salles_bain) }}"
                   placeholder="Ex. 2"
                   class="brand-input">
            @error('nombre_salles_bain')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label for="ville" class="block text-sm font-semibold text-charcoal mb-1">Ville <span class="text-red-500">*</span></label>
            <input id="ville" name="ville" type="text" required value="{{ old('ville', $annonce?->ville) }}"
                   placeholder="Ex. Marrakech"
                   class="brand-input">
            @error('ville')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="adresse" class="block text-sm font-semibold text-charcoal mb-1">Adresse</label>
            <input id="adresse" name="adresse" type="text" value="{{ old('adresse', $annonce?->adresse) }}"
                   placeholder="Quartier, rue, résidence..."
                   class="brand-input">
            @error('adresse')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="photos" class="block text-sm font-semibold text-charcoal mb-1">Photos du bien</label>
        <input id="photos" name="photos[]" type="file" multiple accept="image/jpeg,image/png,image/webp"
               class="w-full rounded-xl border border-beige-100 bg-beige-50 px-4 py-3 text-sm text-charcoal file:mr-4 file:rounded-xl file:border-0 file:bg-beige-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gold hover:file:bg-beige-200 transition">
        <p class="mt-1 text-xs text-charcoal/50">Formats acceptés : JPG, PNG ou WEBP. Taille maximale : 4 Mo par photo.</p>
        @error('photos')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
        @error('photos.*')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>
