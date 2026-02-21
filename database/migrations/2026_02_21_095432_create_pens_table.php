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
        Schema::create('pens', function (Blueprint $table) {
            $table->increments('pen_id');
            $table->string('pen_code', 20)->nullable()->unique();
            $table->string('pen_name');
            $table->integer('capacity');
            $table->string('status')->default('available');
            $table->string('notes')->nullable();
            $table->timestamp('record_date')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pens');
    }
};
