<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('quotations', function (Blueprint $table) {
            if (!Schema::hasColumn('quotations', 'quotation_number')) {
                $table->string('quotation_number')->nullable()->unique();
            }
            if (!Schema::hasColumn('quotations', 'event_id')) {
                $table->unsignedBigInteger('event_id')->nullable();
            }
            if (!Schema::hasColumn('quotations', 'booking_id')) {
                $table->unsignedBigInteger('booking_id')->nullable();
            }
            if (!Schema::hasColumn('quotations', 'due_date')) {
                $table->date('due_date')->nullable();
            }
            if (!Schema::hasColumn('quotations', 'payment_type')) {
                $table->unsignedBigInteger('payment_type')->nullable();
            }
            if (!Schema::hasColumn('quotations', 'payment_currency')) {
                $table->unsignedBigInteger('payment_currency')->nullable();
            }
            if (!Schema::hasColumn('quotations', 'status')) {
                $table->string('status')->default('submitted');
            }
            if (!Schema::hasColumn('quotations', 'space_cost')) {
                $table->decimal('space_cost', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('quotations', 'furniture_total')) {
                $table->decimal('furniture_total', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('quotations', 'services_total')) {
                $table->decimal('services_total', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('quotations', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('quotations', 'total')) {
                $table->decimal('total', 10, 2)->default(0);
            }
        });

        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'invoice_number')) {
                $table->string('invoice_number')->nullable()->unique();
            }
            if (!Schema::hasColumn('invoices', 'event_id')) {
                $table->unsignedBigInteger('event_id')->nullable();
            }
            if (!Schema::hasColumn('invoices', 'booking_id')) {
                $table->unsignedBigInteger('booking_id')->nullable();
            }
            if (!Schema::hasColumn('invoices', 'quotation_id')) {
                $table->unsignedBigInteger('quotation_id')->nullable();
            }
            if (!Schema::hasColumn('invoices', 'amount_paid')) {
                $table->decimal('amount_paid', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('invoices', 'amount_outstanding')) {
                $table->decimal('amount_outstanding', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('invoices', 'total')) {
                $table->decimal('total', 10, 2)->default(0);
            }
        });

        Schema::table('clients', function (Blueprint $table) {
            if (!Schema::hasColumn('clients', 'registration_number')) {
                $table->string('registration_number')->nullable();
            }
            if (!Schema::hasColumn('clients', 'position')) {
                $table->string('position')->nullable();
            }
            if (!Schema::hasColumn('clients', 'mobile')) {
                $table->string('mobile')->nullable();
            }
            if (!Schema::hasColumn('clients', 'postal_address')) {
                $table->string('postal_address')->nullable();
            }
            if (!Schema::hasColumn('clients', 'country')) {
                $table->string('country')->nullable();
            }
            if (!Schema::hasColumn('clients', 'website')) {
                $table->string('website')->nullable();
            }
            if (!Schema::hasColumn('clients', 'business_category')) {
                $table->string('business_category')->nullable();
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'client_id')) {
                $table->unsignedBigInteger('client_id')->nullable();
            }
        });

        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'quotation_id')) {
                $table->unsignedBigInteger('quotation_id')->nullable();
            }
            if (!Schema::hasColumn('payments', 'quotation_number')) {
                $table->string('quotation_number')->nullable();
            }
        });
    }

    public function down()
    {
        // Safe column removal omitted for SQLite compatibility
    }
};
