<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use App\AkunNeracaVisibility;

class AkunNeracaVisibilityController extends Controller
{
    public static function GetMap($year, $idTipe)
    {
        $data = AkunNeracaVisibility::where('year', '=', $year)
            ->when(!is_null($idTipe), function($q) use ($idTipe) {
                $q->where('id-tipe', '=', $idTipe);
            })
            ->get();
        if (is_null($data)) {
            return [];
        } else {
            return array_combine($data->pluck('id-akun')->toArray(), $data->toArray());
        }
    }
}
