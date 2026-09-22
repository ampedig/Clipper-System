<?php

use App\Enums\CampaignStatus;
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
        Schema::create('clip_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->text('brief')->nullable();
            $table->text('source_url')->nullable();
            $table->unsignedBigInteger('commission_amount')->default(0);
            $table->unsignedBigInteger('view_threshold')->default(0);
            $table->unsignedBigInteger('view_max')->nullable();
            $table->unsignedInteger('clipper_limit')->nullable();
            $table->timestamp('start_at')->nullable()->index();
            $table->timestamp('end_at')->nullable()->index();
            $table->string('status', 20)->default(CampaignStatus::Upcoming->value)->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clip_campaigns');
    }
};
