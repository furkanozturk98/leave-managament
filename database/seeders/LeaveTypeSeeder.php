<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        LeaveType::create([
            'title' => 'Mazeret izni',
            'description' => 'Çalışana ait kişisel sebeplerden dolayı alınan izin türüdür'
        ]);
        LeaveType::create([
            'title' => 'Yıllık izin',
            'description' => 'Yasal yıllık izin türüdür'
        ]);
        LeaveType::create([
            'title' => 'Hastalık izni',
            'description' => 'Hastalık nedeniyle alınan ve doktor raporu gerektiren izin türüdür'
        ]);
        LeaveType::create([
            'title' => 'Evlilik izni',
            'description' => 'Evlilik izni üç (3) iş günüdür ve çalışanın nikah tarihi itibariyle başla'
        ]);
        LeaveType::create([
            'title' => 'Doğum günü izni',
            'description' => 'Çalışan doğum gününün olduğu ay içerisinde 1 gün ücretli izin kullanır'
        ]);
        LeaveType::create([
            'title' => 'Analık izni',
            'description' => ''
        ]);
        LeaveType::create([
            'title' => 'Babalık izni',
            'description' => ''
        ]);
    }
}
