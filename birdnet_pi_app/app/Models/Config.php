<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
class Config extends Model
{
    use HasFactory;
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::updated(function ($config) {
            $process = new Process(['sudo', 'systemctl', 'restart', 'birdnet_analysis', 'birdnet_recording', 'birdnet_logs', 'weather']);
            $process->run();

            // executes after the command finishes
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $process->getOutput();
        });
    }
}
