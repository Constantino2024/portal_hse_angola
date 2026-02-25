<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\CompanyProject;
use App\Services\CompanyProjectService;
use Illuminate\Http\Request;

use App\Support\HandlesControllerErrors;
class CompanyProjectController extends Controller
{
    use HandlesControllerErrors;

    public function __construct(private CompanyProjectService $service)
    {
        $this->authorizeResource(CompanyProject::class, 'project');
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
        $this->authorize('viewAny', CompanyProject::class);

        $user = auth()->user();
        $q = CompanyProject::query()->latest('created_at');

        if (!$user->isEditor()) {
            $q->where('user_id', $user->id);
        }

        if ($request->filled('q')) {
            $term = $request->q;
            $q->where(function ($x) use ($term) {
                $x->where('title','like',"%{$term}%")
                  ->orWhere('client','like',"%{$term}%")
                  ->orWhere('location','like',"%{$term}%")
                  ->orWhere('sector','like',"%{$term}%");
            });
        }

        $items = $q->paginate(15)->withQueryString();
        return view('partner.projects.index', ['items' => $items]);
    
        });
}

    public function create()
    {
        return $this->runWithErrors(function () {
        return view('partner.projects.create');
    
        });
}

    public function store(Request $request)
    {
        return $this->runWithErrors(function () use ($request) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'client' => 'nullable|string|max:255',
            'sector' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'excerpt' => 'nullable|string',
            'description' => 'nullable|string',
            'website' => 'nullable|url|max:2048',
            'is_active' => 'sometimes|boolean',
            'image' => $this->imageRules(),
        ]);

        $this->service->create($validated, $request, auth()->id());
        return redirect()->route('partner.projects.index')->with('success', 'Projeto divulgado com sucesso!');
    
        });
}

    public function edit(CompanyProject $project)
    {
        return $this->runWithErrors(function () use ($project) {
        return view('partner.projects.edit', ['project' => $project]);
    
        });
}

    public function update(Request $request, CompanyProject $project)
    {
        return $this->runWithErrors(function () use ($request, $project) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'client' => 'nullable|string|max:255',
            'sector' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'excerpt' => 'nullable|string',
            'description' => 'nullable|string',
            'website' => 'nullable|url|max:2048',
            'is_active' => 'sometimes|boolean',
            'image' => $this->imageRules(),
        ]);

        $this->service->update($project, $validated, $request);
        return redirect()->route('partner.projects.index')->with('success', 'Projeto atualizado!');
    
        });
}

    public function destroy(CompanyProject $project)
    {
        return $this->runWithErrors(function () use ($project) {
        $this->service->delete($project);
        return redirect()->route('partner.projects.index')->with('success', 'Projeto removido!');
    
        });
}
}
