<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sync_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('sync_type', ['hearts', 'game_sessions', 'user_progress', 'full_sync']);
            $table->enum('direction', ['sqlite_to_mysql', 'mysql_to_sqlite', 'bidirectional']);
            $table->enum('status', ['pending', 'in_progress', 'completed', 'failed']);
            $table->integer('records_processed')->default(0);
            $table->integer('records_synced')->default(0);
            $table->integer('records_failed')->default(0);
            $table->json('error_details')->nullable(); // Store any errors that occurred
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable(); // Additional notes about the sync
            $table->timestamps();
            
            $table->index(['sync_type', 'status']);
            $table->index(['started_at', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sync_logs');
    }
};