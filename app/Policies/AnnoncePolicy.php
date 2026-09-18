<?php

namespace App\Policies;

use App\Models\Annonce;
use App\Models\User;

/**
 * Autorisations sur les annonces (cahier des charges 4).
 * Un vendeur ne peut modifier/supprimer que ses propres annonces.
 * Un administrateur peut tout (voir before()).
 */
class AnnoncePolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function view(User $user, Annonce $annonce): bool
    {
        return $annonce->user_id === $user->id;
    }

    public function update(User $user, Annonce $annonce): bool
    {
        return $annonce->user_id === $user->id;
    }

    public function delete(User $user, Annonce $annonce): bool
    {
        return $annonce->user_id === $user->id;
    }
}
