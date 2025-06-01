<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LampData extends Model
{
    use HasFactory;

    protected $fillable = [
    'minggu_ke',
    'tanggal',
    'jam_pemakaian',
    'lux',
];

}
