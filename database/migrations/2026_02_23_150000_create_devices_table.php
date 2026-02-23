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
        Schema::create('devices', function (Blueprint $table) {
            $table->string('device_id')->primary();
            $table->string('device_name')->nullable();
            $table->string('device_type')->default('esp8266');
            $table->string('mac_address', 20)->unique();
            $table->string('ip_address', 45)->nullable();
            $table->string('firmware_version')->nullable();
            $table->string('connection_status')->default('offline');
            $table->timestamp('last_seen_at')->nullable();
            $table->string('pen_code', 20)->nullable()->unique();
            $table->timestamp('record_date')->useCurrent();

            $table->foreign('pen_code')
                ->references('pen_code')
                ->on('pens')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
