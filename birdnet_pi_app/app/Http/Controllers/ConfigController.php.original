<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConfigController extends Controller
{

    public function show()
    {
        return view('config.show');
    }


    public function edit()
    {
        return view('config.edit');
    }


    public function form()
    {
        $settings = parse_ini_string(shell_exec('reformat_config.sh -i /home/birder/BirdNET-Analyzer-Pi/config.py'), true);
        return view('config.form', [
            'settings' => $settings
        ]);
    }

    public function write_config()
    {
        dump(request()->all());
    }
}