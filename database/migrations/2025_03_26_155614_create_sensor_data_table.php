<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('sensor_data', function (Blueprint $table) {
            $table->id();
            $table->float('lux');
            $table->float('distance');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sensor_data');
    }
};
