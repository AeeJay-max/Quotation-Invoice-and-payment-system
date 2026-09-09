<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVenuesAndHierarchyTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->default('Harare');
            $table->string('country')->default('Zimbabwe');
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->integer('capacity')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('venue_halls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained('venues')->onDelete('cascade');
            $table->string('name');
            $table->string('building_name')->nullable();
            $table->string('floor_level')->nullable();
            $table->decimal('total_area_sqm', 10, 2)->nullable();
            $table->integer('capacity')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('venue_halls');
        Schema::dropIfExists('venues');
    }
}
