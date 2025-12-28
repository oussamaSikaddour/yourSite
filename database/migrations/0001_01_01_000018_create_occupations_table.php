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
        Schema::create('occupations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('persons')->onDelete('cascade');
            $table->foreignId('field_id')->constrained('fields')->onDelete('cascade');
             $table->foreignId('field_grade_id')
                  ->nullable()
                  ->constrained('field_grades')
                  ->nullOnDelete();
             $table->foreignId('field_specialty_id')
                  ->nullable()
                  ->constrained('field_specialties')
                  ->nullOnDelete();
            $table->text('description_fr')->nullable();
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->tinyInteger('experience')->default(0);
             $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('occupations');
    }
};
