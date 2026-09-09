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
        Schema::table('venue_halls', function (Blueprint $table) {
            if (!Schema::hasColumn('venue_halls', 'available_area_sqm')) {
                $table->decimal('available_area_sqm', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('venue_halls', 'vip_tickets_quota')) {
                $table->integer('vip_tickets_quota')->default(0);
                $table->integer('vip_tickets_available')->default(0);
                $table->decimal('vip_ticket_price', 10, 2)->default(0.00);
            }
            if (!Schema::hasColumn('venue_halls', 'general_tickets_quota')) {
                $table->integer('general_tickets_quota')->default(0);
                $table->integer('general_tickets_available')->default(0);
                $table->decimal('general_ticket_price', 10, 2)->default(0.00);
            }
            if (!Schema::hasColumn('venue_halls', 'delegate_tickets_quota')) {
                $table->integer('delegate_tickets_quota')->default(0);
                $table->integer('delegate_tickets_available')->default(0);
                $table->decimal('delegate_ticket_price', 10, 2)->default(0.00);
            }
        });

        Schema::table('event_spaces', function (Blueprint $table) {
            if (!Schema::hasColumn('event_spaces', 'total_area_sqm')) {
                $table->decimal('total_area_sqm', 10, 2)->default(500.00);
                $table->decimal('available_area_sqm', 10, 2)->default(500.00);
            }
            if (!Schema::hasColumn('event_spaces', 'vip_tickets_quota')) {
                $table->integer('vip_tickets_quota')->default(50);
                $table->integer('vip_tickets_available')->default(50);
                $table->decimal('vip_ticket_price', 10, 2)->default(100.00);
            }
            if (!Schema::hasColumn('event_spaces', 'general_tickets_quota')) {
                $table->integer('general_tickets_quota')->default(200);
                $table->integer('general_tickets_available')->default(200);
                $table->decimal('general_ticket_price', 10, 2)->default(25.00);
            }
            if (!Schema::hasColumn('event_spaces', 'delegate_tickets_quota')) {
                $table->integer('delegate_tickets_quota')->default(100);
                $table->integer('delegate_tickets_available')->default(100);
                $table->decimal('delegate_ticket_price', 10, 2)->default(50.00);
            }
        });

        Schema::table('quotations', function (Blueprint $table) {
            if (!Schema::hasColumn('quotations', 'vip_tickets_count')) {
                $table->integer('vip_tickets_count')->default(0);
                $table->integer('general_tickets_count')->default(0);
                $table->integer('delegate_tickets_count')->default(0);
                $table->decimal('tickets_cost', 10, 2)->default(0.00);
            }
        });

        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'vip_tickets_count')) {
                $table->integer('vip_tickets_count')->default(0);
                $table->integer('general_tickets_count')->default(0);
                $table->integer('delegate_tickets_count')->default(0);
                $table->decimal('tickets_cost', 10, 2)->default(0.00);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('venue_halls', function (Blueprint $table) {
            $table->dropColumn([
                'available_area_sqm',
                'vip_tickets_quota', 'vip_tickets_available', 'vip_ticket_price',
                'general_tickets_quota', 'general_tickets_available', 'general_ticket_price',
                'delegate_tickets_quota', 'delegate_tickets_available', 'delegate_ticket_price'
            ]);
        });

        Schema::table('event_spaces', function (Blueprint $table) {
            $table->dropColumn([
                'total_area_sqm', 'available_area_sqm',
                'vip_tickets_quota', 'vip_tickets_available', 'vip_ticket_price',
                'general_tickets_quota', 'general_tickets_available', 'general_ticket_price',
                'delegate_tickets_quota', 'delegate_tickets_available', 'delegate_ticket_price'
            ]);
        });

        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn(['vip_tickets_count', 'general_tickets_count', 'delegate_tickets_count', 'tickets_cost']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['vip_tickets_count', 'general_tickets_count', 'delegate_tickets_count', 'tickets_cost']);
        });
    }
};
