<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffTasksIncidentsFeedbackTables extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->string('priority')->default('MEDIUM'); // LOW, MEDIUM, HIGH, URGENT
            $table->string('status')->default('TODO'); // TODO, IN_PROGRESS, BLOCKED, COMPLETED, CANCELLED
            $table->date('due_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('category'); // Venue, Security, Transport, Catering, Marketing, Entertainment, Equipment
            $table->decimal('allocated_amount', 12, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('event_incidents', function (Blueprint $table) {
            $table->id();
            $table->string('incident_number')->unique();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('incident_type')->default('General'); // Security, Medical, Technical, Lost Property, Facility
            $table->string('severity')->default('MEDIUM'); // LOW, MEDIUM, HIGH, CRITICAL
            $table->string('location')->nullable();
            $table->text('description');
            $table->foreignId('reported_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->string('status')->default('open'); // open, under_investigation, resolved, closed
            $table->text('resolution_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('feedback_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('respondent_type')->default('attendee'); // attendee, exhibitor, speaker, sponsor, vendor
            $table->integer('overall_rating')->default(5); // 1-5
            $table->integer('venue_rating')->nullable();
            $table->integer('organization_rating')->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();
        });

        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained('events')->onDelete('cascade');
            $table->string('title');
            $table->string('category')->default('General'); // Contract, Certificate, Insurance, FloorPlan, Report
            $table->string('file_path');
            $table->string('mime_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->boolean('is_private')->default(true);
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
        Schema::dropIfExists('feedback_responses');
        Schema::dropIfExists('event_incidents');
        Schema::dropIfExists('budgets');
        Schema::dropIfExists('tasks');
    }
}
