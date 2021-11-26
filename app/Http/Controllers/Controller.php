<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

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
