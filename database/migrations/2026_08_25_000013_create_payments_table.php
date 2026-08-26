<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->onDelete('set null');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('USD');
            $table->string('payment_method')->default('Bank Transfer');
            $table->string('transaction_reference')->nullable();
            $table->string('proof_of_payment_path')->nullable();
            $table->date('payment_date')->nullable();
            $table->enum('status', ['pending', 'submitted', 'verified', 'rejected'])->default('submitted');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
