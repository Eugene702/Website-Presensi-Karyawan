<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Presensi extends Model
{
    protected $table = 'presensis';

    protected $casts = [
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
    ];

    // Atau gunakan $dates untuk Laravel versi lama
    protected $dates = [
        'clock_in',
        'clock_out',
    ];

}
