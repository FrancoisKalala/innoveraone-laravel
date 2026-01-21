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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade'); // Id_abonne (sender)
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade'); // Id_contact (receiver)
            $table->unsignedBigInteger('replied_to_message_id')->nullable(); // Id_message_answered
            $table->text('content'); // Contenu_message
            $table->enum('category', ['text', 'photo', 'audio', 'video', 'document'])->default('text'); // Categorie_message
            $table->string('category_id')->nullable(); // Id_categorie_message (file path/id)
            $table->boolean('is_read')->default(false); // Message_read
            $table->timestamp('read_at')->nullable(); // Date_message_read
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();

            $table->index(['sender_id', 'receiver_id', 'created_at']);
            $table->index(['receiver_id', 'is_read']);
            $table->foreign('replied_to_message_id')->references('id')->on('messages')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
