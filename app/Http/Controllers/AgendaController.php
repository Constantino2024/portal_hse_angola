<?php

namespace App\Http\Controllers;

use App\Models\AgendaItem;
use App\Models\AgendaRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        $q = AgendaItem::query()
            ->where('is_active', true)
            ->orderBy('starts_at');

        if ($request->filled('type')) {
            $q->where('type', $request->type);
        }

        if ($request->filled('q')) {
            $term = $request->q;
            $q->where(function ($x) use ($term) {
                $x->where('title', 'like', "%$term%")
                  ->orWhere('location', 'like', "%$term%")
                  ->orWhere('description', 'like', "%$term%");
            });
        }

        // opcional: filtrar por mês (YYYY-MM)
        if ($request->filled('month')) {
            $month = $request->month;
            $q->whereRaw('DATE_FORMAT(starts_at, "%Y-%m") = ?', [$month]);
        }

        $items = $q->paginate(12)->withQueryString();

        $types = [
            'evento' => 'Eventos',
            'workshop' => 'Workshops',
            'formacao' => 'Formações',
            'data_internacional' => 'Datas internacionais',
            'webinar' => 'Webinars',
        ];

        return view('agenda.index', compact('items', 'types'));
    }

    public function show(string $slug)
    {
        $item = AgendaItem::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('agenda.show', compact('item'));
    }

    public function register(Request $request, string $slug)
    {
        $item = AgendaItem::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        if (!$item->registration_enabled) {
            return back()->with('error', 'As inscrições para este item estão desativadas.');
        }

        if ($item->capacity !== null && $item->available_spots !== null && $item->available_spots <= 0) {
            return back()->with('error', 'Inscrições esgotadas para este item.');
        }

        // Se o utilizador estiver logado, pré-preenche e liga ao user_id
        $user = Auth::user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:60'],
            'company' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $data['user_id'] = $user?->id;
        $data['agenda_item_id'] = $item->id;

        try {
            AgendaRegistration::create($data);
        } catch (\Throwable $e) {
            // provável duplicado por email
            return back()->with('error', 'Este email já está inscrito neste item.');
        }

        return back()->with('success', 'Inscrição realizada com sucesso!');
    }
}
