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
        Schema::create('persons', function (Blueprint $table) {
            $table->id();
            $table->string('last_name_ar')->nullable();
            $table->string('first_name_ar')->nullable();
            $table->string('last_name_fr')->nullable();
            $table->string('first_name_fr')->nullable();
            $table->string('card_number')->nullable()->unique();
            $table->string('birth_place_fr')->nullable();
            $table->string('birth_place_ar')->nullable();
            $table->string('birth_place_en')->nullable();
            $table->date('birth_date')->nullable();
            $table->text('address_ar')->nullable();
            $table->text('address_fr')->nullable();
            $table->text('address_en')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->string('employee_number')->unique()->nullable();
            $table->string('social_number')->unique()->nullable();
            $table->timestamps();
            $table->softDeletes(); // Add for soft deletes
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('persons');
    }
};
