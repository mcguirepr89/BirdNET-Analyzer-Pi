#################
# Misc settings #
#################

# Random seed for gaussian noise
RANDOM_SEED = 42

##########################
# Model paths and config #
##########################

MODEL_PATH = 'checkpoints/V2.2/BirdNET_GLOBAL_3K_V2.2_Model_FP32.tflite'
MDATA_MODEL_PATH = 'checkpoints/V2.2/BirdNET_GLOBAL_3K_V2.2_MData_Model_FP16.tflite'
LABELS_FILE = 'checkpoints/V2.2/BirdNET_GLOBAL_3K_V2.2_Labels.txt'
TRANSLATED_LABELS_PATH = 'labels/V2.2'

##################
# Audio settings #
##################

# The sound card arecord should use for recording. 'default' denotes
# PulseAudio. ('default' recommended)
REC_CARD = 'default'

# The number of audio channels for recording. Note: This is mostly to ensure
# you can bring your own soundcard. Inference still only uses 1 audio channel.
CHANNELS = 2

# We use a sample rate of 48kHz, so the model input size is 
# (batch size, 48000 kHz * 3 seconds) = (1, 144000)
# Recordings will be resampled automatically.
SAMPLE_RATE = 48000 

# We're using 3-second chunks
SIG_LENGTH = 3.0 

# Define overlap between consecutive chunks <3.0; 0 = no overlap
SIG_OVERLAP = 0 

# Define minimum length of audio chunk for prediction, 
# chunks shorter than 3 seconds will be padded with noise
SIG_MINLEN = 3.0 

# The length recordings should be before passing them off to be analyzed.
# 3 seconds is the minumum, 15 seconds is recommended
RECORDING_LENGTH = 15

# The length of the audio segement that gets extracted as a 'segment'
SEGMENT_LENGTH = 6

# The audio format for the segments and recording. 
AUDIO_FMT = 'mp3'

#####################
#      OS Paths     #
#####################
# Recording Directory path
RECS_DIR = 'Raw'

# Analyzed Directory path
ANALYZED_DIR = 'Analyzed'

# Segments Directory path
SEGMENTS_DIR = 'Segments'

# Storage Directory path (see STORAGE below for more info)
STORAGE_DIR = 'Storage'

# SQLite Database path
DATABASE_PATH = 'birdnet_pi_app/database/database.sqlite'

#####################
# Metadata and user #
#      settings     #
#####################
# System language settings
LANGUAGE = 'en'

# Raw data storage option. If set to 'keep' the system will store the
# amount of data defined in the STORAGE_LIMIT variable directly below.
# If set to 'purge,' no raw data is stored.
STORAGE = 'purge'

# Set this to the amount of raw data that should be kept.
# Unit options: B(bytes) M(megabytes) G(gigabytes)
# Example: STORAGE_LIMIT = 1G
STORAGE_LIMIT = '750M'
LATITUDE = 38.8263
LONGITUDE = -77.2111
WEEK = -1
LOCATION_FILTER_THRESHOLD = 0.03

######################
# Inference settings #
######################

# If None or empty file, no custom species list will be used
# Note: Entries in this list have to match entries from the LABELS_FILE
# We use the 2021 eBird taxonomy for species names (Clements list)
CODES_FILE = 'eBird_taxonomy_codes_2021E.json'
SPECIES_LIST_FILE = '' 

# File input path and output path for selection tables
INPUT_PATH = ''
OUTPUT_PATH = ''

# Number of threads to use for inference.
# Can be as high as number of CPUs in your system
CPU_THREADS = 2
TFLITE_THREADS = 1 

# False will output logits, True will convert to sigmoid activations
APPLY_SIGMOID = True 
SIGMOID_SENSITIVITY = 1.0

# Minimum confidence score to include in selection table 
# (be aware: if APPLY_SIGMOID = False, this no longer represents 
# probabilities and needs to be adjusted)
MIN_CONFIDENCE = 0.5

# Number of samples to process at the same time. Higher values can increase
# processing speed, but will also increase memory usage.
# Might only be useful for GPU inference.
BATCH_SIZE = 1

# Specifies the output format. 'table' denotes a Raven selection table,
# 'audacity' denotes a TXT file with the same format as Audacity timeline labels
# 'csv' denotes a CSV file with start, end, species and confidence.
RESULT_TYPE = 'csv'

#####################
# Misc runtime vars #
#####################
CODES = {}
LABELS = []
TRANSLATED_LABELS = []
SPECIES_LIST = []
ERROR_LOG_FILE = 'error_log.txt'

######################
# Get and set config #
######################

