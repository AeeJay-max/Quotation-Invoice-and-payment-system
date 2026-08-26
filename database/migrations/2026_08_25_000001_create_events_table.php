<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('event_code')->unique();
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->date('registration_open_date')->nullable();
            $table->date('registration_close_date')->nullable();
            $table->string('venue')->nullable();
            $table->string('address')->nullable();
            $table->string('country')->default('Zimbabwe');
            $table->string('currency')->default('USD');
            $table->decimal('vat_rate', 5, 2)->default(15.00);
            $table->enum('status', ['draft', 'published', 'registration_open', 'registration_closed', 'completed', 'cancelled'])->default('published');
            $table->string('banner_path')->nullable();
            $table->text('terms_and_conditions')->nullable();
            $table->text('booking_guidelines')->nullable();
            $table->text('contact_info')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
};
