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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->integer('age')->nullable();
            $table->enum('education_level', ['elementary', 'middle_school', 'high_school', 'diploma', 'bachelor', 'master', 'phd'])->nullable();
            $table->string('profile_picture')->nullable();
            $table->boolean('rules_accepted')->default(false);
            $table->timestamp('rules_accepted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username',
                'gender',
                'age',
                'education_level',
                'profile_picture',
                'rules_accepted',
                'rules_accepted_at'
            ]);
        });
    }
};
