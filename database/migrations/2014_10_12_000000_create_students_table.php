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
        Schema::create('students', function (Blueprint $table) {
            $table->string('username')->primary();
            $table->string('admission')->unique();
            $table->string('firstname');
            $table->string('middlename');
            $table->string('lastname');
            $table->string('gender');
            $table->date('dob');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('maritalstatus');
            $table->string('nationality');
            $table->string('program')->nullable();
            $table->integer('yearofstudy');
            $table->string('active')->default('yes');
            $table->string('status')->default('continuing');
            $table->string('password');
            $table->string('profile_photo_path')->default('user.png');
            $table->foreign('program')->references('programID')->on('programs')
            ->onUpdate('cascade')->onDelete('set null');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
