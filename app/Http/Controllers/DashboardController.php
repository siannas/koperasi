<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Meta;
use App\User;
use Hash;

class DashboardController extends Controller
{
    public function dashboard(){
        $meta = Meta::all();

        return view('dashboard', ['meta'=>$meta]);
    }

    public function index(){
        $user = User::select('id', 'username', 'nama', 'nip', 'role')->get();
        foreach($user as $unit){
            $unit->role = explode(', ', $unit->role);
        }
        return view('user', ['user'=>$user]);
    }

    public function store(Request $request){
        $user_baru = new User();
        $user_baru->fill($request->all());
        $user_baru->password = Hash::make($request->username);
        $user_baru->role = implode(', ', $user_baru->role);
        
        $user_baru->save();
        
        $this->flashSuccess('Data User Berhasil Ditambahkan');
        return back();
    }

    public function update(Request $request, $id){
        try {
            $user = User::findOrFail($id);
            $user->fill($request->all());
            $user->password = Hash::make($request->username);
            $user->role = implode(', ', $user->role);
            
            $user->save();
        }catch (QueryException $exception) {
            $this->flashError($exception->getMessage());
            return back();
        }

        $this->flashSuccess('Data User Berhasil Diperbarui');
        return back();
    }

    public function destroy($id){
        try {
            $user = User::findOrFail($id);
            $user->delete();
        }catch (QueryException $exception) {
            $this->flashError($exception->getMessage());
            return back();
        }
        
        $this->flashSuccess('Data User Berhasil Dihapus');
        return back();
    }
}
