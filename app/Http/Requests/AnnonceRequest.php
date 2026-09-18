<?php

namespace App\Http\Requests;

use App\Models\Annonce;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AnnonceRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null && ($user->isProprietaire() || $user->isAdmin());
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'prix' => ['required', 'numeric', 'min:0'],
            'surface' => ['required', 'numeric', 'min:0'],
            'nombre_chambres' => ['nullable', 'integer', 'min:0'],
            'nombre_salles_bain' => ['nullable', 'integer', 'min:0'],
            'ville' => ['required', 'string', 'max:255'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'type_bien_id' => ['required', 'integer', 'exists:type_biens,id'],
            'photos' => ['nullable', 'array'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }
}
