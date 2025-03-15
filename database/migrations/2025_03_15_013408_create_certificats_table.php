<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificatsTable extends Migration
{
    public function up()
    {
        Schema::create('certificats', function (Blueprint $table) {
            $table->id('idCertificat');
            $table->foreignId('idDemande')->constrained('demande_documents', 'idDemande');
            $table->foreignId('idUtilisateur')->constrained('users', 'idUtilisateur');
            $table->foreignId('idDocument')->constrained('documents', 'idDocument');
            $table->timestamp('dateGeneration')->useCurrent();
            $table->boolean('signatureUtilisateur')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificats');
    }
}
