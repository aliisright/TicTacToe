<?php

namespace App\Http\Controllers;

use App\Cell;
use Illuminate\Http\Request;

class CellController extends Controller
{
    public function update(Request $request)
    {
      
        $cell = Cell::find($request->input('id'));
        if(!$cell->selected) {
            $cell->selected = true;
            $cell->sign = $request->input('sign');
            $cell->update();
        }
    }
}
