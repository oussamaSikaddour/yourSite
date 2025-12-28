<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('heros', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('title_ar'); // Title in Arabic
            $table->string('title_fr'); // Title in French
            $table->string('title_en'); // Title in English
            $table->string('sub_title_ar')->nullable(); // Subtitle in Arabic
            $table->string('sub_title_fr')->nullable(); // Subtitle in French
            $table->string('sub_title_en')->nullable(); // Subtitle in English
            $table->text('introduction_fr')->nullable(); // Subtitle in English
            $table->text('introduction_ar')->nullable(); // Subtitle in English
            $table->text('introduction_en')->nullable(); // Subtitle in English
            $table->text('primary_call_to_action_en')->nullable(); // Subtitle in English
            $table->text('primary_call_to_action_fr')->nullable(); // Subtitle in English
            $table->text('primary_call_to_action_ar')->nullable(); // Subtitle in English
            $table->text('secondary_call_to_action_fr')->nullable(); // Subtitle in English
            $table->text('secondary_call_to_action_ar')->nullable(); // Subtitle in English
            $table->text('secondary_call_to_action_en')->nullable(); // Subtitle in English
            $table->timestamps(); // Created at and Updated at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('heros');
    }
};
