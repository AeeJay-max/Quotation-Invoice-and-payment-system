<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('furniture', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->default('Seating');
            $table->decimal('unit_price', 10, 2)->default(0.00);
            $table->decimal('vat', 5, 2)->default(0.00);
            $table->integer('available_quantity')->default(100);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('furniture');
    }
};
