<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->unsignedBigInteger('sender_id'); // Référence à l'expéditeur
            $table->unsignedBigInteger('receiver_id'); // Référence au destinataire
            $table->text('content'); // Contenu du message
            $table->boolean('is_read')->default(false); // Statut de lecture
            $table->timestamps(); // Champs created_at et updated_at

            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
