<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Bank code (unique)
            $table->string('designation_ar')->nullable(); // Arabic designation
            $table->string('designation_fr')->nullable(); // French designation
            $table->string('designation_en')->nullable(); // English designation
            $table->string('acronym')->unique(); // Bank acronym (optional)
            $table->timestamps(); // created_at and updated_at columns
            $table->softDeletes(); // deleted_at column for soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};
