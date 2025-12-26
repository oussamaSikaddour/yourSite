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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar')->nullable();
            $table->string('name_fr')->nullable();
            $table->string('name_en')->nullable();
            $table->foreignId('head_of_service_id')
                ->nullable()
                ->constrained('persons')
                ->nullOnDelete();
            $table->string('email')->nullable()->unique();
            $table->string('tel')->nullable();
            $table->string('fax')->nullable();
            $table->foreignId('specialty_id')
                ->constrained('field_specialties')
                ->cascadeOnDelete();
            $table->unsignedInteger('beds_number')->default(0);
            $table->unsignedInteger('specialists_number')->default(0);
            $table->unsignedInteger('physicians_number')->default(0);
            $table->unsignedInteger('paramedics_number')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
