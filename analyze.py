import os
import sys
import json
import operator
import argparse
import datetime
import traceback

from multiprocessing import Pool, freeze_support

import numpy as np

import config as cfg
import audio
import model
import sqlite3
from sqlite3 import Error

def clearErrorLog():

    if os.path.isfile(cfg.ERROR_LOG_FILE):
        os.remove(cfg.ERROR_LOG_FILE)

def writeErrorLog(msg):

    with open(cfg.ERROR_LOG_FILE, 'a') as elog:
        elog.write(msg + '\n')

def parseInputFiles(path, allowed_filetypes=['wav', 'flac', 'mp3', 'ogg', 'm4a']):

    # Add backslash to path if not present
    if not path.endswith(os.sep):
        path += os.sep

    # Get all files in directory with os.walk
    files = []
    for root, dirs, flist in os.walk(path):
        for f in flist:
            if len(f.rsplit('.', 1)) > 1 and f.rsplit('.', 1)[1].lower() in allowed_filetypes:
                files.append(os.path.join(root, f))

    print('Found {} files to analyze'.format(len(files)))
    return sorted(files)

def loadCodes():

    with open(cfg.CODES_FILE, 'r') as cfile:
        codes = json.load(cfile)

    return codes

def loadLabels(labels_file):

    labels = []
    with open(labels_file, 'r') as lfile:
        for line in lfile.readlines():
            labels.append(line.replace('\n', ''))    

    return labels

def loadSpeciesList(fpath):

    slist = []
    if not fpath == None:
        with open(fpath, 'r') as sfile:
            for line in sfile.readlines():
                species = line.replace('\r', '').replace('\n', '')
                slist.append(species)

    return slist

def predictSpeciesList():

    l_filter = model.explore(cfg.LATITUDE, cfg.LONGITUDE, cfg.WEEK)
    cfg.SPECIES_LIST_FILE = None
    cfg.SPECIES_LIST = []
    for s in l_filter:
        if s[0] >= cfg.LOCATION_FILTER_THRESHOLD:
            cfg.SPECIES_LIST.append(s[1])

def saveResultFile(r, path, afile_path):

    # Make folder if it doesn't exist
    if len(os.path.dirname(path)) > 0 and not os.path.exists(os.path.dirname(path)):
        os.makedirs(os.path.dirname(path))

    # Selection table
    out_string = ''

    if cfg.RESULT_TYPE == 'table':

        # Raven selection header
        header = 'Selection\tView\tChannel\tBegin Time (s)\tEnd Time (s)\tLow Freq (Hz)\tHigh Freq (Hz)\tSpecies Code\tCommon Name\tConfidence\n'
        selection_id = 0

        # Write header
        out_string += header
        
        # Extract valid predictions for every timestamp
        for timestamp in sorted(r):
            rstring = ''
            start, end = timestamp.split('-')
            for c in r[timestamp]:
                if c[1] > cfg.MIN_CONFIDENCE and c[0] in cfg.CODES and (c[0] in cfg.SPECIES_LIST or len(cfg.SPECIES_LIST) == 0):
                    selection_id += 1
                    label = cfg.TRANSLATED_LABELS[cfg.LABELS.index(c[0])]
                    rstring += '{}\tSpectrogram 1\t1\t{}\t{}\t{}\t{}\t{}\t{}\t{:.4f}\n'.format(
                        selection_id, 
                        start, 
                        end, 
                        150, 
                        12000, 
                        cfg.CODES[c[0]], 
                        label.split('_')[1], 
                        c[1])

            # Write result string to file
            if len(rstring) > 0:
                out_string += rstring

    elif cfg.RESULT_TYPE == 'audacity':

        # Audacity timeline labels
        for timestamp in sorted(r):
            rstring = ''
            for c in r[timestamp]:
                if c[1] > cfg.MIN_CONFIDENCE and c[0] in cfg.CODES and (c[0] in cfg.SPECIES_LIST or len(cfg.SPECIES_LIST) == 0):
                    label = cfg.TRANSLATED_LABELS[cfg.LABELS.index(c[0])]
                    rstring += '{}\t{}\t{:.4f}\n'.format(
                        timestamp.replace('-', '\t'), 
                        label.replace('_', ', '), 
                        c[1])

            # Write result string to file
            if len(rstring) > 0:
                out_string += rstring

    elif cfg.RESULT_TYPE == 'r':

        # Output format for R
        header = 'filepath,start,end,scientific_name,common_name,confidence,lat,lon,week,overlap,sensitivity,min_conf,species_list,model'
        out_string += header

        for timestamp in sorted(r):
            rstring = ''
            start, end = timestamp.split('-')
            for c in r[timestamp]:
                if c[1] > cfg.MIN_CONFIDENCE and c[0] in cfg.CODES and (c[0] in cfg.SPECIES_LIST or len(cfg.SPECIES_LIST) == 0):                    
                    label = cfg.TRANSLATED_LABELS[cfg.LABELS.index(c[0])]
                    rstring += '\n{},{},{},{},{},{:.4f},{:.4f},{:.4f},{},{},{},{},{},{}'.format(
                        afile_path,
                        start,
                        end,
                        label.split('_')[0],
                        label.split('_')[1],
                        c[1],
                        cfg.LATITUDE,
                        cfg.LONGITUDE,
                        cfg.WEEK,
                        cfg.SIG_OVERLAP,
                        (1.0 - cfg.SIGMOID_SENSITIVITY) + 1.0,
                        cfg.MIN_CONFIDENCE,
                        cfg.SPECIES_LIST_FILE,
                        os.path.basename(cfg.MODEL_PATH)
                    )
            # Write result string to file
            if len(rstring) > 0:
                out_string += rstring

    else:

        # CSV output file
        header = 'Start (s),End (s),Scientific name,Common name,Confidence\n'

        # Write header
        out_string += header

        for timestamp in sorted(r):
            rstring = ''
            for c in r[timestamp]:                
                start, end = timestamp.split('-')
                if c[1] > cfg.MIN_CONFIDENCE and c[0] in cfg.CODES and (c[0] in cfg.SPECIES_LIST or len(cfg.SPECIES_LIST) == 0):
                    label = cfg.TRANSLATED_LABELS[cfg.LABELS.index(c[0])]
                    rstring += '{},{},{},{},{:.4f}\n'.format(
                        start,
                        end,
                        label.split('_')[0],
                        label.split('_')[1],
                        c[1])

            # Write result string to file
            if len(rstring) > 0:
                out_string += rstring

    # Save as file
    with open(path, 'w') as rfile:
        rfile.write(out_string)



