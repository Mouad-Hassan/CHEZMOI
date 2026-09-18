<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeBien extends Model
{
    use HasFactory;

    /**
     * Types de biens imposés par le cahier des charges.
     */
    public const APPARTEMENT = 'Appartement';

    public const MAISON = 'Maison';

    public const VILLA = 'Villa';

    protected $fillable = [
        'type',
    ];

    public function annonces()
    {
        return $this->hasMany(Annonce::class);
    }
}
