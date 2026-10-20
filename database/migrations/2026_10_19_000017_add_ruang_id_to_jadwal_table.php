<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal', function (Blueprint $table) {
            $table->foreignId('ruang_id')->nullable()->after('guru_id')
                  ->constrained('ruang')->nullOnDelete();
            $table->index(['ruang_id', 'hari']);
        });
    }

    public function down(): void
    {
        Schema::table('jadwal', function (Blueprint $table) {
            $table->dropIndex(['ruang_id', 'hari']);
            $table->dropConstrainedForeignId('ruang_id');
        });
    }
};
