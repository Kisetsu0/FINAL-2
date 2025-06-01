<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RiwayatPrediksi;


class RiwayatPrediksi extends Model
{
    use HasFactory;

    protected $table = 'riwayat_prediksi';

    protected $fillable = [
        'a',
        'b',
        'prediksi_jam',
        'prediksi_minggu',
        'waktu_analisis',
    ];
}
