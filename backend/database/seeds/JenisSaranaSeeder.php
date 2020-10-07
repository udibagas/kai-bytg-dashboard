<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisSaranaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('jenis_saranas')->truncate();

        DB::table('jenis_saranas')->insert([
            ['nama' => 'GERBONG DATAR', 'kode' => 'GD'],
            ['nama' => 'GERBONG TERBUKA', 'kode' => 'GB'],
            ['nama' => 'GERBONG TERTUTUP', 'kode' => 'GT'],
            ['nama' => 'GERBONG KETEL', 'kode' => 'GK'],
        ]);
    }
}
