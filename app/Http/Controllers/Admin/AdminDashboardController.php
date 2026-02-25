<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Dashboard simples e seguro (não depende de queries), para evitar falhas no primeiro acesso.
        return view('admin.dashboard');
    }
}
