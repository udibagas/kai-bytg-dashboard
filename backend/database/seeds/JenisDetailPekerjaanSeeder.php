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
        JenisDetailPekerjaan::truncate();

        JenisDetailPekerjaan::insert([
            ['nama' => 'Pencucuian Gerbong', 'urutan' => 1, 'bobot' => 5],
            ['nama' => 'RA Lantai', 'urutan' => 2, 'bobot' => 5],
            ['nama' => 'RA Body', 'urutan' => 3, 'bobot' => 15],
            ['nama' => 'RB Chasis', 'urutan' => 4, 'bobot' => 10],
            ['nama' => 'RB Alat Tolak Tarik', 'urutan' => 5, 'bobot' => 10],
            ['nama' => 'RB Pengereman', 'urutan' => 6, 'bobot' => 5],
            ['nama' => 'Bogie Frame', 'urutan' => 7, 'bobot' => 5],
            ['nama' => 'Bogie Roda dan Bearing', 'urutan' => 8, 'bobot' => 5],
            ['nama' => 'Pengecatan Body dan Bogie', 'urutan' => 9, 'bobot' => 5],
            ['nama' => 'Stel Tinggi', 'urutan' => 10, 'bobot' => 5],
        ]);
    }
}
