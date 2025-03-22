<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDateSignatureToCertificatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certificats', function (Blueprint $table) {
            $table->dateTime('dateSignature')->nullable()->after('signatureUtilisateur');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certificats', function (Blueprint $table) {
            $table->dropColumn('dateSignature');
        });
    }
}