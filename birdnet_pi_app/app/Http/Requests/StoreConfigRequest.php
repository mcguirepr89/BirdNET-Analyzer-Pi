<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConfigRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'RANDOM_SEED' => 'digits_between:2,3',
            'MODEL_PATH' => 'string|ends_with:tflite|starts_with:checkpoints',
            'MDATA_MODEL_PATH' => 'string|ends_with:tflite|starts_with:checkpoints',
            'LABELS_FILE' => 'string',
            'TRANSLATED_LABELS_PATH' => 'string',
            'REC_CARD' => 'string',
            'CHANNELS' => 'integer',
            'SAMPLE_RATE' => 'integer',
            'SIG_LENGTH' => 'integer',
            'SIG_OVERLAP' => 'integer',
            'SIG_MINLEN' => 'integer',
            'RECORDING_LENGTH' => 'integer',
            'SEGMENT_LENGTH' => 'integer',
            'AUDIO_FMT' => 'string',
            'RECS_DIR' => 'string',
            'ANALYZED_DIR' => 'string',
            'SEGMENTS_DIR' => 'string',
            'STORAGE_DIR' => 'string',
            'DATABASE_PATH' => 'string',
            'LANGUAGE' => 'string',
            'STORAGE' => 'string',
            'STORAGE_LIMIT' => 'string',
            'LATITUDE' => 'numeric|nullable',
            'LONGITUDE' => 'numeric|nullable',
            'WEEK' => 'integer',
            'LOCATION_FILTER_THRESHOLD' => 'numeric',
            'CODES_FILE' => 'string',
            'SPECIES_LIST_FILE' => 'string|nullable',
            'INPUT_PATH' => 'string|nullable',
            'OUTPUT_PATH' => 'string|nullable',
            'CPU_THREADS' => 'integer',
            'TFLITE_THREADS' => 'integer',
            'APPLY_SIGMOID' => 'numeric',
            'SIGMOID_SENSITIVITY' => 'numeric',
            'MIN_CONFIDENCE' => 'numeric',
            'BATCH_SIZE' => 'integer',
            'RESULT_TYPE' => 'string',
            'CODES' => 'string|nullable',
            'LABELS' => 'string|nullable',
            'TRANSLATED_LABELS' => 'string|nullable',
            'SPECIES_LIST' => 'string|nullable',
            'ERROR_LOG_FILE' => 'string'
         ];
    }
}
