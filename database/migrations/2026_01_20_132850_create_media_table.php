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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('path', 191);
            $table->string('type', 50); // image, video, document
            $table->string('mime_type', 100)->nullable();
            $table->integer('size')->nullable();
            $table->string('mediable_type', 100)->nullable();
            $table->unsignedBigInteger('mediable_id')->nullable();
            $table->timestamps();
            $table->index(['mediable_type', 'mediable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
