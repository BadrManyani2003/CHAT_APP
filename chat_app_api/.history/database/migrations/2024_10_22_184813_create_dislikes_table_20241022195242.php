<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDislikesTable extends Migration
{
    public function up()
    {
        Schema::create('dislikes', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->unsignedBigInteger('user_id'); // Référence à l'utilisateur
            $table->unsignedBigInteger('publication_id'); // Référence à la publication
            $table->timestamps(); // Champs created_at et updated_at

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('publication_id')->references('id')->on('publications')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('dislikes');
    }
}
