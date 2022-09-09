<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Detection;
use Livewire\WithPagination;

class DetectionsTable extends Component
{
    use WithPagination;
    
    public function render()
    {
        return view('livewire.detections-table', [
            'detections' => Detection::latest()->filter(
                        request(['search', 'file_name'])
                    )->paginate(10)->withQueryString()
        ]);
    }
}
