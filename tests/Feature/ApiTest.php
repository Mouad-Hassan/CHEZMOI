<?php

namespace Tests\Feature;

use App\Models\Annonce;
use App\Models\TypeBien;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_utilisateur_peut_s_inscrire_via_l_api_et_recoit_un_token(): void
    {
        $response = $this->postJson('/api/register', [
            'nom' => 'Sara Idrissi',
            'email' => 'sara@example.com',
            'password' => 'MotDePasse123',
            'password_confirmation' => 'MotDePasse123',
            'role' => User::ROLE_ACHETEUR,
        ]);

        $response->assertCreated()
            ->assertJsonStructure(['user' => ['id', 'nom', 'email', 'role'], 'token']);
    }

    public function test_l_acces_au_profil_sans_token_est_refuse(): void
    {
        $this->getJson('/api/me')->assertUnauthorized();
    }

    public function test_l_api_renvoie_du_json_meme_sans_en_tete_accept(): void
    {
        $this->get('/api/me')
            ->assertStatus(401)
            ->assertHeader('content-type', 'application/json');
    }

    public function test_un_utilisateur_authentifie_accede_a_son_profil(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/me')
            ->assertOk()
            ->assertJsonFragment(['email' => $user->email]);
    }

    public function test_la_liste_des_annonces_est_paginee(): void
    {
        $type = TypeBien::factory()->create();
        $proprietaire = User::factory()->proprietaire()->create();

        Annonce::factory()->count(3)->valide()->create([
            'user_id' => $proprietaire->id,
            'type_bien_id' => $type->id,
        ]);

        $this->getJson('/api/annonces?par_page=2')
            ->assertOk()
            ->assertJsonStructure(['data', 'current_page', 'total'])
            ->assertJsonPath('per_page', 2);
    }

    public function test_un_proprietaire_authentifie_cree_une_annonce_via_l_api(): void
    {
        $type = TypeBien::factory()->create();
        $proprietaire = User::factory()->proprietaire()->create();

        $this->actingAs($proprietaire, 'sanctum')->postJson('/api/annonces', [
            'titre' => 'Appartement API',
            'description' => 'Créé via API',
            'prix' => 500000,
            'surface' => 80,
            'ville' => 'Rabat',
            'type_bien_id' => $type->id,
        ])->assertCreated();

        $this->assertDatabaseHas('annonces', ['titre' => 'Appartement API']);
    }
}
