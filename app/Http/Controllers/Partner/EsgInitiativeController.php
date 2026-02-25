<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\EsgInitiative;
use App\Services\EsgInitiativeService;
use Illuminate\Http\Request;

use App\Support\HandlesControllerErrors;
class EsgInitiativeController extends Controller
{
    use HandlesControllerErrors;

    public function __construct(private EsgInitiativeService $service)
    {
        // aplica Policy (create/update/delete) sem quebrar front-end
        $this->authorizeResource(EsgInitiative::class, 'initiative');
    }

    private function imageRules(): array
    {
        return [
            'nullable',
            'file',
            'mimes:jpg,jpeg,png,webp',
            'mimetypes:image/jpeg,image/png,image/webp',
            'max:4096',
            'dimensions:min_width=300,min_height=300,max_width=8000,max_height=8000',
        ];
    }

    public function index(Request $request)
    {
        return $this->runWithErrors(function () use ($request) {
        $this->authorize('viewAny', EsgInitiative::class);

        $user = auth()->user();
        $q = EsgInitiative::query()->latest('created_at');

        // Empresas veem somente as suas; Admin/Editor podem ver tudo
        if (!$user->isEditor()) {
            $q->where('user_id', $user->id);
        }

        if ($request->filled('q')) {
            $term = $request->q;
            $q->where(function ($x) use ($term) {
                $x->where('title', 'like', "%{$term}%")
                  ->orWhere('focus_area', 'like', "%{$term}%")
                  ->orWhere('location', 'like', "%{$term}%");
            });
        }

        $items = $q->paginate(15)->withQueryString();
        return view('partner.esg.index', ['items' => $items]);
    
        });
}

    public function create()
    {
        return $this->runWithErrors(function () {
        return view('partner.esg.create');
    
        });
}

    public function store(Request $request)
    {
        return $this->runWithErrors(function () use ($request) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'description' => 'nullable|string',
            'focus_area' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'is_active' => 'sometimes|boolean',
            'image' => $this->imageRules(),
        ]);

        $this->service->create($validated, $request, auth()->id());
        return redirect()->route('partner.esg.index')->with('success', 'Iniciativa ESG publicada!');
    
        });
}

    public function edit(EsgInitiative $initiative)
    {
        return $this->runWithErrors(function () use ($initiative) {
        return view('partner.esg.edit', ['initiative' => $initiative]);
    
        });
}

    public function update(Request $request, EsgInitiative $initiative)
    {
        return $this->runWithErrors(function () use ($request, $initiative) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'description' => 'nullable|string',
            'focus_area' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'is_active' => 'sometimes|boolean',
            'image' => $this->imageRules(),
        ]);

        $this->service->update($initiative, $validated, $request);
        return redirect()->route('partner.esg.index')->with('success', 'Iniciativa ESG atualizada!');
    
        });
}

    public function destroy(EsgInitiative $initiative)
    {
        return $this->runWithErrors(function () use ($initiative) {
        $this->service->delete($initiative);
        return redirect()->route('partner.esg.index')->with('success', 'Iniciativa ESG removida!');
    
        });
}
}
