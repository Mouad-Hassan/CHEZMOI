<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('annonces', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('type_bien_id')
                ->constrained('type_biens')
                ->cascadeOnDelete();

            $table->string('titre');
            $table->text('description');
            $table->decimal('prix', 12, 2);
            $table->decimal('surface', 10, 2);

            $table->integer('nombre_chambres')->nullable();
            $table->integer('nombre_salles_bain')->nullable();

            $table->string('ville');
            $table->string('adresse')->nullable();

            $table->string('type_operation');
            $table->string('statut_validation');

            $table->date('date_publication');
            $table->unsignedBigInteger('vues')->default(0);

            // Index pour la recherche avancée (performance < 3s exigée par le cahier des charges)
            $table->index(['statut_validation', 'type_operation']);
            $table->index(['ville', 'prix']);
            $table->index('type_bien_id');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annonces');
    }
};
