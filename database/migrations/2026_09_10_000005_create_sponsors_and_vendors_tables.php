<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSponsorsAndVendorsTables extends Migration
{
    public function up()
    {
        Schema::create('sponsors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('sponsor_package')->default('Gold'); // Platinum, Gold, Silver, Bronze, Partner
            $table->decimal('contribution_amount', 12, 2)->default(0.00);
            $table->string('payment_status')->default('pending'); // pending, partially_paid, paid
            $table->string('contact_person')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('logo_path')->nullable();
            $table->text('benefits_summary')->nullable();
            $table->timestamps();
        });

        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('company_name');
            $table->string('service_category'); // Security, Catering, Cleaning, Transport, Sound, Lighting, Medical, ICT
            $table->string('contact_person')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->decimal('contract_value', 12, 2)->default(0.00);
            $table->string('payment_status')->default('pending'); // pending, deposit_paid, fully_paid
            $table->string('status')->default('active'); // active, completed, terminated
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendors');
        Schema::dropIfExists('sponsors');
    }
}
