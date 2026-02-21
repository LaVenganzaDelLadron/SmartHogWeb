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
        Schema::create('feeding_records', function (Blueprint $table) {
            $table->increments('feeding_id');
            $table->string('batch_id');
            $table->float('feeding_quantity_kg');
            $table->time('feeding_time');
            $table->date('feeding_date');
            $table->string('feeding_type');

            $table->foreign('batch_id')->references('batch_id')->on('pig_batches')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feeding_records');
    }
};
