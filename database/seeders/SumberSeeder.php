<?php

namespace Database\Seeders;

use App\Models\Sumber;
use Illuminate\Database\Seeder;

class SumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sumberData = [
            'Gaji',
            'Bahan baku',
            'Operasional',
            'Uang darurat',
        ];

        foreach ($sumberData as $nama) {
            Sumber::create([
                'nama' => $nama,
            ]);
        }
    }
}
