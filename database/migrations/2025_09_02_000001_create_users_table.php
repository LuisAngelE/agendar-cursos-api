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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100)->nullable();
            $table->string('middle_name', 100)->nullable();
            $table->string('first_last_name')->nullable();
            $table->string('second_last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->unsignedTinyInteger('type_user')->nullable()->default(3);
            $table->unsignedTinyInteger('type_person')->nullable()->default(4);

            $table->string('phone', 20);
            $table->string('collaborator_number', 10)->nullable();

            $table->date('birth_date')->nullable();
            $table->string('curp', 18)->nullable();
            $table->string('rfc', 13)->nullable();

            $table->string('razon_social')->nullable();
            $table->string('representante_legal')->nullable();
            $table->string('domicilio_fiscal')->nullable();

            $table->string('position')->nullable();
            $table->string('url')->nullable();

            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
