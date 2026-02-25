<?php

namespace App\Http\Controllers;

use App\Models\Trabalho;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $q = Trabalho::published()->latest('published_at');

        if ($request->filled('q')) {
            $term = $request->q;
            $q->where(function ($x) use ($term) {
                $x->where('title','like',"%$term%")
                  ->orWhere('company','like',"%$term%")
                  ->orWhere('location','like',"%$term%")
                  ->orWhere('excerpt','like',"%$term%");
            });
        }

        $jobs = $q->paginate(12)->withQueryString();

        return view('jobs.index', compact('jobs'));
    }

    public function show(string $slug)
    {
        $job = Trabalho::published()->where('slug', $slug)->firstOrFail();
        $latest = Trabalho::published()->latest('published_at')->take(6)->get();

        return view('jobs.show', compact('job', 'latest'));
    }
}
