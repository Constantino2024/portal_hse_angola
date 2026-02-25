<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CompanyService;
use App\Http\Requests\Companies\StoreCompanyRequest;
use App\Http\Requests\Companies\UpdateCompanyRequest;
use Illuminate\Http\Request;

use App\Support\HandlesControllerErrors;
class AdminCompanyController extends Controller
{
    use HandlesControllerErrors;

    public function __construct(private CompanyService $service)
    {
        $this->authorize('manageCompanies', User::class);
    }
    public function index(Request $request)
    {
        return $this->runWithErrors(function () use ($request) {
        $q = User::query()->where('role', 'empresa')->latest('created_at');

        if ($request->filled('q')) {
            $term = $request->q;
            $q->where(function ($x) use ($term) {
                $x->where('name', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%");
            });
        }

        $companies = $q->paginate(15)->withQueryString();
        return view('admin.companies.index', compact('companies'));
    
        });
}

    public function create()
    {
        return $this->runWithErrors(function () {
        return view('admin.companies.create');
    
        });
}

    public function store(StoreCompanyRequest $request)
    {
        return $this->runWithErrors(function () use ($request) {
        $this->authorize('manageCompanies', User::class);
        $data = $request->validated();

        $this->service->createCompanyUser($data);

        return redirect()->route('admin.companies.index')->with('success', 'Empresa criada com sucesso!');
    
        });
}

    public function edit(User $company)
    {
        return $this->runWithErrors(function () use ($company) {
        abort_unless($company->role === 'empresa', 404);
        return view('admin.companies.edit', compact('company'));
    
        });
}

    public function update(UpdateCompanyRequest $request, User $company)
    {
        return $this->runWithErrors(function () use ($request, $company) {
        $this->authorize('manageCompanies', User::class);
        abort_unless($company->role === 'empresa', 404);

        $data = $request->validated();

        $this->service->updateCompanyUser($company, $data);

        return redirect()->route('admin.companies.index')->with('success', 'Empresa atualizada!');
    
        });
}

    public function destroy(User $company)
    {
        return $this->runWithErrors(function () use ($company) {
        $this->authorize('manageCompanies', User::class);
        abort_unless($company->role === 'empresa', 404);
        $company->delete();
        return redirect()->route('admin.companies.index')->with('success', 'Empresa removida!');
    
        });
}
}
