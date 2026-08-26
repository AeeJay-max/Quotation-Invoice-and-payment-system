<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->unsignedBigInteger('quotation_id')->nullable();
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->foreignId('event_space_id')->nullable()->constrained('event_spaces')->onDelete('set null');
            $table->foreignId('stand_type_id')->nullable()->constrained('stand_types')->onDelete('set null');
            $table->foreignId('space_position_id')->nullable()->constrained('space_positions')->onDelete('set null');
            $table->decimal('width', 8, 2)->default(0);
            $table->decimal('length', 8, 2)->default(0);
            $table->decimal('area_sqm', 8, 2)->default(0);
            $table->decimal('space_cost', 10, 2)->default(0);
            $table->decimal('furniture_total', 10, 2)->default(0);
            $table->decimal('services_total', 10, 2)->default(0);
            $table->decimal('attendee_total', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('vat_amount', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2)->default(0);
            $table->enum('status', ['draft', 'submitted', 'under_review', 'confirmed', 'accepted', 'cancelled', 'rejected'])->default('accepted');
            $table->enum('payment_status', ['unpaid', 'partially_paid', 'paid', 'refunded'])->default('unpaid');
            $table->enum('attendee_status', ['pending', 'submitted', 'under_review', 'approved', 'rejected'])->default('pending');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};
