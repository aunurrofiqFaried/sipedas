<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JurusanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['RPL', 'Rekayasa Perangkat Lunak'],
            ['TKJ', 'Teknik Komputer dan Jaringan'],
            ['DKV', 'Desain Komunikasi Visual'],
            ['KUL', 'Kuliner'],
        ];

        foreach ($data as [$kode, $nama]) {
            DB::table('jurusan')->updateOrInsert(
                ['kode' => $kode],
                ['nama' => $nama, 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
