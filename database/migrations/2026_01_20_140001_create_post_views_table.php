<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('viewed')->default(false);
            $table->timestamps();

            $table->unique(['post_id', 'user_id']);
            $table->index(['user_id', 'viewed']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_views');
    }
};
