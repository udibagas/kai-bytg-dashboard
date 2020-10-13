<?php

use App\Dipo;
use Illuminate\Database\Seeder;

class DipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Dipo::insert([
            ['kode' => 'RK', 'nama' => 'Rangkas'],
            ['kode' => 'MA', 'nama' => 'Maos'],
            ['kode' => 'JAKG', 'nama' => 'Jakarta Gudang'],
            ['kode' => 'SDT', 'nama' => 'Sidotopo'],
            ['kode' => 'RWL', 'nama' => 'Rewulu'],
            ['kode' => 'AWN', 'nama' => 'Arjawinangun'],
            ['kode' => 'BD', 'nama' => 'Bandung'],
            ['kode' => 'YK', 'nama' => 'Yogyakarta'],
            ['kode' => 'JR', 'nama' => 'Jember'],
            ['kode' => 'BW', 'nama' => 'Banyuwangi'],
            ['kode' => 'SMC', 'nama' => 'Semarang Poncol'],
        ]);
    }
}
