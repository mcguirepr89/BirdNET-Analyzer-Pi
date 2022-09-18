<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDetectionRequest;
use App\Http\Requests\UpdateDetectionRequest;
use App\Models\Detection;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;


class DetectionController extends Controller
{
    use WithPagination;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //API
        if (strpos(url()->current(), 'api')) {
            return Detection::latest()->get();
	}
	else
        //API
	{
        // Livewire fetches the data for this view
        return view('detections');
	}
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function species()
    {
        //API
        if (strpos(url()->current(), 'api')) {
            $species_stats = DB::table('detections')
                ->select('com_name', 'sci_name', DB::raw('MAX("confidence") as highest_confidence'), DB::raw('COUNT(*) as species_total'))
                ->groupBy('com_name')
                ->orderByRaw('COUNT(*) DESC, confidence DESC')
                ->get();
            return $species_stats;
        }
        else
        {
        // Livewire fetches the data for this view
        return view('species');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDetectionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDetectionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Detection  $detection
     * @return \Illuminate\Http\Response
     */
    public function show_com(Detection $detection)
    {
        //API
        if (strpos(url()->current(), 'api')) {
            $species = Detection::where('com_name', $detection->com_name)->get();
            return $species;
        }
        else
        //View
        {
            $species_bytime = Detection::where('com_name', $detection->com_name)->orderByDesc('created_by')->paginate(18);
            // dump($species_bytime);die;
            $species_byconf = Detection::where('com_name', $detection->com_name)->orderByDesc('confidence')->paginate(18);
            // dump($species_byconf);die;

            return view('by-species', [ 
                'species_bytime' => $species_bytime,
                'species_byconf' => $species_byconf,
            ]);
        }
    }
    public function show_sci(Detection $detection)
    {
        //API
        if (strpos(url()->current(), 'api')) {
            $species = Detection::where('sci_name', $detection->sci_name)->get();
            return $species;
        }
        else
        //View
        {
            $species = Detection::where('sci_name', $detection->sci_name)->get();
            return view('by-species', [ 'species' => $species ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Detection  $detection
     * @return \Illuminate\Http\Response
     */
    public function edit(Detection $detection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDetectionRequest  $request
     * @param  \App\Models\Detection  $detection
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDetectionRequest $request, Detection $detection)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Detection  $detection
     * @return \Illuminate\Http\Response
     */
    public function destroy(Detection $detection)
    {
        //
    }
}
