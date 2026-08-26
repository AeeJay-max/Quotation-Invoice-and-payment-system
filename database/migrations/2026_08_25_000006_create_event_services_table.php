<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('event_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->default('Utilities'); // Electricity, Internet, Cleaning, Security, Branding, AV
            $table->decimal('unit_price', 10, 2)->default(0.00);
            $table->decimal('vat', 5, 2)->default(0.00);
            $table->boolean('availability')->default(true);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('event_services');
    }
};
