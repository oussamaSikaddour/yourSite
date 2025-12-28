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
        Schema::create('about_us', function (Blueprint $table) {
            $table->id();
            $table->string('sub_title_fr');
            $table->string('sub_title_ar');
            $table->string('sub_title_en');
            $table->text('first_paragraph_fr');
            $table->text('first_paragraph_ar');
            $table->text('first_paragraph_en');
            $table->text('second_paragraph_fr')->nullable();
            $table->text('second_paragraph_ar')->nullable();
            $table->text('second_paragraph_en')->nullable();
            $table->text('third_paragraph_fr')->nullable();
            $table->text('third_paragraph_ar')->nullable();
            $table->text('third_paragraph_en')->nullable();
            $table->timestamps(); // created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_us');
    }
};
