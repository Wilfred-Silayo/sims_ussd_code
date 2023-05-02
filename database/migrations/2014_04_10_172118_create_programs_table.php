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
        Schema::create('programs', function (Blueprint $table) {
            $table->string('programID')->primary();
            $table->string('programname');
            $table->integer('capacity')->default(600);
            $table->string('ntalevel');
            $table->string('department')->nullable();
            $table->foreign('department')->references('deptcode')->on('departments')
            ->onUpdate('cascade')->onDelete('set null');
            $table->timestamps();
     
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
