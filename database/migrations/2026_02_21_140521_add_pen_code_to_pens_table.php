<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('pens', 'pen_code')) {
            Schema::table('pens', function (Blueprint $table): void {
                $table->string('pen_code', 20)->nullable()->unique()->after('pen_id');
            });
        }

        $pens = DB::table('pens')
            ->select('pen_id')
            ->orderBy('pen_id')
            ->get();

        foreach ($pens as $pen) {
            DB::table('pens')
                ->where('pen_id', $pen->pen_id)
                ->update([
                    'pen_code' => sprintf('PEN-%03d', (int) $pen->pen_id),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('pens', 'pen_code')) {
            Schema::table('pens', function (Blueprint $table): void {
                $table->dropUnique('pens_pen_code_unique');
                $table->dropColumn('pen_code');
            });
        }
    }
};
