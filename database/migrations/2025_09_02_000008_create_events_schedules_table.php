<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('course_id')->nullable()->constrained('courses')->onDelete('cascade');

            $table->enum('event_type', ['curso', 'demo'])->nullable()->index();
            $table->unsignedBigInteger('reference_id')->nullable()->index();

            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('location')->nullable();
            $table->foreignId('state_id')->nullable()->constrained('states')->onDelete('cascade');
            $table->foreignId('municipality_id')->nullable()->constrained('municipalities');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events_schedules');
    }
}
