<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnimalReceivingNoteController extends Controller
{
    
    public function create()
    {
        return view('animal-receiving-note.create');
    }

}
