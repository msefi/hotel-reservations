<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FromJsonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file_prov = public_path('json/provinsi.json');
        $file_kab = public_path('json/kabupaten.json');

        $json_prov = file_get_contents($file_prov);
        $json_kab = file_get_contents($file_kab);

        $data_prov = json_decode($json_prov, true);
        $data_kab = json_decode($json_kab, true);

        echo "Memulai proses seeder data Provinsi...\n";
        DB::table('provinsis')->insert($data_prov);
        echo "Done seeder data Provinsi...\n";

        echo "Memulai proses seeder data Kabupaten...\n";
        DB::table('kabupatens')->insert($data_kab);
        echo "Done seeder data Kabupaten...\n";
    }
}