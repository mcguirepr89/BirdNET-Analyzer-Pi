<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConfigRequest;
use App\Http\Requests\UpdateConfigRequest;
use App\Models\Config;

class ConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('config.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreConfigRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreConfigRequest $request)
    {
        $config = Config::find(1);
        $validated = $request->validated();
        if($validated)
        {
            $config->RANDOM_SEED = $validated['RANDOM_SEED'];            
            $config->MODEL_PATH = $validated['MODEL_PATH'];
            $config->MDATA_MODEL_PATH = $validated['MDATA_MODEL_PATH'];
            $config->LABELS_FILE = $validated['LABELS_FILE'];
            $config->TRANSLATED_LABELS_PATH = $validated['TRANSLATED_LABELS_PATH'];
            $config->REC_CARD = $validated['REC_CARD'];
            $config->CHANNELS = $validated['CHANNELS'];
            $config->SAMPLE_RATE = $validated['SAMPLE_RATE'];
            $config->SIG_LENGTH = $validated['SIG_LENGTH'];
            $config->SIG_OVERLAP = $validated['SIG_OVERLAP'];
            $config->SIG_MINLEN = $validated['SIG_MINLEN'];
            $config->RECORDING_LENGTH = $validated['RECORDING_LENGTH'];
            $config->SEGMENT_LENGTH = $validated['SEGMENT_LENGTH'];
            $config->AUDIO_FMT = $validated['AUDIO_FMT'];
            $config->RECS_DIR = $validated['RECS_DIR'];
            $config->ANALYZED_DIR = $validated['ANALYZED_DIR'];
            $config->SEGMENTS_DIR = $validated['SEGMENTS_DIR'];
            $config->STORAGE_DIR = $validated['STORAGE_DIR'];
            $config->DATABASE_PATH = $validated['DATABASE_PATH'];
            $config->LANGUAGE = $validated['LANGUAGE'];
            $config->STORAGE = $validated['STORAGE'];
            $config->STORAGE_LIMIT = $validated['STORAGE_LIMIT'];
            $config->LATITUDE = $validated['LATITUDE'];
            $config->LONGITUDE = $validated['LONGITUDE'];
            $config->WEEK = $validated['WEEK'];
            $config->LOCATION_FILTER_THRESHOLD = $validated['LOCATION_FILTER_THRESHOLD'];
            $config->CODES_FILE = $validated['CODES_FILE'];
            $config->SPECIES_LIST_FILE = $validated['SPECIES_LIST_FILE'];
            $config->INPUT_PATH = $validated['INPUT_PATH'];
            $config->OUTPUT_PATH = $validated['OUTPUT_PATH'];
            $config->CPU_THREADS = $validated['CPU_THREADS'];
            $config->TFLITE_THREADS = $validated['TFLITE_THREADS'];
            $config->APPLY_SIGMOID = $validated['APPLY_SIGMOID'];
            $config->SIGMOID_SENSITIVITY = $validated['SIGMOID_SENSITIVITY'];
            $config->MIN_CONFIDENCE = $validated['MIN_CONFIDENCE'];
            $config->BATCH_SIZE = $validated['BATCH_SIZE'];
            $config->RESULT_TYPE = $validated['RESULT_TYPE'];
            $config->CODES = $validated['CODES'];
            $config->LABELS = $validated['LABELS'];
            $config->TRANSLATED_LABELS = $validated['TRANSLATED_LABELS'];
            $config->SPECIES_LIST = $validated['SPECIES_LIST'];
            $config->ERROR_LOG_FILE = $validated['ERROR_LOG_FILE'];
            $config->save();

            $flash_message = "Settings updated successfully!";
            session()->flash('success', "$flash_message");
            return view('config.index');
        }
    }

}
