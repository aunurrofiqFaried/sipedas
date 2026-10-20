<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
            $table->string('username')->unique()->after('name');
            $table->string('nip', 30)->nullable()->after('username');
            // admin, guru, kesiswaan, kepala_sekolah
            // wali kelas BUKAN role, tapi ditentukan dari kelas.wali_kelas_id
            $table->string('role', 20)->default('guru')->after('nip')->index();
            $table->boolean('is_active')->default(true)->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'nip', 'role', 'is_active']);
        });
    }
};
