<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPeopleCountToQuotationsAndBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            if (!Schema::hasColumn('quotations', 'people_count')) {
                $table->integer('people_count')->default(1);
            }
        });
        
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'people_count')) {
                $table->integer('people_count')->default(1);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            if (Schema::hasColumn('quotations', 'people_count')) {
                $table->dropColumn('people_count');
            }
        });

        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'people_count')) {
                $table->dropColumn('people_count');
            }
        });
    }
}
