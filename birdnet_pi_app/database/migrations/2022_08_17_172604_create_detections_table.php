<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detections', function (Blueprint $table) {
            $table->string('sci_name');
            $table->string('com_name');
            $table->float('confidence');
            $table->float('latitude');
            $table->float('longitude');
            $table->float('cutoff');
            $table->tinyInteger('week');
            $table->float('sensitivity');
            $table->float('overlap');
            $table->string('file_name')->primary()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detections');
    }
};