def saveSQLFile(r, path, afile_path):
    for timestamp in sorted(r):
        rstring = ''
        for c in r[timestamp]:                
            start, end = timestamp.split('-')
            if c[1] > cfg.MIN_CONFIDENCE and c[0] in cfg.CODES and (c[0] in cfg.SPECIES_LIST or len(cfg.SPECIES_LIST) == 0):
                label = cfg.TRANSLATED_LABELS[cfg.LABELS.index(c[0])]
                confscore = '{:.3f}'.format(c[1])
                rstring += '{},{},{},{},{}\n'.format(
                    start,
                    end,
                    label.split('_')[0],
                    label.split('_')[1],
                    confscore)
                sourcefile = args.i.split('/')[5]
                filename = 'storage/Segments/' + label.split('_')[1] + '/' + label.split('_')[1].replace(" ", "_") + '_' + confscore + '_' + args.i.split('/')[5]
                conn = None
                try:
                    conn = sqlite3.connect(cfg.DATABASE_PATH)
                    cur = conn.cursor()
                    created_at = datetime.datetime.now()
                    updated_at = datetime.datetime.now()
                    try:
                        cur.execute("INSERT INTO detections VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", (label.split('_')[0], label.split('_')[1], confscore, float(cfg.LATITUDE), float(cfg.LONGITUDE), float(cfg.MIN_CONFIDENCE), int(args.week), float(cfg.SIGMOID_SENSITIVITY), float(cfg.SIG_OVERLAP), filename, created_at, updated_at))
                        conn.commit()
                    except Error as e:
                        print(e)
                        cur.execute("CREATE TABLE IF NOT EXISTS detections (\
                            sci_name VARCHAR(100) NOT NULL,\
                            com_name VARCHAR(100) NOT NULL,\
                            confidence FLOAT,\
                            latitude FLOAT,\
                            longitude FLOAT,\
                            cutoff FLOAT,\
                            week INT,\
                            sens FLOAT,\
                            overlap FLOAT,\
                            file_name VARCHAR(100) NOT NULL,\
                            created_at DATETIME NULL,\
                            updated_at DATETIME NULL)")
                        conn.commit()
                        cur.execute("INSERT INTO detections VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", (label.split('_')[0], label.split('_')[1], confscore, float(cfg.LATITUDE), float(cfg.LONGITUDE), float(cfg.MIN_CONFIDENCE), int(args.week), float(cfg.SIGMOID_SENSITIVITY), float(cfg.SIG_OVERLAP), filename, created_at, updated_at))
                        conn.commit()
                except Error as e:
                    print(e)
                finally:
                  if conn:
                      conn.close()        
                print("Success! Detection logged")    

