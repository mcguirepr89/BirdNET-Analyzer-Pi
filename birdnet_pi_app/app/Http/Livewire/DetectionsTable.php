<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Detection;

class DetectionsTable extends Component
{
    public function render()
    {
        return view('livewire.detections-table', [
            'detections' => Detection::latest()->paginate(10),
        ]);
    }
}
