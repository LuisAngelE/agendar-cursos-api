<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignId('course_id')
                ->constrained('courses')
                ->onDelete('cascade');

            $table->foreignId('schedule_id')
                ->nullable()
                ->constrained('events_schedules')
                ->onDelete('cascade');

            $table->dateTime('reserved_at')
                ->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->unsignedTinyInteger('status')->default(1);

            $table->text('cancellation_reason')->nullable();

            $table->timestamps();

            $table->unique(['student_id', 'course_id', 'schedule_id'], 'unique_reservation');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
