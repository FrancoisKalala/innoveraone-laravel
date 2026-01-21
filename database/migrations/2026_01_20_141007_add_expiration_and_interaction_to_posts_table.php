<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->integer('expiration_hours')->default(24)->after('content');
            $table->enum('interaction_type', [
                'like',
                'dislike',
                'comment',
                'like_dislike',
                'like_comment',
                'dislike_comment',
                'all',
                'none'
            ])->default('all')->after('expiration_hours');
            $table->boolean('already_expired')->default(false)->after('interaction_type');
            $table->boolean('already_deleted')->default(false)->after('already_expired');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
                'expiration_hours',
                'interaction_type',
                'already_expired',
                'already_deleted'
            ]);
        });
    }
};
