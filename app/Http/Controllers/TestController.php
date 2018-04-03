<?php

namespace App\Http\Controllers;

use App\Test;
use Illuminate\Http\Request;

class TestController extends Controller
{
      public function index()
      {
          return view('test');
      }

      public function getCells()
      {
          $cells = Test::all();

          return ['cells' => $cells];
      }
}
