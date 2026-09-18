<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favori extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function annonce()
    {
        return $this->belongsTo(Annonce::class);
    }

    protected $fillable = [
        'user_id',
        'annonce_id',
        'date_ajout',
    ];

    protected function casts(): array
    {
        return [
            'date_ajout' => 'date',
        ];
    }
}
