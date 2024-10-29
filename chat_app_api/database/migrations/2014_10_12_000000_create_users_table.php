<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('name'); // Nom de l'utilisateur
            $table->string('email')->unique(); // Adresse e-mail (unique)
            $table->string('password'); // Mot de passe
            $table->string('imgprofile')->nullable(); // Image de profil
            $table->date('birthday')->nullable(); // Date de naissance
            $table->string('gender')->nullable(); // Genre
            $table->string('address')->nullable(); // Adresse
            $table->string('role')->default('user'); // Rôle de l'utilisateur
            $table->timestamp('email_verified_at')->nullable(); // Date de vérification de l'e-mail
            $table->rememberToken(); // Token de "remember me"
            $table->timestamps(); // Champs created_at et updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
