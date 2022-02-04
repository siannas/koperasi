<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Meta;
use Validator;
use Carbon\Carbon;

class PengaturanController extends Controller
{
    public function index(){
        $data=[];
        $data['datelock']=Meta::where('key','setting_datelock')->pluck('value')->first();
        return view('pengaturan', $data);
    }
    
    public function updateDateLock(Request $request){
        $input = array_map('trim', $request->all());
        $validator = Validator::make($input, [
            'datelock' => 'required|string',
        ]);

        if ($validator->fails()) return back()->with('error','Gagal menyimpan');
        
        $input = $validator->valid();
        $input['datelock']=Carbon::createFromFormat('m/Y',$input['datelock'])->format('Y-m').'-01';
        $datekunci = Meta::where('key','setting_datelock')->first();

        if(isset($datekunci)){
            $datekunci->value = $input['datelock'];
        }else{
            $datekunci = new Meta();
            $datekunci->key = 'setting_datelock';
            $datekunci->value = $input['datelock'];
        }
        $datekunci->save();
        $this->flashSuccess('Data Jurnal Berhasil Diperbarui');
        return back();
    }
}
