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
        Schema::create('modules', function (Blueprint $table) {
            $table->string('modulecode')->primary();
            $table->string('modulename');
            $table->integer('credit');
            $table->string('elective')->default('no');
            $table->string('department')->nullable();
            $table->string('program')->nullable();
            $table->string('semester');
            $table->string('lecturerID')->nullable();
            $table->foreign('department')->references('deptcode')->on('departments')
            ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('lecturerID')->references('username')->on('lecturers')
            ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('program')->references('programID')->on('programs')
            ->onUpdate('cascade')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};