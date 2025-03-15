<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('idUtilisateur');
            $table->string('nom');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('fonction')->nullable();
            $table->string('societe')->nullable();
            $table->string('direction')->nullable();
            $table->string('service')->nullable();
            $table->enum('role', ['utilisateur', 'responsable', 'archiviste', 'admin']);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}