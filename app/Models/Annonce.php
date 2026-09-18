<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type_bien_id',
        'titre',
        'description',
        'prix',
        'surface',
        'nombre_chambres',
        'nombre_salles_bain',
        'ville',
        'adresse',
        'statut_validation',
        'date_publication',
        'vues',
    ];

    public const STATUT_EN_ATTENTE = 'en_attente';

    public const STATUT_VALIDE = 'valide';

    public const STATUT_REFUSE = 'refuse';

    protected function casts(): array
    {
        return [
            'prix' => 'decimal:2',
            'surface' => 'decimal:2',
            'date_publication' => 'date',
            'vues' => 'integer',
        ];
    }

    public function scopeValide($query)
    {
        return $query->where('statut_validation', self::STATUT_VALIDE);
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut_validation', self::STATUT_EN_ATTENTE);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function typeBien()
    {
        return $this->belongsTo(TypeBien::class);
    }

    public function favoris()
    {
        return $this->hasMany(Favori::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class); // ← corrigé (avant c'était hasmany)
    }

    /**
     * Chemin de la première photo (photo principale de l'annonce).
     */
    public function getPhotoPrincipaleAttribute(): ?string
    {
        return $this->photos->first()?->chemin;
    }
}
