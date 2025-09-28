<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_hearts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('hearts')->default(6); // Current hearts count
            $table->integer('max_hearts')->default(6); // Maximum hearts allowed
            $table->timestamp('last_heart_lost_at')->nullable(); // When last heart was lost
            $table->timestamp('last_heart_regenerated_at')->nullable(); // When last heart was regenerated
            $table->integer('hearts_purchased_today')->default(0); // Hearts purchased today
            $table->date('purchase_date')->nullable(); // Date of last purchase
            $table->boolean('is_synced')->default(false); // Whether synced with offline DB
            $table->timestamp('last_synced_at')->nullable(); // Last sync time
            $table->timestamps();
            
            $table->unique('user_id');
            $table->index(['user_id', 'is_synced']);
            $table->index('last_synced_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_hearts');
    }
};