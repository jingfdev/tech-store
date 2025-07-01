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
            // Add the correct provider columns
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            
            // Remove the old incorrect columns
            $table->dropColumn(['google', 'google_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Restore the old columns
            $table->string('google')->nullable();
            $table->string('google_id')->nullable();
            
            // Remove the provider columns
            $table->dropColumn(['provider', 'provider_id']);
        });
    }
};
