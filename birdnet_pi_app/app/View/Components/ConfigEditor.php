<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Config;

class ConfigEditor extends Component
{
    public $configVars;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $this->configVars = Config::all()->toArray();
        return view('components.config-editor');
    }
}
