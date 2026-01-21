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
        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // album creator
            $table->string('title'); // Intitule_chap
            $table->string('image')->nullable(); // Img_chap
            $table->text('description')->nullable(); // Description_chap
            $table->string('slug', 191)->unique();
            $table->enum('visibility', ['public', 'private'])->default('public'); // Visibilite_chap (1=public, 2=private)
            $table->string('category')->nullable(); // Album category
            $table->integer('publications_count')->default(0);
            $table->integer('views_count')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index('visibility');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('albums');
    }
};
