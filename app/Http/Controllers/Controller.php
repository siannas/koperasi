<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $date;
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
   }

    public function flashSuccess($message) {
     $this->setupFlash("Operation Successful", $message, 'success');
  }

  public function flashError($message) {
     $this->setupFlash("Something went wrong", $message, 'error');
  }

  private function setupFlash($title, $message, $type) {
     request()->session()->flash('alert', [
        'title' => $title,
        'message' => $message,
        'type' => $type,
     ]);
  }
}
