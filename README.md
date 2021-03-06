<h1 align="center">BirdNET-Analyzer</h1>
<p align="center">Automated scientific audio data processing and bird ID.</p>
<p align="center"><img src="https://tuc.cloud/index.php/s/xwKqoCmRDKzBCDZ/download/logo_box_birdnet.png" width="500px" /></p>

[![CC BY-NC-SA 4.0][license-badge]][cc-by-nc-sa] 
![Supported OS][os-badge]
![Number of species][species-badge]

[license-badge]: https://badgen.net/badge/License/CC-BY-NC-SA%204.0/green
[os-badge]: https://badgen.net/badge/OS/Linux%2C%20Windows/blue
[species-badge]: https://badgen.net/badge/Species/2424/blue


## Introduction
This repo contains BirdNET models and scripts for processing large amounts of audio data or single audio files. This is the most advanced version of BirdNET for acoustic analyses and we will keep this repository up-to-date with new models and improved interfaces to enable scientists with no CS background to run the analysis.

Feel free to use BirdNET for your acoustic analyses and research. If you do, please cite as:

```
@article{kahl2021birdnet,
  title={BirdNET: A deep learning solution for avian diversity monitoring},
  author={Kahl, Stefan and Wood, Connor M and Eibl, Maximilian and Klinck, Holger},
  journal={Ecological Informatics},
  volume={61},
  pages={101236},
  year={2021},
  publisher={Elsevier}
}
```

This work is licensed under a
[Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License][cc-by-nc-sa].

[cc-by-nc-sa]: http://creativecommons.org/licenses/by-nc-sa/4.0/

## About

