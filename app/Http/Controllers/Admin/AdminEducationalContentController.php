<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EducationalContent;
use App\Services\EducationalContentService;
use Illuminate\Http\Request;
use App\Http\Requests\Educational\StoreEducationalContentRequest;
use App\Http\Requests\Educational\UpdateEducationalContentRequest;

use App\Support\HandlesControllerErrors;
class AdminEducationalContentController extends Controller
{
    use HandlesControllerErrors;

    public function __construct(private EducationalContentService $service)
    {
        $this->authorizeResource(EducationalContent::class, 'educational');
    }

    public function index(Request $request)
    {
        return $this->runWithErrors(function () use ($request) {
        $q = EducationalContent::query()->latest('created_at');

        if ($request->filled('q')) {
            $term = $request->q;
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('topic', 'like', "%{$term}%");
        }

        $contents = $q->paginate(15)->withQueryString();
        return view('admin.educational.index', compact('contents'));
    
        });
}

    public function create()
    {
        return $this->runWithErrors(function () {
        return view('admin.educational.create');
    
        });
}

    public function store(StoreEducationalContentRequest $request)
    {
        return $this->runWithErrors(function () use ($request) {
        $validated = $request->validated();
        $this->service->create($validated, $request);
        return redirect()->route('admin.educational.index')->with('success', 'Conteúdo criado com sucesso!');
    
        });
}

    public function edit(EducationalContent $educational)
    {
        return $this->runWithErrors(function () use ($educational) {
        return view('admin.educational.edit', ['content' => $educational]);
    
        });
}

    public function update(UpdateEducationalContentRequest $request, EducationalContent $educational)
    {
        return $this->runWithErrors(function () use ($request, $educational) {
        $validated = $request->validated();
        $this->service->update($educational, $validated, $request);
        return redirect()->route('admin.educational.index')->with('success', 'Conteúdo atualizado!');
    
        });
}

    public function destroy(EducationalContent $educational)
    {
        return $this->runWithErrors(function () use ($educational) {
        $this->service->delete($educational);
        return redirect()->route('admin.educational.index')->with('success', 'Conteúdo apagado!');
    
        });
}
}
