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
        Schema::create('configs', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('RANDOM_SEED')->default(42);
            $table->string('MODEL_PATH')->default('checkpoints/V2.2/BirdNET_GLOBAL_3K_V2.2_Model_FP32.tflite');
            $table->string('MDATA_MODEL_PATH')->default('checkpoints/V2.2/BirdNET_GLOBAL_3K_V2.2_MData_Model_FP16.tflite');
            $table->string('LABELS_FILE')->default('checkpoints/V2.2/BirdNET_GLOBAL_3K_V2.2_Labels.txt');
            $table->string('TRANSLATED_LABELS_PATH')->default('labels/V2.2');
            $table->string('REC_CARD')->default('default');
            $table->tinyInteger('CHANNELS')->default(2);
            $table->integer('SAMPLE_RATE')->default(48000);
            $table->float('SIG_LENGTH')->default(3.0); 
            $table->float('SIG_OVERLAP')->default(0); 
            $table->float('SIG_MINLEN')->default(3.0);
            $table->tinyInteger('RECORDING_LENGTH')->default(15);
            $table->tinyInteger('SEGMENT_LENGTH')->default(6);
            $table->string('AUDIO_FMT')->default('mp3');
            $table->string('RECS_DIR')->default('Raw');
            $table->string('ANALYZED_DIR')->default('Analyzed');
            $table->string('SEGMENTS_DIR')->default('Segments');
            $table->string('STORAGE_DIR')->default('Storage');
            $table->string('DATABASE_PATH')->default('birdnet_pi_app/database/database.sqlite');
            $table->string('LANGUAGE')->default('en');
            $table->string('STORAGE')->default('purge');
            $table->string('STORAGE_LIMIT')->default('750M');
            $table->float('LATITUDE')->nullable();
            $table->float('LONGITUDE')->nullable();
            $table->tinyInteger('WEEK')->default(-1);
            $table->float('LOCATION_FILTER_THRESHOLD')->default(0.03);
            $table->string('CODES_FILE')->default('eBird_taxonomy_codes_2021E.json');
            $table->string('SPECIES_LIST_FILE')->nullable();
            $table->string('INPUT_PATH')->nullable();
            $table->string('OUTPUT_PATH')->nullable();
            $table->tinyInteger('CPU_THREADS')->default(2);
            $table->tinyInteger('TFLITE_THREADS')->default(1); 
            $table->boolean('APPLY_SIGMOID')->default(True); 
            $table->float('SIGMOID_SENSITIVITY')->default(1.0);
            $table->float('MIN_CONFIDENCE')->default(0.5);
            $table->tinyInteger('BATCH_SIZE')->default(1);
            $table->string('RESULT_TYPE')->default('csv');
            $table->string('CODES')->nullable();
            $table->string('LABELS')->nullable();
            $table->string('TRANSLATED_LABELS')->nullable();
            $table->string('SPECIES_LIST')->nullable();
            $table->string('ERROR_LOG_FILE')->default('error_log.txt');
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
        Schema::dropIfExists('configs');
    }
};
