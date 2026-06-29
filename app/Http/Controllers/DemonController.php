<?php

namespace App\Http\Controllers;

use App\Models\Demon;
use Illuminate\Http\Request;

class DemonController extends Controller
{
    public function index(Request $request)
    {
        $query = Demon::query();

        // Busca por nome ou raça
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('race', 'LIKE', "%{$search}%");
        }

        $demons = $query->orderBy('name')->paginate(20);

        return view('demons.index', compact('demons'));
    }

    public function show(Demon $demon)
    {
        // Carrega as fusões (se existirem) - a tabela está vazia por enquanto
        $demon->load(['fusionsAsA', 'fusionsAsB', 'fusionsAsResult']);

        return view('demons.show', compact('demon'));
    }
}