<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDetectionRequest;
use App\Http\Requests\UpdateDetectionRequest;
use App\Models\Detection;

class DetectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	if (strpos(url()->current(), 'api')) {
		return Detection::latest()->get();
	}
	else
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
        // Livewire fetches the data for this view
        return view('species');
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
    public function show(Detection $detection)
    {
        //
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
