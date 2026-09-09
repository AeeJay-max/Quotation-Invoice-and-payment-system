<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFloorPlansTables extends Migration
{
    public function up()
    {
        Schema::create('floor_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('venue_hall_id')->nullable()->constrained('venue_halls')->onDelete('set null');
            $table->string('name');
            $table->integer('width_units')->default(100);
            $table->integer('height_units')->default(100);
            $table->text('background_image_path')->nullable();
            $table->json('settings')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('floor_plan_objects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('floor_plan_id')->constrained('floor_plans')->onDelete('cascade');
            $table->foreignId('space_position_id')->nullable()->constrained('space_positions')->onDelete('set null');
            $table->string('object_type')->default('stand'); // stand, stage, entrance, exit, rest_area, info_desk
            $table->string('label');
            $table->decimal('pos_x', 8, 2);
            $table->decimal('pos_y', 8, 2);
            $table->decimal('width', 8, 2);
            $table->decimal('height', 8, 2);
            $table->string('color')->nullable();
            $table->string('status')->default('available'); // available, pending, reserved, booked, blocked
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('floor_plan_objects');
        Schema::dropIfExists('floor_plans');
    }
}