def getSortedTimestamps(results):
    return sorted(results, key=lambda t: float(t.split('-')[0]))

def getRawAudioFromFile(fpath):

    # Open file
    sig, rate = audio.openAudioFile(fpath, cfg.SAMPLE_RATE)
    
    # Split into raw audio chunks

    chunks = audio.splitSignal(sig, rate, cfg.SIG_LENGTH, cfg.SIG_OVERLAP, cfg.SIG_MINLEN)
    return chunks

def predict(samples):

    # Prepare sample and pass through model
    data = np.array(samples, dtype='float32')
    prediction = model.predict(data)

    # Logits or sigmoid activations?
    if cfg.APPLY_SIGMOID:
        prediction = model.flat_sigmoid(np.array(prediction), sensitivity=-cfg.SIGMOID_SENSITIVITY)

    return prediction

def analyzeFile(item):

    # Get file path and restore cfg
    fpath = item[0]
    cfg.setConfig(item[1])

    # Start time
    start_time = datetime.datetime.now()

    # Status
    print('Analyzing {}'.format(fpath), flush=True)
    
    # Create the spectrogram for currently analyzing
    spec_name = 'currently_analyzing.png'
    spec_path = os.path.join(cfg.RECS_DIR, spec_name)
    print(spec_path)
    os.system("sox '"+fpath+"' -n remix 1 rate 24k spectrogram -a -w Hann -p 2 -t 'Currently Analyzing' -c '"+fpath+"' -o '"+spec_path+"'")


    # Open audio file and split into 3-second chunks
    chunks = getRawAudioFromFile(fpath)

    # If no chunks, show error and skip
    if len(chunks) == 0:
        msg = 'Error: Cannot open audio file {}'.format(fpath)
        print(msg, flush=True)
        writeErrorLog(msg)
        return False

    # Process each chunk
    try:
        start, end = 0, cfg.SIG_LENGTH
        results = {}
        samples = []
        timestamps = []
        for c in range(len(chunks)):

            # Add to batch
            samples.append(chunks[c])
            timestamps.append([start, end])

            # Advance start and end
            start += cfg.SIG_LENGTH - cfg.SIG_OVERLAP
            end = start + cfg.SIG_LENGTH

            # Check if batch is full or last chunk        
            if len(samples) < cfg.BATCH_SIZE and c < len(chunks) - 1:
                continue

            # Predict
            p = predict(samples)
            # Add to results
            for i in range(len(samples)):

                # Get timestamp
                s_start, s_end = timestamps[i]

                # Get prediction
                pred = p[i]

                # Assign scores to labels
                p_labels = dict(zip(cfg.LABELS, pred))

                # Sort by score
                p_sorted =  sorted(p_labels.items(), key=operator.itemgetter(1), reverse=True)

                # Store top 5 results and advance indicies
                results[str(s_start) + '-' + str(s_end)] = p_sorted

                # Print top two predictions per chunk
                print(str(s_start)+","+str(s_end),"=", p_sorted[:2])

            # Clear batch
            samples = []
            timestamps = []  
    except:
        # Print traceback
        print(traceback.format_exc(), flush=True)

        # Write error log
        msg = 'Error: Cannot analyze audio file {}.\n{}'.format(fpath, traceback.format_exc())
        print(msg, flush=True)
        writeErrorLog(msg)
        return False     

    # Save as selection table
    try:

        # We have to check if output path is a file or directory
        if not cfg.OUTPUT_PATH.rsplit('.', 1)[-1].lower() in ['txt', 'csv']:

            rpath = fpath.replace(cfg.INPUT_PATH, '')
            rpath = rpath[1:] if rpath[0] in ['/', '\\'] else rpath

            # Make target directory if it doesn't exist
            rdir = os.path.join(cfg.OUTPUT_PATH, os.path.dirname(rpath))
            if not os.path.exists(rdir):
                os.makedirs(rdir, exist_ok=True)

            if cfg.RESULT_TYPE == 'table':
                rtype = '.BirdNET.selection.table.txt' 
            elif cfg.RESULT_TYPE == 'audacity':
                rtype = '.BirdNET.results.txt'
            else:
                rtype = '.BirdNET.results.csv'
            saveResultFile(results, os.path.join(cfg.OUTPUT_PATH, rpath.rsplit('.', 1)[0] + rtype), fpath)
            saveSQLFile(results, os.path.join(cfg.OUTPUT_PATH, rpath.rsplit('.', 1)[0] + rtype), fpath)
        else:
            saveResultFile(results, cfg.OUTPUT_PATH, fpath)
            saveSQLFile(results, cfg.OUTPUT_PATH, fpath)        
    except:

        # Print traceback
        print(traceback.format_exc(), flush=True)

        # Write error log
        msg = 'Error: Cannot save result for {}.\n{}'.format(fpath, traceback.format_exc())
        print(msg, flush=True)
        writeErrorLog(msg)
        return False

    delta_time = (datetime.datetime.now() - start_time).total_seconds()
    print('Finished {} in {:.2f} seconds'.format(fpath, delta_time), flush=True)

    return True

