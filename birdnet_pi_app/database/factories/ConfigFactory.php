<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Config>
 */
class ConfigFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            // 'RANDOM_SEED' => 42,
            // 'MODEL_PATH' => 'checkpoints/V2.2/BirdNET_GLOBAL_3K_V2.2_Model_FP32.tflite',
            // 'MDATA_MODEL_PATH' => 'checkpoints/V2.2/BirdNET_GLOBAL_3K_V2.2_MData_Model_FP16.tflite',
            // 'LABELS_FILE' => 'checkpoints/V2.2/BirdNET_GLOBAL_3K_V2.2_Labels.txt',
            // 'TRANSLATED_LABELS_PATH' => 'labels/V2.2',
            // 'REC_CARD' => 'default',
            // 'CHANNELS' => 2,
            // 'SAMPLE_RATE' => 48000,
            // 'SIG_LENGTH' => 3.0,
// SIG_OVERLAP = 0 
// SIG_MINLEN = 3.0 
// RECORDING_LENGTH = 15
// SEGMENT_LENGTH = 6
// AUDIO_FMT = 'mp3'
// [OS Paths]
// RECS_DIR = 'Raw'
// ANALYZED_DIR = 'Analyzed'
// SEGMENTS_DIR = 'Segments'
// STORAGE_DIR = 'Storage'
// DATABASE_PATH = 'birdnet_pi_app/database/database.sqlite'
// [Metadata and user settings]
// LANGUAGE = 'en'
// STORAGE = 'purge'
// STORAGE_LIMIT = '750M'
// LATITUDE = 38.8263
// LONGITUDE = -77.2111
// WEEK = -1
// LOCATION_FILTER_THRESHOLD = 0.03
// [Inference settings]
// CODES_FILE = 'eBird_taxonomy_codes_2021E.json'
// SPECIES_LIST_FILE = '' 
// INPUT_PATH = ''
// OUTPUT_PATH = ''
// CPU_THREADS = 2
// TFLITE_THREADS = 1 
// APPLY_SIGMOID = True 
// SIGMOID_SENSITIVITY = 1.0
// MIN_CONFIDENCE = 0.5
// BATCH_SIZE = 1
// RESULT_TYPE = 'csv'
// [Misc runtime vars]
// CODES = {}
// LABELS = []
// TRANSLATED_LABELS = []
// SPECIES_LIST = []
// ERROR_LOG_FILE = 'error_log.txt'

        ];
    }
}
