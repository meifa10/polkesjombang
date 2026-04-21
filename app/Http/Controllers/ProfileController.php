<?php

namespace App\Http\Controllers;

use App\Models\Gallery;

class ProfileController extends Controller
{
    public function index()
    {
        $galleries = Gallery::all(); 

        return view('profile.index', compact('galleries'));
    }
}
