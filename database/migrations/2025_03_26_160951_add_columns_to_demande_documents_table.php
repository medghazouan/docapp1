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
        Schema::table('demande_documents', function (Blueprint $table) {
            $table->date('dateRetour')->nullable();
            $table->enum('statut_retour', [
                'possede',      
                'rendu',        
                'retard'      
            ])->default('possede');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demande_documents', function (Blueprint $table) {
            $table->dropColumn(['dateRetour', 'statut_retour']);
        });
    }
};
