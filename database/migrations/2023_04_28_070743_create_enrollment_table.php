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
        Schema::create('enrollment', function (Blueprint $table) {
            $table->string('studentID');
            $table->string('moduleCode');
            $table->integer('semester');  
            $table->string('academicYear');
            $table->double('Coursework',5,2)->nullable();
            $table->double('semesterExam',5,2)->nullable();
            $table->boolean('published')->default(false);
            $table->foreign('studentID')->references('username')->on('students')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('moduleCode')->references('modulecode')->on('modules')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
            $table->primary(['studentID', 'moduleCode']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollment');
    }
};
