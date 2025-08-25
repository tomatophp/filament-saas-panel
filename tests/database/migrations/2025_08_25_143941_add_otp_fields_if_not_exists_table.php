<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable(config('filament-saas-panel.user_table'))) {
            Schema::table(config('filament-saas-panel.user_table'), function (Blueprint $table) {
                if (! Schema::hasColumn(config('filament-saas-panel.user_table'), 'phone')) {
                    $table->string('phone')->nullable();
                }
                if (! Schema::hasColumn(config('filament-saas-panel.user_table'), 'username')) {
                    $table->string('username')->nullable();
                }
                if (! Schema::hasColumn(config('filament-saas-panel.user_table'), 'login_by')) {
                    $table->string('login_by')->nullable();
                }
                if (! Schema::hasColumn(config('filament-saas-panel.user_table'), 'otp_code')) {
                    $table->string('otp_code')->nullable();
                }
                if (! Schema::hasColumn(config('filament-saas-panel.user_table'), 'otp_activated_at')) {
                    $table->dateTime('otp_activated_at')->nullable();
                }
                if (! Schema::hasColumn(config('filament-saas-panel.user_table'), 'last_login')) {
                    $table->dateTime('last_login')->nullable();
                }
                if (! Schema::hasColumn(config('filament-saas-panel.user_table'), 'agent')) {
                    $table->longText('agent')->nullable();
                }
                if (! Schema::hasColumn(config('filament-saas-panel.user_table'), 'host')) {
                    $table->string('host')->nullable();
                }
                if (! Schema::hasColumn(config('filament-saas-panel.user_table'), 'is_login')) {
                    $table->boolean('is_login')->default(0)->nullable();
                }
                if (! Schema::hasColumn(config('filament-saas-panel.user_table'), 'is_active')) {
                    $table->boolean('is_active')->default(false);
                }
                if (! Schema::hasColumn(config('filament-saas-panel.user_table'), 'is_notification_active')) {
                    $table->boolean('is_notification_active')->default(true);
                }
            });
        }
    }

    public function down()
    {
        Schema::table(config('filament-saas-panel.user_table'), function (Blueprint $table) {
            if (Schema::hasColumn(config('filament-saas-panel.user_table'), 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn(config('filament-saas-panel.user_table'), 'username')) {
                $table->dropColumn('username');
            }
            if (Schema::hasColumn(config('filament-saas-panel.user_table'), 'login_by')) {
                $table->dropColumn('login_by');
            }
            if (Schema::hasColumn(config('filament-saas-panel.user_table'), 'otp_code')) {
                $table->dropColumn('otp_code');
            }
            if (Schema::hasColumn(config('filament-saas-panel.user_table'), 'otp_activated_at')) {
                $table->dropColumn('otp_activated_at');
            }
            if (Schema::hasColumn(config('filament-saas-panel.user_table'), 'last_login')) {
                $table->dropColumn('last_login');
            }
            if (Schema::hasColumn(config('filament-saas-panel.user_table'), 'agent')) {
                $table->dropColumn('agent');
            }
            if (Schema::hasColumn(config('filament-saas-panel.user_table'), 'host')) {
                $table->dropColumn('host');
            }
            if (Schema::hasColumn(config('filament-saas-panel.user_table'), 'is_login')) {
                $table->dropColumn('is_login');
            }
            if (Schema::hasColumn(config('filament-saas-panel.user_table'), 'is_active')) {
                $table->dropColumn('is_active');
            }
            if (Schema::hasColumn(config('filament-saas-panel.user_table'), 'is_notification_active')) {
                $table->dropColumn('is_notification_active');
            }
        });
    }
};
