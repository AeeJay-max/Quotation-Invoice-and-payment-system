<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsAndCheckinsTables extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('attendee_id')->nullable()->constrained('attendees')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('ticket_type')->default('General'); // General, VIP, VVIP, Media, Speaker, Exhibitor, Staff
            $table->decimal('price', 10, 2)->default(0.00);
            $table->text('qr_code_payload');
            $table->string('status')->default('ISSUED'); // ISSUED, USED, CANCELLED, EXPIRED
            $table->timestamp('issued_at')->useCurrent();
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
        });

        Schema::create('checkins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('ticket_id')->nullable()->constrained('tickets')->onDelete('set null');
            $table->foreignId('attendee_id')->nullable()->constrained('attendees')->onDelete('set null');
            $table->foreignId('badge_id')->nullable()->constrained('badges')->onDelete('set null');
            $table->string('station_name')->default('Main Entrance');
            $table->foreignId('checked_in_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('checkin_time')->useCurrent();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('checkins');
        Schema::dropIfExists('tickets');
    }
}
