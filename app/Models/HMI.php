<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HMI extends Model
{
    use HasFactory;

    protected $table = 'hmis';

    protected $fillable = [
        'area',
        'hostname',
        'ip_address',
        'mac_address',
        'serial_number',
        'model_type',
        'os',
        'ram',
        'ssd_hdd',
        'pc_username',
        'password',
        'monitor_size',
        'location',
    ];
}
