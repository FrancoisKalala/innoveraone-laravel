<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('group_message_reads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
            $table->unsignedBigInteger('message_id'); // from groupe_message table
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('read')->default(false);
            $table->timestamps();

            $table->unique(['group_id', 'message_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_message_reads');
    }
};
