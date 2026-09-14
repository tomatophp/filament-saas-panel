<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates the membership table and the Jetstream columns on the configured user table
     * (users by default); tables and columns that already exist are left untouched.
     */
    public function up(): void
    {
        $users = config('filament-saas-panel.user_table', 'users');
        $column = config('filament-saas-panel.team_id_column', 'user_id');

        if (! Schema::hasTable('team_user')) {
            Schema::create('team_user', function (Blueprint $table) use ($users, $column) {
                $table->id();
                $table->foreignId('team_id');
                $table->foreignId($column)->constrained($users)->cascadeOnDelete();
                $table->string('role')->nullable();
                $table->timestamps();

                $table->unique(['team_id', $column]);
            });
        }

        Schema::table($users, function (Blueprint $table) use ($users) {
            if (! Schema::hasColumn($users, 'remember_token')) {
                $table->rememberToken();
            }

            if (! Schema::hasColumn($users, 'current_team_id')) {
                $table->foreignId('current_team_id')->nullable();
            }

            if (! Schema::hasColumn($users, 'profile_photo_path')) {
                $table->string('profile_photo_path', 2048)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_user');
    }
};
