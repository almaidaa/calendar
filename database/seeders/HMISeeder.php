<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\HMI;
use Illuminate\Database\Seeder;

class HMISeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HMI::create([
            'area' => 'Area 1',
            'hostname' => 'HMI-001',
            'ip_address' => '192.168.1.10',
            'mac_address' => '00:1B:44:11:3A:B7',
            'serial_number' => 'SN123456',
            'model_type' => 'Model X',
            'os' => 'Windows 10',
            'ram' => '8GB',
            'ssd_hdd' => '256GB SSD',
            'pc_username' => 'user1',
            'password' => 'password',
            'monitor_size' => '24 inch',
            'location' => 'Room 101',
        ]);
    }
}
