<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns');
            $table->foreignId('platform_id')->constrained('platforms');
            $table->string('judul', 150);
            $table->text('caption');
            $table->enum('format', ['feed', 'reels', 'story', 'video', 'artikel'])->default('feed');
            $table->date('tanggal_tayang');
            $table->date('tanggal_ditayangkan')->nullable();
            $table->enum('status', ['draft', 'dijadwalkan', 'tayang'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
