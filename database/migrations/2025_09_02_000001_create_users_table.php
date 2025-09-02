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

            $table->string('name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->unsignedTinyInteger('type_user')->default(3);
            $table->unsignedTinyInteger('type_person');

            $table->string('phone', 20);

            $table->date('birth_date')->nullable();
            $table->string('curp', 18)->nullable();
            $table->string('rfc', 13)->nullable();

            $table->string('razon_social')->nullable();
            $table->string('representante_legal')->nullable();
            $table->string('domicilio_fiscal')->nullable();

            $table->rememberToken();
            $table->timestamps();
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
