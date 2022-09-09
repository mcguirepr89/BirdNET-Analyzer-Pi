<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Detection;
use Illuminate\Support\Facades\DB;

class SpeciesCards extends Component
{
    public function render()
    {
        return view('livewire.species-cards', [
            'total_detections' => \App\Models\Detection::count(),
            'species_stats' => DB::table('detections')
                                ->select('com_name', 'sci_name', DB::raw('MAX("confidence") as confidence'), 'file_name', DB::raw('COUNT(*) as species_total'))
                                ->groupBy('com_name')
                                ->orderByRaw('COUNT(*) DESC, confidence DESC')
                                ->get(),
                ]);
    }
}
