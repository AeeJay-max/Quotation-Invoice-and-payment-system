<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventProgrammeAndSpeakersTables extends Migration
{
    public function up()
    {
        Schema::create('speakers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('title')->nullable();
            $table->string('organization')->nullable();
            $table->string('position')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('bio')->nullable();
            $table->string('photo_path')->nullable();
            $table->json('social_links')->nullable();
            $table->timestamps();
        });

        Schema::create('event_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('session_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('venue_room')->nullable();
            $table->integer('max_capacity')->nullable();
            $table->string('session_type')->default('presentation'); // presentation, panel, workshop, keynote, ceremony
            $table->string('status')->default('scheduled'); // scheduled, in_progress, completed, cancelled
            $table->timestamps();
        });

        Schema::create('session_speakers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_session_id')->constrained('event_sessions')->onDelete('cascade');
            $table->foreignId('speaker_id')->constrained('speakers')->onDelete('cascade');
            $table->string('role')->default('speaker'); // speaker, moderator, panelist, keynote_speaker
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('session_speakers');
        Schema::dropIfExists('event_sessions');
        Schema::dropIfExists('speakers');
    }
}
