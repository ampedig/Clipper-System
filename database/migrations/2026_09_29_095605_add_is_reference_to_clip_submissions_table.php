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
        Schema::table('clip_submissions', function (Blueprint $table) {
            $table->boolean('is_reference')->default(false)->after('status');
            $table->index(['clip_campaign_id', 'is_reference', 'status'], 'idx_clip_subs_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clip_submissions', function (Blueprint $table) {
            $table->dropIndex('idx_clip_subs_reference');
            $table->dropColumn('is_reference');
        });
    }
};
