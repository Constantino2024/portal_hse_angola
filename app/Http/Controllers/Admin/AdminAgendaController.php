<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgendaItem;
use App\Services\AgendaService;
use Illuminate\Http\Request;
use App\Http\Requests\Agenda\StoreAgendaRequest;
use App\Http\Requests\Agenda\UpdateAgendaRequest;

use App\Support\HandlesControllerErrors;
class AdminAgendaController extends Controller
{
    use HandlesControllerErrors;

    public function __construct(private AgendaService $service)
    {
        // aplica Policies automaticamente em rotas resource
        $this->authorizeResource(AgendaItem::class, 'agenda');
    }

    public function index(Request $request)
    {
        return $this->runWithErrors(function () use ($request) {
        $q = AgendaItem::query()->latest('starts_at');

        if ($request->filled('q')) {
            $term = $request->q;
            $q->where(function ($x) use ($term) {
                $x->where('title','like',"%$term%")
                  ->orWhere('location','like',"%$term%")
                  ->orWhere('type','like',"%$term%");
            });
        }

        if ($request->filled('type')) {
            $q->where('type', $request->type);
        }

        $items = $q->paginate(15)->withQueryString();

        $types = $this->types();
        return view('admin.agenda.index', compact('items','types'));
    
        });
}

    public function create()
    {
        return $this->runWithErrors(function () {
        $types = $this->types();
        return view('admin.agenda.create', compact('types'));
    
        });
}

    public function store(StoreAgendaRequest $request)
    {
        return $this->runWithErrors(function () use ($request) {
        $validated = $request->validated();
        $this->service->create($validated, $request);

        return redirect()->route('admin.agenda.index')->with('success', 'Item da agenda criado com sucesso!');
    
        });
}

    public function edit(AgendaItem $agenda)
    {
        return $this->runWithErrors(function () use ($agenda) {
        $types = $this->types();
        return view('admin.agenda.edit', ['item' => $agenda, 'types' => $types]);
    
        });
}

    public function update(UpdateAgendaRequest $request, AgendaItem $agenda)
    {
        return $this->runWithErrors(function () use ($request, $agenda) {
        $validated = $request->validated();
        $this->service->update($agenda, $validated, $request);

        return redirect()->route('admin.agenda.index')->with('success', 'Item da agenda atualizado!');
    
        });
}

    public function destroy(AgendaItem $agenda)
    {
        return $this->runWithErrors(function () use ($agenda) {
        $this->service->delete($agenda);
        return redirect()->route('admin.agenda.index')->with('success', 'Item da agenda apagado!');
    
        });
}

    private function types(): array
    {
        return [
            'evento' => 'Evento',
            'workshop' => 'Workshop',
            'formacao' => 'Formação',
            'data_internacional' => 'Data internacional',
            'webinar' => 'Webinar',
        ];
    }
}
