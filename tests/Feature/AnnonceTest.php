<?php

namespace Tests\Feature;

use App\Models\Annonce;
use App\Models\TypeBien;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnnonceTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_page_d_accueil_affiche_les_annonces_validees(): void
    {
        $type = TypeBien::factory()->create(['type' => 'Appartement']);
        $proprietaire = User::factory()->proprietaire()->create();

        Annonce::factory()->create([
            'user_id' => $proprietaire->id,
            'type_bien_id' => $type->id,
            'titre' => 'Appartement visible',
            'statut_validation' => Annonce::STATUT_VALIDE,
        ]);

        Annonce::factory()->create([
            'user_id' => $proprietaire->id,
            'type_bien_id' => $type->id,
            'titre' => 'Annonce en attente',
            'statut_validation' => Annonce::STATUT_EN_ATTENTE,
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Appartement visible');
        $response->assertDontSee('Annonce en attente');
    }

    public function test_la_recherche_avancee_filtre_par_ville_et_prix(): void
    {
        $type = TypeBien::factory()->create();
        $proprietaire = User::factory()->proprietaire()->create();

        Annonce::factory()->valide()->create([
            'user_id' => $proprietaire->id,
            'type_bien_id' => $type->id,
            'titre' => 'Bien Marrakech cher',
            'ville' => 'Marrakech',
            'prix' => 900000,
        ]);

        Annonce::factory()->valide()->create([
            'user_id' => $proprietaire->id,
            'type_bien_id' => $type->id,
            'titre' => 'Bien Rabat abordable',
            'ville' => 'Rabat',
            'prix' => 200000,
        ]);

        $response = $this->get('/?ville=Rabat&prix_max=500000');

        $response->assertOk();
        $response->assertSee('Bien Rabat abordable');
        $response->assertDontSee('Bien Marrakech cher');
    }

    public function test_un_proprietaire_peut_creer_une_annonce_en_attente_de_validation(): void
    {
        $type = TypeBien::factory()->create();
        $proprietaire = User::factory()->proprietaire()->create();

        $response = $this->actingAs($proprietaire)->post('/annonces', [
            'titre' => 'Nouvelle villa',
            'description' => 'Villa avec piscine',
            'prix' => 1500000,
            'surface' => 250,
            'nombre_chambres' => 4,
            'nombre_salles_bain' => 2,
            'ville' => 'Marrakech',
            'adresse' => 'Route de Fès',
            'type_bien_id' => $type->id,
        ]);

        $response->assertRedirect(route('annonces.index'));

        $this->assertDatabaseHas('annonces', [
            'titre' => 'Nouvelle villa',
            'user_id' => $proprietaire->id,
            'statut_validation' => Annonce::STATUT_EN_ATTENTE,
        ]);
    }

    public function test_un_acheteur_ne_peut_pas_creer_une_annonce(): void
    {
        $acheteur = User::factory()->acheteur()->create();

        $this->actingAs($acheteur)->get(route('annonces.create'))->assertForbidden();
    }

    public function test_un_administrateur_modere_les_annonces_sans_pouvoir_en_publier(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('annonces.create'))->assertForbidden();
    }

    public function test_un_proprietaire_ne_peut_pas_modifier_l_annonce_d_un_autre(): void
    {
        $type = TypeBien::factory()->create();
        $proprietaire = User::factory()->proprietaire()->create();
        $autre = User::factory()->proprietaire()->create();

        $annonce = Annonce::factory()->create([
            'user_id' => $autre->id,
            'type_bien_id' => $type->id,
        ]);

        $this->actingAs($proprietaire)
            ->get(route('annonces.edit', $annonce))
            ->assertForbidden();
    }

    public function test_la_consultation_d_une_annonce_incremente_le_compteur_de_vues(): void
    {
        $type = TypeBien::factory()->create();
        $proprietaire = User::factory()->proprietaire()->create();
        $visiteur = User::factory()->acheteur()->create();

        $annonce = Annonce::factory()->valide()->create([
            'user_id' => $proprietaire->id,
            'type_bien_id' => $type->id,
        ]);

        $this->actingAs($visiteur)->get(route('annonces.show', $annonce))->assertOk();

        $this->assertSame(1, $annonce->fresh()->vues);
    }
}
