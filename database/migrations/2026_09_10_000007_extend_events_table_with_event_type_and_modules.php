<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExtendEventsTableWithEventTypeAndModules extends Migration
{
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'event_type')) {
                $table->string('event_type')->default('Exhibition')->after('name');
            }
            if (!Schema::hasColumn('events', 'venue_id')) {
                $table->foreignId('venue_id')->nullable()->after('event_type')->constrained('venues')->onDelete('set null');
            }
            if (!Schema::hasColumn('events', 'enabled_modules')) {
                $table->json('enabled_modules')->nullable()->after('status');
            }
            if (!Schema::hasColumn('events', 'expected_attendance')) {
                $table->integer('expected_attendance')->nullable()->after('enabled_modules');
            }
            if (!Schema::hasColumn('events', 'registration_start')) {
                $table->date('registration_start')->nullable()->after('start_date');
            }
            if (!Schema::hasColumn('events', 'registration_end')) {
                $table->date('registration_end')->nullable()->after('end_date');
            }
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['venue_id']);
            $table->dropColumn([
                'event_type',
                'venue_id',
                'enabled_modules',
                'expected_attendance',
                'registration_start',
                'registration_end',
            ]);
        });
    }
}
