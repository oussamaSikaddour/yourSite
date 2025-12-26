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
        Schema::create('general_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('maintenance')->default(false);
            $table->text('map')->nullable();
            $table->string('acronym')->nullable();
            $table->string('app_name')->nullable();
            $table->string('address_fr')->nullable();
            $table->string('address_ar')->nullable();
            $table->string('address_en')->nullable();
            $table->string('landline')->nullable();
            $table->string('fax')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->year('inaugural_year')->nullable();
            $table->string('youtube')->nullable();
            $table->string('facebook')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('github')->nullable();
            $table->string('instagram')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('twitter')->nullable();          // X (formerly Twitter)
            $table->string('threads')->nullable();
            $table->string('snapchat')->nullable();
            $table->string('pinterest')->nullable();
            $table->string('reddit')->nullable();
            $table->string('telegram')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('discord')->nullable();
            $table->string('twitch')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_settings');
    }
};
