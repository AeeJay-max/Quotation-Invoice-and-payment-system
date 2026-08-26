<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendee_id')->constrained('attendees')->onDelete('cascade');
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->string('badge_code')->unique();
            $table->text('qr_code_payload')->nullable();
            $table->enum('status', ['not_generated', 'generated', 'printed', 'collected'])->default('generated');
            $table->timestamp('generated_at')->nullable();
            $table->timestamp('printed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('badges');
    }
};
