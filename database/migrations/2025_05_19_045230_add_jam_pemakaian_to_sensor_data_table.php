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
    Schema::table('sensor_data', function (Blueprint $table) {
        $table->float('jam_pemakaian')->default(0); // tipe dan default bisa disesuaikan
    });
}

    /**
     * Reverse the migrations.
     */
public function down()
{
    Schema::table('sensor_data', function (Blueprint $table) {
        $table->dropColumn('jam_pemakaian');
    });
}
};
