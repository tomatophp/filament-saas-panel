<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Uses the `filament-saas-panel.user_table` / `team_id_column` config (users / user_id by default),
     * and is skipped when a teams table already exists (for example from `jetstream:install`).
     */
    public function up(): void
    {
        if (Schema::hasTable('teams')) {
            return;
        }

        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId(config('filament-saas-panel.team_id_column', 'user_id'))
                ->index()
                ->constrained(config('filament-saas-panel.user_table', 'users'))
                ->cascadeOnDelete();
            $table->string('name');
            $table->boolean('personal_team');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
