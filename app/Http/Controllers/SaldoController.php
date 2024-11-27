<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Jurnal;
use App\Saldo;

class SaldoController extends Controller
{
    public static function GenerateSaldoByMonth(int $month, int $year, string $isValidated0 = 'true') {
        $isValidated = ($isValidated0 == 'true');
        $monthLiteral = sprintf("%02d", $month);
        $subQuery = function($to) { return ;
        };
        
        $jurnalRecap = function($from, $to) use ($year, $monthLiteral, $isValidated) { return (Jurnal::select(
                DB::Raw("`id-{$from}` as `id-akun`"),
                "jurnal.id-tipe",
                ($from == 'debit') ? DB::Raw("SUM({$from}) - IFNULL(d.saldo, 0) as saldo") : 
                    DB::Raw("IFNULL(d.saldo, 0) - SUM({$from}) as saldo"),
                DB::Raw("\"{$year}-{$monthLiteral}-01\" as tanggal")
            )
            ->leftJoinSub(
                Jurnal::select(
                    DB::Raw("`id-{$to}` as `id-akun`"),
                    'id-tipe',
                    DB::Raw("SUM({$to}) as saldo"),
                )
                ->when($isValidated == true, function($q) {
                    $q->where('validasi', '=', DB::Raw(1));
                })
                ->whereMonth('tanggal', $monthLiteral)
                ->whereYear('tanggal', $year)
                ->groupBy('id-tipe', 'id-' . $to), 
                'd', 
                function($join) use ($from) {
                    $join->on('d.id-tipe', '=', 'jurnal.id-tipe');
                    $join->on('d.id-akun', '=', 'jurnal.id-' . $from);
                }
            )
            ->when($isValidated == true, function($q) {
                $q->where('validasi', '=', DB::Raw(1));
            })
            ->whereMonth('tanggal', $monthLiteral)
            ->whereYear('tanggal', $year)
            ->groupBy('jurnal.id-tipe', 'jurnal.id-' . $from));
        };
        $left = $jurnalRecap('debit', 'kredit')->get()->toBase();
        $right = $jurnalRecap('kredit', 'debit')->get()->toBase();
        $merged = $left->merge($right)->unique();
        Saldo::upsert(
            $merged->toArray(),
            ['id-akun', 'id-tipe', 'tanggal'],
            ['saldo'],
        );
        return response()->json(["status" => 200, "msg" => "oke"]);
    }

    public static function GenerateSaldoByYear(int $year, string $isValidated0 = 'true') {
        for ($i=1; $i <= 12; $i++) { 
            self::GenerateSaldoByMonth($i, $year, $isValidated0);
        }
        return response()->json(["status" => 200, "msg" => "oke year"]);
    }
}