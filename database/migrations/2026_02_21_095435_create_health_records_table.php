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
        Schema::create('health_records', function (Blueprint $table) {
            $table->increments('health_id');
            $table->string('batch_id');
            $table->boolean('vaccine_given');
            $table->boolean('vitamin_given');
            $table->integer('pig_age_days');
            $table->timestamp('record_date')->useCurrent();

            $table->foreign('batch_id')->references('batch_id')->on('pig_batches')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_records');
    }
};
