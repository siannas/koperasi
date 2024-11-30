<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Akun;
use App\Kategori;

class ConfigController extends Controller
{
    public function index()
    {
        $categories = Kategori::with(['getAkun' => function($q) {
            $q->where('status', '=', 1);
        }])
        ->whereNotNull('tipe-pendapatan')
        ->orderBy('priority','ASC')
        ->get();
        if (is_null($categories)) {
            $categories = [];
        }

        $categoriesId = [];
        if ($categories) {
            $categoriesId = $categories->pluck('id');
        }

        # don't have category
        $orphans = Akun::select('id', DB::Raw("CONCAT(`no-akun` , ' - ', `nama-akun`) as name"))
            ->where('status', '=', 1)
            ->where(function ($query) use ($categoriesId) {
                $query->whereNotIn('id-kategori', $categoriesId)
                ->orWhereNull('id-kategori');
            })
            ->get();
        if (is_null($orphans)) {
            $orphans = [];
        } else {
            $orphans = $orphans->toArray();
        }

        $left = []; $right = [];
        foreach ($categories as $category) {
            $childs = [];
            foreach ($category->getAkun as $akun) {
                $childs[] = [
                    'id' => $akun->id,
                    'name' => $akun->{'no-akun'} . ' - ' . $akun->{'nama-akun'},
                ];
            }
            $cat = [
                'id' => $category->id,
                'name' => $category->kategori,
                'data' => $childs,
            ];
            if ($category['tipe-pendapatan']  == 'debit') {
                $left[] = $cat;
            } else {
                $right[] = $cat;
            }
        }
        //tipe-pendapatan
        return view('configNeraca', ['left' => $left, 'right' => $right, 'orphans' => $orphans]);
    }

    public function update(Request $req)
    {
        $input = $req->all();
        $akunMap = $input['akun'];
        $data = [];

        DB::beginTransaction();
        try{
            # update kategori for modal
            $prio = 1;
            foreach ($input['category']['modal'] as $id) {
                $id = intval($id);
                Kategori::where('id', '=', $id)->update([
                    'tipe-pendapatan' => 'debit',
                    'priority' => $prio++,
                ]);
                if (isset($akunMap[$id]) == false) {
                    continue;
                }
                foreach ($akunMap[$id] as $idAkun) {
                    Akun::where('id', '=', $idAkun)->update([
                        'id-kategori' => $id
                    ]);
                }
            }
            # update kategori for beban
            $prio = 1;
            foreach ($input['category']['beban'] as $id) {
                $id = intval($id);
                Kategori::where('id', '=', $id)->update([
                    'tipe-pendapatan' => 'kredit',
                    'priority' => $prio++,
                ]);
                if (isset($akunMap[$id]) == false) {
                    continue;
                }
                foreach ($akunMap[$id] as $idAkun) {
                    Akun::where('id', '=', $idAkun)->update([
                        'id-kategori' => $id
                    ]);
                }
            }
            # save for orphan
            if (isset($akunMap['orphan'])) {
                foreach ($akunMap['orphan'] as $idAkun) {
                    Akun::where('id', '=', $idAkun)->update([
                        'id-kategori' => 0
                    ]);
                }
            }
            DB::commit();
        }catch(QueryException $exception){
            DB::rollBack();
            $this->flashError($exception->getMessage());
            return back();
        }
        $this->flashSuccess('Berhasil Menyimpan');
        return back();
    }
}
