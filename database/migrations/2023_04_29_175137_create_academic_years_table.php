<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            $table->string('semester');
            $table->boolean('current')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('academic_years');
    }
};