Developed by the [K. Lisa Yang Center for Conservation Bioacoustics](https://www.birds.cornell.edu/ccb/) at the [Cornell Lab of Ornithology](https://www.birds.cornell.edu/home).

Go to https://birdnet.cornell.edu to learn more about the project.

Follow us on Twitter [@BirdNET_App](https://twitter.com/BirdNET_App).

Want to use BirdNET to analyze a large dataset? Don't hesitate to contact us: ccb-birdnet@cornell.edu

<b>Have a question, remark or feature request? Please start a new issue thread to let us know. Feel free to submit pull request.</b>

## Contents

[Model version update](#model-version-update)
[Usage](#usage)  
[Usage (Server)](#usage-server)   
[Funding](#funding)  
[Partners](#partners)

## Model version update

**V2.1, Apr 2022**

- same model architecture as V2.0
- extended 2022 training data
- global selection of species (birds and non-birds) with 2,434 classes (incl. 10 non-event classes)

You can find a list of previous versions here: [BirdNET-Analyzer Model Version History](https://github.com/kahst/BirdNET-Analyzer/tree/main/checkpoints)

## Setup Raspberry Pi RaspiOS-ARM64-Lite for testing

```
curl -s https://raw.githubusercontent.com/mcguirepr89/BirdNET-Analyzer-Pi/main/install_birdnetpi.sh | bash
```

## Usage

1. Inspect config file for options and settings, especially inference settings. Specify a custom species list if needed and adjust the number of threads TFLite can use to run the inference.

2. Run `analyzer.py` to analyze an audio file. You need to set paths for the audio file and selection table output. Here is an example:

```
./analyze.py --i /path/to/audio/folder --o /path/to/output/folder
```

<b>NOTE</b>: Your custom species list has to be named 'species_list.txt' and the folder containing the list needs to be specified with `--slist /path/to/folder`. You can also specify the number of CPU threads that should be used for the analysis with `--threads <Integer>` (e.g., `--threads 16`). If you provide GPS coordinates with `--lat` and `--lon`, the custom species list argument will be ignored.

Here's a complete list of all command line arguments:

```
--i, Path to input file or folder. If this is a file, --o needs to be a file too.
--o, Path to output file or folder. If this is a file, --i needs to be a file too.
--lat, Recording location latitude. Set -1 to ignore.
--lon, Recording location longitude. Set -1 to ignore.
--week, Week of the year when the recording was made. Values in [1, 48] (4 weeks per month). Set -1 for year-round species list.
--slist, Path to species list file or folder. If folder is provided, species list needs to be named "species_list.txt". If lat and lon are provided, this list will be ignored.
--sensitivity, Detection sensitivity; Higher values result in higher sensitivity. Values in [0.5, 1.5]. Defaults to 1.0.
--min_conf, Minimum confidence threshold. Values in [0.01, 0.99]. Defaults to 0.1.
--overlap, Overlap of prediction segments. Values in [0.0, 2.9]. Defaults to 0.0.
--rtype, Specifies output format. Values in ['table', 'audacity', 'r', 'csv']. Defaults to 'table' (Raven selection table).
--threads, Number of CPU threads.
--batchsize, Number of samples to process at the same time. Defaults to 1.
--locale, Locale for translated species common names. Values in ['af', 'de', 'it', ...] Defaults to 'en'.
--sf_thresh, Minimum species occurrence frequency threshold for location filter. Values in [0.01, 0.99]. Defaults to 0.03.
```

Here are two example commands to run this BirdNET version:

```
./analyze.py --i example/ --o example/ --slist example/ --min_conf 0.5 --threads 4

./analyze.py --i example/ --o example/ --lat 42.5 --lon -76.45 --week 4 --sensitivity 1.0
```

3. Run `embeddings.py` to extract feature embeddings instead of class predictions. Result file will contain timestamps and lists of float values representing the embedding for a particular 3-second segment. Embeddings can be used for clustering or similarity analysis. Here is an example:

```
./embeddings.py --i example/ --o example/ --threads 4 --batchsize 16
```

Here's a complete list of all command line arguments:

```
--i, Path to input file or folder. If this is a file, --o needs to be a file too.
--o, Path to output file or folder. If this is a file, --i needs to be a file too.
--threads, Number of CPU threads.
--batchsize, Number of samples to process at the same time. Defaults to 1.
```

4. After the analysis, run `segments.py` to extract short audio segments for species detections to verify results. This way, it might be easier to review results instead of loading hundreds of result files manually.

Here's a complete list of all command line arguments:

```
--audio, Path to folder containing audio files.
--results, Path to folder containing result files.
--o, Output folder path for extracted segments.
--min_conf, Minimum confidence threshold. Values in [0.01, 0.99]. Defaults to 0.1.
--max_segments, Number of randomly extracted segments per species.
--seg_length, Length of extracted segments in seconds. Defaults to 3.0.
--threads, Number of CPU threads.
```

5. When editing your own `species_list.txt` file, make sure to copy species names from the labels file of each model. 

You can find label files in the checkpoints folder, e.g., `checkpoints/V2.1/BirdNET_GLOBAL_2K_V2.1_Labels.txt`. 

Species names need to consist of `scientific name_common name` to be valid.

6. You can generate a species list for a given location using `species.py` in case you need it for reference. Here is an example:

```
./species.py --o example/species_list.txt --lat 42.5 --lon -76.45 --week 4
```

Here's a complete list of all command line arguments:

```
--o, Path to output file or folder. If this is a folder, file will be named 'species_list.txt'.
--lat, Recording location latitude. Set -1 to ignore.
--lon, Recording location longitude. Set -1 to ignore.
--week, Week of the year when the recording was made. Values in [1, 48] (4 weeks per month). Set -1 for year-round species list.
--threshold, Occurrence frequency threshold. Defaults to 0.05.
--sortby, Sort species by occurrence frequency or alphabetically. Values in ['freq', 'alpha']. Defaults to 'freq'.
```

7. This is a very basic version of the analysis workflow, you might need to adjust it to your own needs.

8. Please open an issue to ask for new features or to document unexpected behavior.

9. I will keep models up to date and upload new checkpoints whenever there is an improvement in performance. I will also provide quantized and pruned model files for distribution.

## Usage (Server)

You can host your own analysis service and API by launching the `server.py` script. This will allow you to send files to this server, store submitted files, analyse them and send detection results back to a client. This could be a local service, running on a desktop PC, or a remote server. The API can be accessed locally or remotely through a browser or Python client (or any other client implementation).

1. Install one additional package with `pip3 install bottle`.

2. Start the server with `python3 server.py`. You can also specify a host name or IP and port number, e.g., `python3 server.py --host localhost --port 8080`.

Here's a complete list of all command line arguments:

```
--host, Host name or IP address of API endpoint server. Defaults to '0.0.0.0'.
--port, Port of API endpoint server. Defaults to 8080.    
--spath, Path to folder where uploaded files should be stored. Defaults to '/uploads'.
--threads, Number of CPU threads for analysis. Defaults to 4.
--locale, Locale for translated species common names. Values in ['af', 'de', 'it', ...] Defaults to 'en'.
```

<b>NOTE</b>: The server is single-threaded, so you'll need to start multiple instances for higher throughput. This service is intented for short audio files (e.g., 1-10 seconds).

3. Query the API with a client. You can use the provided Python client or any other client implementation. Request payload needs to be `multipart/form-data` with the following fields: `audio` for raw audio data as byte code, and `meta` for additional information on the audio file. Take a look at our example client implementation in the `client.py` script.

This script will read an audio file, generate metadata from command line arguments and send it to the server. The server will then analyze the audio file and send back the detection results which will be stored as a JSON file.

Here's a complete list of all command line arguments:

```
--host, Host name or IP address of API endpoint server.
--port, Port of API endpoint server.     
--i, Path to file that should be analyzed.  
--o, Path to result file. Leave blank to store with audio file.  
--lat, Recording location latitude. Set -1 to ignore.
--lon, Recording location longitude. Set -1 to ignore.
--week, Week of the year when the recording was made. Values in [1, 48] (4 weeks per month). Set -1 for year-round species list.
--overlap, Overlap of prediction segments. Values in [0.0, 2.9]. Defaults to 0.0.
--sensitivity, Detection sensitivity; Higher values result in higher sensitivity. Values in [0.5, 1.5]. Defaults to 1.0.
--pmode, Score pooling mode. Values in ['avg', 'max']. Defaults to 'avg'. 
--num_results, Number of results per request.
--sf_thresh, Minimum species occurrence frequency threshold for location filter. Values in [0.01, 0.99]. Defaults to 0.03.
--save, Define if files should be stored on server. Values in [True, False]. Defaults to False.  
```

4. Parse results from the server. The server will send back a JSON response with the detection results. The response also contains a `msg` field, indicating `success` or `error`. Results consist of a sorted list of (species, score) tuples.

This is an example response:

```
{"msg": "success", "results": [["Poecile atricapillus_Black-capped Chickadee", 0.7889], ["Spinus tristis_American Goldfinch", 0.5028], ["Junco hyemalis_Dark-eyed Junco", 0.4943], ["Baeolophus bicolor_Tufted Titmouse", 0.4345], ["Haemorhous mexicanus_House Finch", 0.2301]]}
```

<b>NOTE</b>: Let us know if you have any questions, suggestions, or feature requests. Also let us know when hosting an analysis service - we would love to give it a try.

## Funding

This project is supported by Jake Holshuh (Cornell class of ???69) and The Arthur Vining Davis Foundations. Our work in the K. Lisa Yang Center for Conservation Bioacoustics is made possible by the generosity of K. Lisa Yang to advance innovative conservation technologies to inspire and inform the conservation of wildlife and habitats.

The European Union and the European Social Fund for Germany partially funded this research. This work was also partially funded by the German Federal Ministry of Education and Research in the program of Entrepreneurial Regions InnoProfileTransfer in the project group localizeIT (funding code 03IPT608X).

## Partners

BirdNET is a joint effort of partners from academia and industry. Without these partnerships, this project would not have been possible. Thank you!

![Logos of all partners](https://tuc.cloud/index.php/s/KSdWfX5CnSRpRgQ/download/box_logos.png)
