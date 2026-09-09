<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPricePerSqmToVenueHallsTable extends Migration
{
    public function up()
    {
        Schema::table('venue_halls', function (Blueprint $table) {
            if (!Schema::hasColumn('venue_halls', 'price_per_sqm')) {
                $table->decimal('price_per_sqm', 10, 2)->default(0.00)->after('total_area_sqm');
            }
        });
    }

    public function down()
    {
        Schema::table('venue_halls', function (Blueprint $table) {
            if (Schema::hasColumn('venue_halls', 'price_per_sqm')) {
                $table->dropColumn('price_per_sqm');
            }
        });
    }
}
