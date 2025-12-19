<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rubric; 

class RubricController extends Controller
{
      public function show(Rubric $rubric)
    {
        return view('rubrics.show', compact('rubric'));
    }
}
