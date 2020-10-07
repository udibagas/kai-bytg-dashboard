<?php

use App\JenisPekerjaan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisPekerjaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'kode' => 'P.24',
                'nama' => 'Perbaikan 24 Bulan'
            ],
            [
                'kode' => 'P.48',
                'nama' => 'Perbaikan 48 Bulan'
            ],
            [
                'kode' => 'PB',
                'nama' => 'Perbaikan (Body, Bogie)'
            ],
            [
                'kode' => 'TSGP',
                'nama' => 'Tidak Siap Guna Operasi'
            ],
        ];

        DB::table('jenis_pekerjaans')->truncate();
        DB::table('jenis_pekerjaans')->insert($data);
    }
}
