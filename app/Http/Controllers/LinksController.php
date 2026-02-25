<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LinksController extends Controller
{
    public function index()
    {
        $sections = config('hse_links', []);
        return view('links.index', compact('sections'));
    }
}
