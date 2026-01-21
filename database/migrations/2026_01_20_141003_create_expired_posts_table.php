<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expired_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            $table->timestamp('expired_at');
            $table->timestamps();

            $table->unique('post_id');
            $table->index('expired_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expired_posts');
    }
};
