<?php

use App\Bogie;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BogieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bogies')->truncate();

        $data = [
            ['kode' => 'BARBER', 'nama' => 'BARBER'],
            ['kode' => 'SUMITOMO', 'nama' => 'SUMITOMO'],
            ['kode' => 'ROMANIA', 'nama' => 'ROMANIA'],
            ['kode' => 'KUDA KEPANG', 'nama' => 'KUDA KEPANG'],
            ['kode' => 'NISHA', 'nama' => 'NISHA'],
            ['kode' => 'GLONCESTER', 'nama' => 'GLONCESTER'],
        ];

        Bogie::insert($data);
    }
}
