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
        Schema::create('wilayates', function (Blueprint $table) {
          $table->id();
            $table->string('code', 10)->unique();
            $table->string('designation_ar', 255);
            $table->string('designation_fr', 255);
            $table->string('designation_en', 255);
            $table->softDeletes(); // adds deleted_at column
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wilayates');
    }
};