if __name__ == '__main__':

    # Freeze support for excecutable
    freeze_support()

    # Clear error log
    #clearErrorLog()

    # Parse arguments
    parser = argparse.ArgumentParser(description='Analyze audio files with BirdNET')
    parser.add_argument('--i', default='example/', help='Path to input file or folder. If this is a file, --o needs to be a file too.')
    parser.add_argument('--o', default='example/', help='Path to output file or folder. If this is a file, --i needs to be a file too.')
    parser.add_argument('--lat', type=float, default=-1, help='Recording location latitude. Set -1 to ignore.')
    parser.add_argument('--lon', type=float, default=-1, help='Recording location longitude. Set -1 to ignore.')
    parser.add_argument('--week', type=int, default=-1, help='Week of the year when the recording was made. Values in [1, 48] (4 weeks per month). Set -1 for year-round species list.')
    parser.add_argument('--slist', default='', help='Path to species list file or folder. If folder is provided, species list needs to be named \"species_list.txt\". If lat and lon are provided, this list will be ignored.')
    parser.add_argument('--sensitivity', type=float, default=1.0, help='Detection sensitivity; Higher values result in higher sensitivity. Values in [0.5, 1.5]. Defaults to 1.0.')
    parser.add_argument('--min_conf', type=float, default=0.1, help='Minimum confidence threshold. Values in [0.01, 0.99]. Defaults to 0.1.')
    parser.add_argument('--overlap', type=float, default=0.0, help='Overlap of prediction segments. Values in [0.0, 2.9]. Defaults to 0.0.')
    parser.add_argument('--rtype', default='table', help='Specifies output format. Values in [\'table\', \'audacity\', \'r\', \'csv\']. Defaults to \'table\' (Raven selection table).')
    parser.add_argument('--threads', type=int, default=4, help='Number of CPU threads.')
    parser.add_argument('--batchsize', type=int, default=1, help='Number of samples to process at the same time. Defaults to 1.')
    parser.add_argument('--locale', default='en', help='Locale for translated species common names. Values in [\'af\', \'de\', \'it\', ...] Defaults to \'en\'.')
    parser.add_argument('--sf_thresh', type=float, default=0.03, help='Minimum species occurrence frequency threshold for location filter. Values in [0.01, 0.99]. Defaults to 0.03.')

    args = parser.parse_args()

    # Set paths relative to script path (requested in #3)
    cfg.MODEL_PATH = os.path.join(os.path.dirname(os.path.abspath(sys.argv[0])), cfg.MODEL_PATH)
    cfg.LABELS_FILE = os.path.join(os.path.dirname(os.path.abspath(sys.argv[0])), cfg.LABELS_FILE)
    cfg.TRANSLATED_LABELS_PATH = os.path.join(os.path.dirname(os.path.abspath(sys.argv[0])), cfg.TRANSLATED_LABELS_PATH)
    cfg.MDATA_MODEL_PATH = os.path.join(os.path.dirname(os.path.abspath(sys.argv[0])), cfg.MDATA_MODEL_PATH)
    cfg.CODES_FILE = os.path.join(os.path.dirname(os.path.abspath(sys.argv[0])), cfg.CODES_FILE)
    cfg.ERROR_LOG_FILE = os.path.join(os.path.dirname(os.path.abspath(sys.argv[0])), cfg.ERROR_LOG_FILE)
    cfg.RECS_DIR = os.path.join(os.path.dirname(os.path.abspath(sys.argv[0])), cfg.RECS_DIR)
    cfg.ANALYZED_DIR = os.path.join(os.path.dirname(os.path.abspath(sys.argv[0])), cfg.ANALYZED_DIR)
    cfg.SEGMENTS_DIR = os.path.join(os.path.dirname(os.path.abspath(sys.argv[0])), cfg.SEGMENTS_DIR)
    cfg.STORAGE_DIR = os.path.join(os.path.dirname(os.path.abspath(sys.argv[0])), cfg.STORAGE_DIR)
    cfg.DATABASE_PATH = os.path.join(os.path.dirname(os.path.abspath(sys.argv[0])), cfg.DATABASE_PATH)

    # Load eBird codes, labels
    cfg.CODES = loadCodes()
    cfg.LABELS = loadLabels(cfg.LABELS_FILE)

    # Load translated labels
    lfile = os.path.join(cfg.TRANSLATED_LABELS_PATH, os.path.basename(cfg.LABELS_FILE).replace('.txt', '_{}.txt'.format(args.locale)))
    if not args.locale in ['en'] and os.path.isfile(lfile):
        cfg.TRANSLATED_LABELS = loadLabels(lfile)
    else:
        cfg.TRANSLATED_LABELS = cfg.LABELS   

    ### Make sure to comment out appropriately if you are not using args. ###

    # Load species list from location filter or provided list
    cfg.LATITUDE, cfg.LONGITUDE, cfg.WEEK = args.lat, args.lon, args.week
    cfg.LOCATION_FILTER_THRESHOLD = max(0.01, min(0.99, float(args.sf_thresh)))
    if cfg.LATITUDE == -1 and cfg.LONGITUDE == -1:
        if len(args.slist) == 0:
            cfg.SPECIES_LIST_FILE = None
        else:
            cfg.SPECIES_LIST_FILE = os.path.join(os.path.dirname(os.path.abspath(sys.argv[0])), args.slist)
            if os.path.isdir(cfg.SPECIES_LIST_FILE):
                cfg.SPECIES_LIST_FILE = os.path.join(cfg.SPECIES_LIST_FILE, 'species_list.txt')
        cfg.SPECIES_LIST = loadSpeciesList(cfg.SPECIES_LIST_FILE)
    else:
        predictSpeciesList()
    if len(cfg.SPECIES_LIST) == 0:
        print('Species list contains {} species'.format(len(cfg.LABELS)))
    else:        
        print('Species list contains {} species'.format(len(cfg.SPECIES_LIST)))

    # Set input and output path    
    cfg.INPUT_PATH = args.i
    cfg.OUTPUT_PATH = args.o

    # Parse input files
    if os.path.isdir(cfg.INPUT_PATH):
        cfg.FILE_LIST = parseInputFiles(cfg.INPUT_PATH)  
    else:
        cfg.FILE_LIST = [cfg.INPUT_PATH]

    # Set confidence threshold
    cfg.MIN_CONFIDENCE = max(0.01, min(0.99, float(args.min_conf)))

    # Set sensitivity
    cfg.SIGMOID_SENSITIVITY = max(0.5, min(1.0 - (float(args.sensitivity) - 1.0), 1.5))

    # Set overlap
    cfg.SIG_OVERLAP = max(0.0, min(2.9, float(args.overlap)))

    # Set result type
    cfg.RESULT_TYPE = args.rtype.lower()    
    if not cfg.RESULT_TYPE in ['table', 'audacity', 'r', 'csv']:
        cfg.RESULT_TYPE = 'table'

    # Set number of threads
    if os.path.isdir(cfg.INPUT_PATH):
        cfg.CPU_THREADS = max(1, int(args.threads))
        cfg.TFLITE_THREADS = 1
    else:
        cfg.CPU_THREADS = 1
        cfg.TFLITE_THREADS = max(1, int(args.threads))

    # Set batch size
    cfg.BATCH_SIZE = max(1, int(args.batchsize))

    # Add config items to each file list entry.
    # We have to do this for Windows which does not
    # support fork() and thus each process has to
    # have its own config. USE LINUX!
    flist = []
    for f in cfg.FILE_LIST:
        flist.append((f, cfg.getConfig()))

    # Analyze files   
    if cfg.CPU_THREADS < 2:
        for entry in flist:
            analyzeFile(entry)
    else:
        with Pool(cfg.CPU_THREADS) as p:
            p.map(analyzeFile, flist)


    # A few examples to test
    # python3 analyze.py --i example/ --o example/ --slist example/ --min_conf 0.5 --threads 4
    # python3 analyze.py --i example/soundscape.wav --o example/soundscape.BirdNET.selection.table.txt --slist example/species_list.txt --threads 8
    # python3 analyze.py --i example/ --o example/ --lat 42.5 --lon -76.45 --week 4 --sensitivity 1.0 --rtype table --locale de

