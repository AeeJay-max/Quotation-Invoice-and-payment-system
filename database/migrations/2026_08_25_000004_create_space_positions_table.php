<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('space_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_space_id')->constrained('event_spaces')->onDelete('cascade');
            $table->string('position_number');
            $table->string('label')->nullable();
            $table->string('position_type')->default('Standard'); // e.g. Corner, Entrance, Central, Standard
            $table->decimal('additional_fee', 10, 2)->default(0.00);
            $table->enum('status', ['available', 'reserved', 'booked', 'unavailable'])->default('available');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('space_positions');
    }
};
