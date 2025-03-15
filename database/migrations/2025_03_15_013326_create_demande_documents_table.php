<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemandeDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('demande_documents', function (Blueprint $table) {
            $table->id('idDemande');
            $table->foreignId('idUtilisateur')->constrained('users', 'idUtilisateur');
            $table->foreignId('idResponsableService')->nullable()->constrained('users', 'idUtilisateur');
            $table->foreignId('idArchiviste')->nullable()->constrained('users', 'idUtilisateur');
            $table->foreignId('idDocument')->constrained('documents', 'idDocument');
            $table->text('description');
            $table->enum('statut', ['en_attente', 'approuvé_responsable', 'refusé_responsable', 'approuvé_archiviste', 'refusé_archiviste', 'récupéré']);
            $table->timestamp('dateSoumission')->useCurrent();
            $table->timestamp('dateValidationResponsable')->nullable();
            $table->timestamp('dateValidationArchiviste')->nullable();
            $table->timestamp('dateRecuperation')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('demande_documents');
    }
}
