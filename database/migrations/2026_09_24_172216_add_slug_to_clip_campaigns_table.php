<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clip_campaigns', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('title');
        });

        foreach (DB::table('clip_campaigns')->cursor() as $campaign) {
            DB::table('clip_campaigns')
                ->where('id', $campaign->id)
                ->update([
                    'slug' => Str::slug($campaign->title).'-'.$campaign->id,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clip_campaigns', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
