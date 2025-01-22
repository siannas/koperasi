<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use App\Akun;
use App\AkunNeracaVisibility;
use App\Tipe;
use App\Kategori;

class AkunNeracaVisibilityController extends Controller
{
    public function index(Request $request){
        $idTipe = $request->input('tipe');
        $types = Tipe::all();
        $res = ['types' => $types];
        $res['idTipe'] = null;
        $res['aset'] = [];
        $res['beban'] = [];
        if (!is_null($idTipe)) {
            $idTipe = intval($idTipe);
            # get neraca config
            $visibleCategories = null;
            $config = MetaController::GetNeracaConfig($idTipe);
            if ($config) {
                $visibleCategories = $config['visible_categories'];
            }
            $visibility = self::GetMap($this->year, $idTipe);
            $categories = Kategori::with(['getAkun' => function($q) {
                    $q->where('status', '=', 1)
                    ->orderBy('no-akun','ASC');
                }])
                ->where('parent', '<>', Kategori::SHU)
                ->whereNotNull('tipe-pendapatan')
                ->orderBy('priority','ASC')
                ->get();
            # plot
            $aset = [];
            $beban = [];
            foreach ($categories as $category) {
                # filter visible categories
                if (!is_null($visibleCategories) && !in_array($category->id, $visibleCategories)) {
                    continue;
                }
                $childs = [];
                foreach ($category->getAkun as $akun) {
                    $show = true;
                    if (
                        !is_null($visibility) &&
                        array_key_exists($akun->{'id'}, $visibility) &&
                        $visibility[$akun->{'id'}]['show'] != 1
                    ) {
                        $show = false;
                    }
                    $childs[] = [
                        'id' => $akun->id,
                        'name' => $akun->{'no-akun'} . ' - ' . $akun->{'nama-akun'},
                        'show' => $show,
                    ];
                }
                if (empty($childs)) {
                    continue;
                }
                $cat = [
                    'id' => $category->id,
                    'name' => $category->kategori,
                    'data' => $childs,
                ];
                if ($category['tipe-pendapatan']  == 'debit') {
                    $aset[] = $cat;
                } else {
                    $beban[] = $cat;
                }
            }
            $res['aset'] = $aset;
            $res['beban'] = $beban;
            $res['idTipe'] = $idTipe;
        }
        return view('akun_neraca_visibility', $res);
    }

    public function update(Request $request){
        $akun = Akun::orderBy('no-akun', 'ASC')->get();
        $idTipe = intval($request->input('idtipe'));
        $toshow = $request->input('v');
        $data = [];
        foreach ($akun as $a) {
            $isShow = true;
            if (!array_key_exists($a->id, $toshow)) {
                $isShow = false;
            }
            $data[] = [
                'id-akun' => $a->id,
                'id-tipe' => $idTipe,
                'year' => $this->year,
                'show' => $isShow,
            ];
        }
        AkunNeracaVisibility::upsert(
            $data, 
            ['id-akun', 'id-tipe', 'year'],
            ['show']
        );
        $this->flashSuccess('Berhasil Menyimpan');
        return back();
    }

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
