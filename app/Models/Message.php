<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    public function expediteur()
    {
        return $this->belongsTo(User::class, 'expediteur_id');
    }

    public function destinataire()
    {
        return $this->belongsTo(User::class, 'destinataire_id');
    }

    public function annonce()
    {
        return $this->belongsTo(Annonce::class);
    }

    protected $fillable = [
        'expediteur_id',
        'destinataire_id',
        'annonce_id',
        'contenu',
        'statut_lecture',
        'date_envoi',
    ];

    protected function casts(): array
    {
        return [
            'statut_lecture' => 'boolean',
            'date_envoi' => 'datetime',
        ];
    }
}
