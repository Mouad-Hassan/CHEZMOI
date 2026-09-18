<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';

    public const ROLE_PROPRIETAIRE = 'proprietaire';

    public const ROLE_ACHETEUR = 'acheteur';

    public function annonces()
    {
        return $this->hasMany(Annonce::class);
    }

    public function favoris()
    {
        return $this->hasMany(Favori::class);
    }

    public function messagesEnvoyes()
    {
        return $this->hasMany(Message::class, 'expediteur_id');
    }

    public function messagesRecus()
    {
        return $this->hasMany(Message::class, 'destinataire_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nom',
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isProprietaire(): bool
    {
        return $this->role === self::ROLE_PROPRIETAIRE;
    }

    public function isAcheteur(): bool
    {
        return $this->role === self::ROLE_ACHETEUR;
    }

    public function getNameAttribute(): string
    {
        return $this->attributes['nom'] ?? '';
    }

    public function setNameAttribute($value): void
    {
        $this->attributes['nom'] = $value;
    }

    /**
     * Libellé lisible du rôle (pour l'affichage).
     */
    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            self::ROLE_ADMIN => 'Administrateur',
            self::ROLE_PROPRIETAIRE => 'Vendeur / Propriétaire',
            default => 'Acheteur / Locataire',
        };
    }
}
