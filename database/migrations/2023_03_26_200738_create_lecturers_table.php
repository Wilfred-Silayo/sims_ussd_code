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
        Schema::create('lecturers', function (Blueprint $table) {
            $table->string('username')->primary();
            $table->string('admission')->unique();
            $table->string('firstname');
            $table->string('middlename');
            $table->string('lastname');
            $table->string('gender');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('maritalstatus');
            $table->string('nationality');
            $table->date('dob');
            $table->string('department')->nullable();
            $table->string('password');
            $table->string('profile_photo_path')->default('user.png');
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('department')
            ->references('deptcode')->on('departments')
            ->onUpdate('cascade')->onDelete('set null');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturers');
    }
};
