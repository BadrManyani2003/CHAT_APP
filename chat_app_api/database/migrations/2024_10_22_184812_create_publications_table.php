<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicationsTable extends Migration
{
    public function up()
    {
        Schema::create('publications', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->unsignedBigInteger('user_id'); // Référence à l'utilisateur
            $table->text('content'); // Contenu de la publication
            $table->string('img')->nullable(); // Image associée à la publication
            $table->timestamps(); // Champs created_at et updated_at

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('publications');
    }
}
