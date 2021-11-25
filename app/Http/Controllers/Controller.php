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
