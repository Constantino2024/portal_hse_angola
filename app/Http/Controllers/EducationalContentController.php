<?php

namespace App\Http\Controllers;

use App\Models\EducationalContent;
use Illuminate\Http\Request;

class EducationalContentController extends Controller
{
    public function index(Request $request)
    {
        $q = EducationalContent::published()->latest('published_at');

        if ($request->filled('q')) {
            $term = $request->q;
            $q->where(function ($x) use ($term) {
                $x->where('title','like',"%$term%")
                  ->orWhere('topic','like',"%$term%")
                  ->orWhere('excerpt','like',"%$term%")
                  ->orWhere('body','like',"%$term%");
            });
        }

        if ($request->filled('level')) {
            $q->where('level', $request->level);
        }

        $contents = $q->paginate(12)->withQueryString();

        return view('educational.index', compact('contents'));
    }

    public function show(string $slug)
    {
        $content = EducationalContent::published()->where('slug', $slug)->firstOrFail();
        $latest = EducationalContent::published()->latest('published_at')->take(6)->get();

        return view('educational.show', compact('content', 'latest'));
    }
}
