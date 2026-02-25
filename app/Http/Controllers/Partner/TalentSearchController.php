<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TalentBankController;
use App\Models\HseTalentProfile;
use Illuminate\Http\Request;

class TalentSearchController extends Controller
{
    public function index(Request $request)
    {
        $q = HseTalentProfile::query()->where('is_public', true)->with('user');

        if ($request->filled('level')) {
            $q->where('level', $request->string('level'));
        }
        if ($request->filled('area')) {
            $q->where('area', $request->string('area'));
        }
        if ($request->filled('availability')) {
            $q->where('availability', $request->string('availability'));
        }
        if ($request->filled('province')) {
            $q->where('province', $request->string('province'));
        }

        if ($request->filled('search')) {
            $term = '%'.$request->string('search')->toString().'%';
            $q->where(function ($sub) use ($term) {
                $sub->where('headline', 'like', $term)
                    ->orWhere('bio', 'like', $term)
                    ->orWhere('province', 'like', $term);
            });
        }

        $profiles = $q->latest()->paginate(12)->withQueryString();

        return view('partner.talents.index', [
            'profiles' => $profiles,
            'levels' => TalentBankController::levels(),
            'areas' => TalentBankController::areas(),
            'availabilities' => TalentBankController::availabilities(),
        ]);
    }
}
