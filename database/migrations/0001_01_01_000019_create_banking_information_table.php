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
            Schema::create('banking_information', function (Blueprint $table) {
                $table->id();
                $table->string('agency_ar')->nullable();
                $table->string('agency_en')->nullable();
                $table->string('agency_fr')->nullable();
                $table->string('agency_code')->nullable();
                $table->string('account_number');
                $table->foreignId('bank_id')->constrained()->onDelete('cascade'); // Assuming banks table exists
                $table->morphs('bankable'); // Adds bankable_id and bankable_type for polymorphic relation
                $table->boolean('is_active')->default(false);
                $table->softDeletes();
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('banking_information');
        }

};
