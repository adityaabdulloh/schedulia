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
        Schema::table('pengambilan_mk', function (Blueprint $table) {
            $table->foreignId('pengampu_id')->nullable()->after('matakuliah_id')->constrained('pengampu')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengambilan_mk', function (Blueprint $table) {
            $table->dropForeign(['pengampu_id']);
            $table->dropColumn('pengampu_id');
        });
    }
};
