<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('riwayat_prediksi', function (Blueprint $table) {
        $table->id();
        $table->float('a');
        $table->float('b');
        $table->float('prediksi_jam');
        $table->integer('prediksi_minggu');
        $table->timestamp('waktu_analisis')->useCurrent();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_prediksi');
    }
};
