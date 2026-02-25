<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TalentBankController;
use App\Models\CompanyNeed;
use App\Models\HseTalentProfile;
use Illuminate\Http\Request;

class CompanyNeedController extends Controller
{
    public function index(Request $request)
    {
        $needs = CompanyNeed::query()
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(12);

        return view('partner.needs.index', [
            'needs' => $needs,
            'levels' => TalentBankController::levels(),
            'areas' => TalentBankController::areas(),
            'availabilities' => TalentBankController::availabilities(),
        ]);
    }

    public function create()
    {
        return view('partner.needs.create', [
            'need' => new CompanyNeed(),
            'levels' => TalentBankController::levels(),
            'areas' => TalentBankController::areas(),
            'availabilities' => TalentBankController::availabilities(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateNeed($request);
        $data['user_id'] = auth()->id();
        $data['is_active'] = $request->boolean('is_active', true);

        CompanyNeed::create($data);

        return redirect()->route('partner.needs.index')->with('success', 'Necessidade criada com sucesso!');
    }

    public function show(CompanyNeed $need)
    {
        $this->authorizeNeed($need);

        $matches = $this->matchProfiles($need)
            ->paginate(12)
            ->withQueryString();

        return view('partner.needs.show', [
            'need' => $need,
            'matches' => $matches,
            'levels' => TalentBankController::levels(),
            'areas' => TalentBankController::areas(),
            'availabilities' => TalentBankController::availabilities(),
        ]);
    }

    public function edit(CompanyNeed $need)
    {
        $this->authorizeNeed($need);

        return view('partner.needs.edit', [
            'need' => $need,
            'levels' => TalentBankController::levels(),
            'areas' => TalentBankController::areas(),
            'availabilities' => TalentBankController::availabilities(),
        ]);
    }

    public function update(Request $request, CompanyNeed $need)
    {
        $this->authorizeNeed($need);

        $data = $this->validateNeed($request);
        $data['is_active'] = $request->boolean('is_active', true);

        $need->update($data);

        return redirect()->route('partner.needs.index')->with('success', 'Necessidade atualizada!');
    }

    public function destroy(CompanyNeed $need)
    {
        $this->authorizeNeed($need);
        $need->delete();
        return redirect()->route('partner.needs.index')->with('success', 'Necessidade removida!');
    }

    // --- helpers ---

    private function validateNeed(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'level' => ['required', 'in:junior,pleno,senior'],
            'area' => ['required', 'in:hst,ambiente,esg,medico_trabalho,qhse'],
            'availability' => ['required', 'in:obra,projecto,permanente'],
            'province' => ['required', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:6000'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function authorizeNeed(CompanyNeed $need): void
    {
        if ($need->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Acesso não autorizado.');
        }
    }

    private function matchProfiles(CompanyNeed $need)
    {
        // MVP de matching: match por critérios principais e is_public
        // (Pode evoluir para scoring, IA, ou serviço pago)
        return HseTalentProfile::query()
            ->where('is_public', true)
            ->where('level', $need->level)
            ->where('area', $need->area)
            ->where('availability', $need->availability)
            ->where('province', $need->province)
            ->with('user')
            ->latest();
    }
}
