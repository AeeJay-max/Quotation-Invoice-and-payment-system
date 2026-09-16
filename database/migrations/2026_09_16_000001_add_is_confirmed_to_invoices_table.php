<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'is_confirmed')) {
                $table->boolean('is_confirmed')->default(false)->after('payment_status');
            }
            if (!Schema::hasColumn('invoices', 'confirmed_at')) {
                $table->timestamp('confirmed_at')->nullable()->after('is_confirmed');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'is_confirmed')) {
                $table->dropColumn('is_confirmed');
            }
            if (Schema::hasColumn('invoices', 'confirmed_at')) {
                $table->dropColumn('confirmed_at');
            }
        });
    }
};
