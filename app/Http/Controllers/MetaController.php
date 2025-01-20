<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use App\Meta;

class MetaController extends Controller
{
    public function fetchEnvByYear(int $year)
    {
        try {
            $env = Meta::where('key', '=', strval($year) . '_env')->first();
            if (is_null($env)) {
                $this->copyEnvByYear($year - 1, $year);
            }
            $this->saveEnvToFile();
            // $this->recompileConfig();
        } catch (\Throwable $th) {
            $this->flashError("Please Try Again");
            return back();
        }
    }

    private function copyEnvByYear(int $yearToCopy, int $year)
    {
        $existing = Meta::where('key', '=', strval($yearToCopy) . '_env')->first();
        if ($existing) {
            $new = $existing->replicate();
            $new->key = strval($year) . '_env';
            $new->save();
        }
        return $existing;
    }

    private function saveEnvToFile()
    {
        $existings = Meta::where('key', 'LIKE', '%_env')->get();
        $config = [];
        foreach ($existings as $e) {
            $key = substr($e->key,0,4) . '_';
            $json = json_decode($e->value);
            foreach ($json as $k => $v) {
                $config[$key . $k] = $v;
            }
        }
        Storage::put('config.json', json_encode($config));
    }

    private function recompileConfig()
    {
        Artisan::call('config:cache');
    }
}
