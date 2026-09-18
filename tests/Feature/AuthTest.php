<?php

namespace Tests\Feature;

use App\Models\Annonce;
use App\Models\TypeBien;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_utilisateur_peut_s_inscrire_avec_confirmation_du_mot_de_passe(): void
    {
        $response = $this->post('/register', [
            'nom' => 'Yassine Alami',
            'email' => 'yassine@example.com',
            'password' => 'MotDePasse123',
            'password_confirmation' => 'MotDePasse123',
            'role' => User::ROLE_PROPRIETAIRE,
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'yassine@example.com',
            'role' => User::ROLE_PROPRIETAIRE,
        ]);
    }

    public function test_l_inscription_echoue_si_la_confirmation_du_mot_de_passe_differe(): void
    {
        $this->post('/register', [
            'nom' => 'Yassine Alami',
            'email' => 'yassine@example.com',
            'password' => 'MotDePasse123',
            'password_confirmation' => 'AutreMotDePasse',
            'role' => User::ROLE_ACHETEUR,
        ])->assertSessionHasErrors('password');

        $this->assertGuest();
    }

    public function test_l_inscription_publique_ne_permet_pas_de_creer_un_administrateur(): void
    {
        $this->post('/register', [
            'nom' => 'Compte malveillant',
            'email' => 'admin-illicite@example.com',
            'password' => 'MotDePasse123',
            'password_confirmation' => 'MotDePasse123',
            'role' => User::ROLE_ADMIN,
        ])->assertSessionHasErrors('role');

        $this->assertDatabaseMissing('users', ['email' => 'admin-illicite@example.com']);
    }

    public function test_un_acheteur_est_redirige_vers_son_espace(): void
    {
        $user = User::factory()->acheteur()->create(['password' => 'MotDePasse123']);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'MotDePasse123',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
        $this->get(route('dashboard'))->assertSee('Acheteur / Locataire');
    }

    public function test_un_proprietaire_est_redirige_vers_son_espace(): void
    {
        $user = User::factory()->proprietaire()->create(['password' => 'MotDePasse123']);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'MotDePasse123',
        ])->assertRedirect(route('dashboard'));

        $this->get(route('dashboard'))->assertSee('Vendeur / Propriétaire');
    }

    public function test_un_admin_est_redirige_vers_l_administration(): void
    {
        $user = User::factory()->admin()->create(['password' => 'MotDePasse123']);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'MotDePasse123',
        ])->assertRedirect(route('admin.dashboard'));

        $this->get(route('admin.dashboard'))->assertSee('Administrateur');
    }

    public function test_les_identifiants_invalides_sont_rejetes(): void
    {
        $user = User::factory()->create(['password' => 'MotDePasse123']);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'mauvais-mot-de-passe',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_un_administrateur_accede_a_son_espace_d_administration(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
    }

    public function test_un_acheteur_ne_peut_pas_acceder_a_l_administration(): void
    {
        $acheteur = User::factory()->acheteur()->create();

        $this->actingAs($acheteur)->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_un_acheteur_ne_peut_pas_acceder_a_la_gestion_des_annonces(): void
    {
        $acheteur = User::factory()->acheteur()->create();

        $this->actingAs($acheteur)->get(route('annonces.index'))->assertForbidden();
        $this->actingAs($acheteur)->get(route('annonces.create'))->assertForbidden();
    }

    public function test_un_proprietaire_ne_peut_pas_acceder_aux_favoris(): void
    {
        $proprietaire = User::factory()->proprietaire()->create();

        $this->actingAs($proprietaire)->get(route('favoris.index'))->assertForbidden();
    }

    public function test_le_tableau_de_bord_du_proprietaire_affiche_ses_statistiques(): void
    {
        $type = TypeBien::factory()->create();
        $proprietaire = User::factory()->proprietaire()->create();

        Annonce::factory()->valide()->create([
            'user_id' => $proprietaire->id,
            'type_bien_id' => $type->id,
        ]);

        $this->actingAs($proprietaire)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Annonces publiées');
    }
}
