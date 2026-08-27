<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Extend the payments table to support:
     * - Claimed vs Verified amount distinction (full audit trail)
     * - Verified_by / rejected_by admin tracking
     * - Rejection reason
     * - quotation_id and quotation_number (were on model but missing from migration)
     * - Private proof storage path
     *
     * BACKWARD COMPATIBLE: existing `amount` column is kept and becomes amount_claimed.
     * amount_verified is null until admin verifies.
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Rename/alias: existing `amount` = what exhibitor claimed
            // Add amount_verified = what admin actually approved
            if (!Schema::hasColumn('payments', 'amount_claimed')) {
                $table->decimal('amount_claimed', 10, 2)->nullable()->after('client_id');
            }
            if (!Schema::hasColumn('payments', 'amount_verified')) {
                $table->decimal('amount_verified', 10, 2)->nullable()->after('amount_claimed');
            }

            // quotation_id and quotation_number (on model already, add to table if missing)
            if (!Schema::hasColumn('payments', 'quotation_id')) {
                $table->unsignedBigInteger('quotation_id')->nullable()->after('booking_id');
            }
            if (!Schema::hasColumn('payments', 'quotation_number')) {
                $table->string('quotation_number')->nullable()->after('quotation_id');
            }

            // Verification tracking
            if (!Schema::hasColumn('payments', 'verified_by')) {
                $table->unsignedBigInteger('verified_by')->nullable()->after('notes');
                $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('payments', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('verified_by');
            }

            // Rejection tracking
            if (!Schema::hasColumn('payments', 'rejected_by')) {
                $table->unsignedBigInteger('rejected_by')->nullable()->after('verified_at');
                $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('payments', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('rejected_by');
            }
            if (!Schema::hasColumn('payments', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('rejected_at');
            }
        });

        // Back-fill: copy existing `amount` into `amount_claimed` for existing records
        \Illuminate\Support\Facades\DB::statement(
            'UPDATE payments SET amount_claimed = amount WHERE amount_claimed IS NULL'
        );
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropForeign(['rejected_by']);
            $table->dropColumn([
                'amount_claimed', 'amount_verified',
                'quotation_id', 'quotation_number',
                'verified_by', 'verified_at',
                'rejected_by', 'rejected_at', 'rejection_reason',
            ]);
        });
    }
};
