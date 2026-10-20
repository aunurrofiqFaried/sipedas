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
        Schema::table('siswa', function (Blueprint $table) {
            $table->string('nama_wali', 100)->nullable()->after('jenis_kelamin');
            $table->string('hubungan_wali', 30)->nullable()->after('nama_wali'); // ayah / ibu / wali
            $table->string('no_hp_wali', 20)->nullable()->after('hubungan_wali');
        });
    }

    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn(['nama_wali', 'hubungan_wali', 'no_hp_wali']);
        });
    }
};
