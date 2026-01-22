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
        Schema::table('messages', function (Blueprint $table) {
            if (!Schema::hasColumn('messages', 'is_forwarded_post')) {
                $table->boolean('is_forwarded_post')->default(false)->after('category_id');
            }
            if (!Schema::hasColumn('messages', 'forwarded_post_id')) {
                $table->unsignedBigInteger('forwarded_post_id')->nullable()->after('is_forwarded_post');
                $table->foreign('forwarded_post_id')->references('id')->on('posts')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            if (Schema::hasColumn('messages', 'forwarded_post_id')) {
                $table->dropForeign(['forwarded_post_id']);
                $table->dropColumn('forwarded_post_id');
            }
            if (Schema::hasColumn('messages', 'is_forwarded_post')) {
                $table->dropColumn('is_forwarded_post');
            }
        });
    }
};
