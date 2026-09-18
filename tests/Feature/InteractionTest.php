<?php

namespace Tests\Feature;

use App\Models\Annonce;
use App\Models\TypeBien;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InteractionTest extends TestCase
{
    use RefreshDatabase;

    private function creerAnnonceValidee(): Annonce
    {
        return Annonce::factory()->valide()->create([
            'user_id' => User::factory()->proprietaire()->create()->id,
            'type_bien_id' => TypeBien::factory()->create()->id,
        ]);
    }

    public function test_un_utilisateur_peut_ajouter_et_retirer_un_favori(): void
    {
        $annonce = $this->creerAnnonceValidee();
        $acheteur = User::factory()->acheteur()->create();

        // Ajout
        $this->actingAs($acheteur)
            ->post(route('favoris.toggle', $annonce))
            ->assertRedirect();

        $this->assertDatabaseHas('favoris', [
            'user_id' => $acheteur->id,
            'annonce_id' => $annonce->id,
        ]);

        // Retrait (toggle)
        $this->actingAs($acheteur)
            ->post(route('favoris.toggle', $annonce))
            ->assertRedirect();

        $this->assertDatabaseMissing('favoris', [
            'user_id' => $acheteur->id,
            'annonce_id' => $annonce->id,
        ]);
    }

    public function test_la_liste_des_favoris_est_accessible(): void
    {
        $annonce = $this->creerAnnonceValidee();
        $acheteur = User::factory()->acheteur()->create();

        $acheteur->favoris()->create([
            'annonce_id' => $annonce->id,
            'date_ajout' => now()->toDateString(),
        ]);

        $this->actingAs($acheteur)
            ->get(route('favoris.index'))
            ->assertOk()
            ->assertSee($annonce->titre);
    }

    public function test_l_envoi_d_un_message_genere_une_notification_pour_le_destinataire(): void
    {
        $annonce = $this->creerAnnonceValidee();
        $expediteur = User::factory()->acheteur()->create();

        $this->actingAs($expediteur)->post(route('messages.store'), [
            'destinataire_id' => $annonce->user_id,
            'annonce_id' => $annonce->id,
            'contenu' => 'Bonjour, le bien est-il toujours disponible ?',
        ])->assertRedirect();

        $this->assertDatabaseHas('messages', [
            'expediteur_id' => $expediteur->id,
            'destinataire_id' => $annonce->user_id,
            'contenu' => 'Bonjour, le bien est-il toujours disponible ?',
        ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $annonce->user_id,
            'type' => 'nouveau_message',
        ]);
    }

    public function test_un_utilisateur_ne_peut_pas_s_envoyer_un_message_a_lui_meme(): void
    {
        $user = User::factory()->acheteur()->create();

        $this->actingAs($user)->post(route('messages.store'), [
            'destinataire_id' => $user->id,
            'contenu' => 'Test',
        ])->assertForbidden();
    }

    public function test_l_administrateur_valide_une_annonce_et_notifie_le_proprietaire(): void
    {
        $admin = User::factory()->admin()->create();
        $proprietaire = User::factory()->proprietaire()->create();

        $annonce = Annonce::factory()->create([
            'user_id' => $proprietaire->id,
            'type_bien_id' => TypeBien::factory()->create()->id,
            'statut_validation' => Annonce::STATUT_EN_ATTENTE,
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.annonces.valider', $annonce))
            ->assertRedirect();

        $this->assertSame(Annonce::STATUT_VALIDE, $annonce->fresh()->statut_validation);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $proprietaire->id,
            'type' => 'annonce_validee',
        ]);
    }

    public function test_l_administrateur_refuse_une_annonce_et_notifie_le_proprietaire(): void
    {
        $admin = User::factory()->admin()->create();
        $proprietaire = User::factory()->proprietaire()->create();

        $annonce = Annonce::factory()->create([
            'user_id' => $proprietaire->id,
            'type_bien_id' => TypeBien::factory()->create()->id,
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.annonces.refuser', $annonce))
            ->assertRedirect();

        $this->assertSame(Annonce::STATUT_REFUSE, $annonce->fresh()->statut_validation);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $proprietaire->id,
            'type' => 'annonce_refusee',
        ]);
    }

    public function test_le_proprietaire_est_notifie_lorsqu_une_annonce_est_creee(): void
    {
        $admin = User::factory()->admin()->create();
        $proprietaire = User::factory()->proprietaire()->create();
        $type = TypeBien::factory()->create();

        $this->actingAs($proprietaire)->post('/annonces', [
            'titre' => 'Studio meublé',
            'description' => 'Studio proche du centre',
            'prix' => 2500,
            'surface' => 35,
            'ville' => 'Beni Mellal',
            'type_bien_id' => $type->id,
        ])->assertRedirect();

        $this->assertDatabaseHas('notifications', [
            'user_id' => $admin->id,
            'type' => 'nouvelle_annonce',
        ]);
    }

    public function test_l_administrateur_peut_gerer_les_categories(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('admin.type-biens.store'), [
            'type' => 'Studio',
        ])->assertRedirect();

        $this->assertDatabaseHas('type_biens', ['type' => 'Studio']);
    }

    public function test_l_administrateur_ne_peut_pas_supprimer_sa_propre_compte(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $admin))
            ->assertForbidden();
    }

    public function test_un_favori_ne_peut_concerner_qu_une_annonce_validee(): void
    {
        $acheteur = User::factory()->acheteur()->create();
        $annonce = Annonce::factory()->create([
            'user_id' => User::factory()->proprietaire()->create()->id,
            'type_bien_id' => TypeBien::factory()->create()->id,
            'statut_validation' => Annonce::STATUT_EN_ATTENTE,
        ]);

        $this->actingAs($acheteur)
            ->post(route('favoris.toggle', $annonce))
            ->assertNotFound();

        $this->assertDatabaseMissing('favoris', ['user_id' => $acheteur->id, 'annonce_id' => $annonce->id]);
    }

    public function test_un_message_lie_a_une_annonce_doit_etre_envoye_a_son_proprietaire(): void
    {
        $annonce = $this->creerAnnonceValidee();
        $acheteur = User::factory()->acheteur()->create();
        $mauvaisDestinataire = User::factory()->proprietaire()->create();

        $this->actingAs($acheteur)->post(route('messages.store'), [
            'destinataire_id' => $mauvaisDestinataire->id,
            'annonce_id' => $annonce->id,
            'contenu' => 'Bonjour',
        ])->assertStatus(422);

        $this->assertDatabaseMissing('messages', ['expediteur_id' => $acheteur->id, 'contenu' => 'Bonjour']);
    }
}
