<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->unsignedBigInteger('publication_id'); // Référence à la publication
            $table->unsignedBigInteger('user_id'); // Référence à l'utilisateur
            $table->text('content'); // Contenu du commentaire
            $table->timestamps(); // Champs created_at et updated_at

            $table->foreign('publication_id')->references('id')->on('publications')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
