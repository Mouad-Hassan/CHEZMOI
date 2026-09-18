<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('annonces', function (Blueprint $table) {
            $table->dropIndex(['statut_validation', 'type_operation']);
            $table->dropColumn('type_operation');
            $table->index(['statut_validation']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('annonces', function (Blueprint $table) {
            $table->string('type_operation')->default('vente');
            $table->dropIndex(['statut_validation']);
            $table->index(['statut_validation', 'type_operation']);
        });
    }
};
