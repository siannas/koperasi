<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use App\Meta;
use App\Tipe;

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

    /**
     * @return null|array(
     *      "visible_categories" => array(1,2,3,4)
     * )
     */
    public static function GetNeracaConfig($key)
    {
        if (is_numeric($key)) {
            switch ($key) {
                case 2:
                    $key = Tipe::FC;
                    break;
                case 3:
                    $key = Tipe::TK;
                    break;
                case 4:
                    $key = Tipe::PU;
                    break;
                default:
                    $key = Tipe::SP;
                    break;
            }
        }
        $config = Meta::where('key', 'LIKE', $key . '_neraca_config')->first();
        if (is_null($config)) {
            return null;
        } else {
            return json_decode($config->value, true);
        }
    }
}
