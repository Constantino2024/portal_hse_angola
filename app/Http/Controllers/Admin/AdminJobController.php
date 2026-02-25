<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trabalho;
use App\Services\JobService;
use Illuminate\Http\Request;
use App\Http\Requests\Jobs\StoreJobRequest;
use App\Http\Requests\Jobs\UpdateJobRequest;

use App\Support\HandlesControllerErrors;
class AdminJobController extends Controller
{
    use HandlesControllerErrors;

    public function __construct(private JobService $service)
    {
        $this->authorizeResource(Trabalho::class, 'job');
    }

    public function index(Request $request)
    {
        return $this->runWithErrors(function () use ($request) {
        $q = Trabalho::query()->latest('created_at');

        if ($request->filled('q')) {
            $term = $request->q;
            $q->where(function ($x) use ($term) {
                $x->where('title','like',"%$term%")
                  ->orWhere('company','like',"%$term%")
                  ->orWhere('location','like',"%$term%");
            });
        }

        $jobs = $q->paginate(15)->withQueryString();
        return view('admin.jobs.index', compact('jobs'));
    
        });
}

    public function create()
    {
        return $this->runWithErrors(function () {
        return view('admin.jobs.create');
    
        });
}

    public function store(StoreJobRequest $request)
    {
        return $this->runWithErrors(function () use ($request) {
        $validated = $request->validated();
        $this->service->create($validated, $request);

        return redirect()->route('admin.jobs.index')->with('success', 'Vaga criada com sucesso!');
    
        });
}

    public function edit(Trabalho $job)
    {
        return $this->runWithErrors(function () use ($job) {
        return view('admin.jobs.edit', compact('job'));
    
        });
}

    public function update(UpdateJobRequest $request, Trabalho $job)
    {
        return $this->runWithErrors(function () use ($request, $job) {
        $validated = $request->validated();
        $this->service->update($job, $validated, $request);

        return redirect()->route('admin.jobs.index')->with('success', 'Vaga atualizada!');
    
        });
}

    public function destroy(Trabalho $job)
    {
        return $this->runWithErrors(function () use ($job) {
        $this->service->delete($job);
        return redirect()->route('admin.jobs.index')->with('success', 'Vaga apagada!');
    
        });
}
}
