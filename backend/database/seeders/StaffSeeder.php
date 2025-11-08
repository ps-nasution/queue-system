<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Staff;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Staff::insert([
            ['name'=>'Loket 1','active'=>true],
            ['name'=>'Loket 2','active'=>true],
            ['name'=>'Loket 3','active'=>true],
            ]);
    }
}
