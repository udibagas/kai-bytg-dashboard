<?php

use App\JenisDetailPekerjaan;
use Illuminate\Database\Seeder;

class JenisDetailPekerjaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        JenisDetailPekerjaan::insert([
            ['nama' => 'Pencucuian Gerbong'],
            ['nama' => 'RA Lantai'],
            ['nama' => 'RA Body'],
            ['nama' => 'RB Chasis'],
            ['nama' => 'RB Alat Tolak Tarik'],
            ['nama' => 'RB Pengereman'],
            ['nama' => 'Bogie Frame'],
            ['nama' => 'Bogie Roda dan Bearing'],
            ['nama' => 'Pengecatan Body dan Bogie'],
            ['nama' => 'Stel Tinggi'],
        ]);
    }
}