def getConfig():
    return {
        'RANDOM_SEED': RANDOM_SEED,
        'MODEL_PATH': MODEL_PATH,
        'MDATA_MODEL_PATH': MDATA_MODEL_PATH,
        'LABELS_FILE': LABELS_FILE,
        'REC_CARD': REC_CARD,
        'CHANNELS': CHANNELS,
        'SAMPLE_RATE': SAMPLE_RATE,
        'SIG_LENGTH': SIG_LENGTH,
        'SIG_OVERLAP': SIG_OVERLAP,
        'SIG_MINLEN': SIG_MINLEN,
        'RECORDING_LENGTH': RECORDING_LENGTH,
        'SEGMENT_LENGTH': SEGMENT_LENGTH,
        'AUDIO_FMT': AUDIO_FMT,
        'RECS_DIR': RECS_DIR,
        'ANALYZED_DIR': ANALYZED_DIR,
        'SEGMENTS_DIR': SEGMENTS_DIR,
        'STORAGE_DIR': STORAGE_DIR,
        'DATABASE_PATH': DATABASE_PATH,
        'LANGUAGE': LANGUAGE,
        'STORAGE': STORAGE,
        'STORAGE_LIMIT': STORAGE_LIMIT,
        'LATITUDE': LATITUDE,
        'LONGITUDE': LONGITUDE,
        'WEEK': WEEK,
        'LOCATION_FILTER_THRESHOLD': LOCATION_FILTER_THRESHOLD,
        'CODES_FILE': CODES_FILE,
        'SPECIES_LIST_FILE': SPECIES_LIST_FILE,
        'INPUT_PATH': INPUT_PATH,
        'OUTPUT_PATH': OUTPUT_PATH,
        'CPU_THREADS': CPU_THREADS,
        'TFLITE_THREADS': TFLITE_THREADS,
        'APPLY_SIGMOID': APPLY_SIGMOID,
        'SIGMOID_SENSITIVITY': SIGMOID_SENSITIVITY,
        'MIN_CONFIDENCE': MIN_CONFIDENCE,
        'BATCH_SIZE': BATCH_SIZE,
        'RESULT_TYPE': RESULT_TYPE,
        'CODES': CODES,
        'LABELS': LABELS,
        'TRANSLATED_LABELS': TRANSLATED_LABELS,
        'SPECIES_LIST': SPECIES_LIST,
        'ERROR_LOG_FILE': ERROR_LOG_FILE
    }

def setConfig(c):

    global RANDOM_SEED
    global MODEL_PATH
    global MDATA_MODEL_PATH
    global LABELS_FILE
    global REC_CARD
    global CHANNELS
    global SAMPLE_RATE
    global SIG_LENGTH
    global SIG_OVERLAP
    global SIG_MINLEN
    global RECORDING_LENGTH
    global SEGMENT_LENGTH
    global AUDIO_FMT
    global RECS_DIR
    global ANALYZED_DIR
    global SEGMENTS_DIR
    global STORAGE_DIR
    global DATABASE_PATH
    global LANGUAGE
    global STORAGE
    global STORAGE_LIMIT
    global LATITUDE
    global LONGITUDE
    global WEEK
    global LOCATION_FILTER_THRESHOLD
    global CODES_FILE
    global SPECIES_LIST_FILE
    global INPUT_PATH
    global OUTPUT_PATH
    global CPU_THREADS
    global TFLITE_THREADS
    global APPLY_SIGMOID
    global SIGMOID_SENSITIVITY
    global MIN_CONFIDENCE
    global BATCH_SIZE
    global RESULT_TYPE
    global CODES
    global LABELS
    global TRANSLATED_LABELS
    global SPECIES_LIST
    global ERROR_LOG_FILE

    RANDOM_SEED = c['RANDOM_SEED']
    MODEL_PATH = c['MODEL_PATH']
    MDATA_MODEL_PATH = c['MDATA_MODEL_PATH']
    LABELS_FILE = c['LABELS_FILE']
    REC_CARD = c['REC_CARD']
    CHANNELS = c['CHANNELS']
    SAMPLE_RATE = c['SAMPLE_RATE']
    SIG_LENGTH = c['SIG_LENGTH']
    SIG_OVERLAP = c['SIG_OVERLAP']
    SIG_MINLEN = c['SIG_MINLEN']
    RECORDING_LENGTH = c['RECORDING_LENGTH']
    SEGMENT_LENGTH = c['SEGMENT_LENGTH']
    AUDIO_FMT = c['AUDIO_FMT']
    RECS_DIR = c['RECS_DIR']
    ANALYZED_DIR = c['ANALYZED_DIR']
    SEGMENTS_DIR = c['SEGMENTS_DIR']
    STORAGE_DIR = c['STORAGE_DIR']
    DATABASE_PATH = c['DATABASE_PATH']
    LANGUAGE = c['LANGUAGE']
    STORAGE = c['STORAGE']
    STORAGE_LIMIT = c['STORAGE_LIMIT']
    LATITUDE = c['LATITUDE']
    LONGITUDE = c['LONGITUDE']
    WEEK = c['WEEK']
    LOCATION_FILTER_THRESHOLD = c['LOCATION_FILTER_THRESHOLD']
    CODES_FILE = c['CODES_FILE']
    SPECIES_LIST_FILE = c['SPECIES_LIST_FILE']
    INPUT_PATH = c['INPUT_PATH']
    OUTPUT_PATH = c['OUTPUT_PATH']
    CPU_THREADS = c['CPU_THREADS']
    TFLITE_THREADS = c['TFLITE_THREADS']
    APPLY_SIGMOID = c['APPLY_SIGMOID']
    SIGMOID_SENSITIVITY = c['SIGMOID_SENSITIVITY']
    MIN_CONFIDENCE = c['MIN_CONFIDENCE']
    BATCH_SIZE = c['BATCH_SIZE']
    RESULT_TYPE = c['RESULT_TYPE']
    CODES = c['CODES']
    LABELS = c['LABELS']
    TRANSLATED_LABELS = c['TRANSLATED_LABELS']
    SPECIES_LIST = c['SPECIES_LIST']
    ERROR_LOG_FILE = c['ERROR_LOG_FILE']
