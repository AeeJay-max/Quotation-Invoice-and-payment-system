<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('event_spaces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('name');
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->decimal('width', 8, 2)->default(0);
            $table->decimal('length', 8, 2)->default(0);
            $table->decimal('min_size', 8, 2)->default(9.00);
            $table->decimal('max_size', 8, 2)->default(500.00);
            $table->decimal('price_per_sqm', 10, 2)->default(50.00);
            $table->decimal('fixed_price', 10, 2)->default(0.00);
            $table->enum('availability_status', ['available', 'limited', 'full', 'unavailable'])->default('available');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('event_spaces');
    }
};
