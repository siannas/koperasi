<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\View;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $date;
    protected $year;

    /**
     * Instantiate a new Controller instance.
     */
   public function __construct()
   {
      $d=date('d');
      $m=date('m');
      $Y=date('Y');
      $this->date=[
         'date'=>date_create($Y.'-'.$m.'-'.$d),
         'd'=> intval($d),
         'm'=>intval($m),
         'y'=>intval($Y),
      ];
      $this->year = date('Y');
      if (request()->cookie('tahun')) {
         $this->year = request()->cookie('tahun');
      }
      View::share('year', $this->year);
   }

    public function flashSuccess($message) {
     $this->setupFlash('check', $message, '3');
  }

  public function flashError($message) {
     $this->setupFlash('close', $message, '2');
  }

  private function setupFlash($icon, $message, $type) {
     request()->session()->flash('alert', [
        'icon' => $icon,
        'message' => $message,
        'status' => $type,
     ]);
  }
}
