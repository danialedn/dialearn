<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('stage_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->char('selected_answer', 1)->nullable(); // A, B, C, or D
            $table->boolean('is_correct')->nullable();
            $table->integer('score')->default(0);
            $table->timestamps();
            
            $table->unique(['user_id', 'question_id']);
            $table->index(['user_id', 'stage_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_progress');
    }
};