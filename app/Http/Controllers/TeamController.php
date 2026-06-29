<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Demon;
use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::where('user_id', Auth::id())
                     ->with('demons')
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);
        return view('teams.index', compact('teams'));
    }

    public function create()
    {
        $demons = Demon::orderBy('name')->get();
        return view('teams.create', compact('demons'));
    }

    public function store(StoreTeamRequest $request)
    {
        DB::transaction(function () use ($request) {
            $team = Team::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'description' => $request->description,
            ]);

            if ($request->has('demons')) {
                $this->syncDemons($team, $request->demons);
            }
        });

        return redirect()->route('teams.index')
                         ->with('success', 'Time criado com sucesso!');
    }

    public function show(Team $team)
    {
        $this->authorize('view', $team);
        $team->load('demons');
        return view('teams.show', compact('team'));
    }

    public function edit(Team $team)
    {
        $this->authorize('update', $team);
        $demons = Demon::orderBy('name')->get();
        $selectedDemons = $team->demons->pluck('id')->toArray();
        return view('teams.edit', compact('team', 'demons', 'selectedDemons'));
    }

    public function update(UpdateTeamRequest $request, Team $team)
    {
        $this->authorize('update', $team);

        DB::transaction(function () use ($request, $team) {
            $team->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            if ($request->has('demons')) {
                $this->syncDemons($team, $request->demons);
            } else {
                $team->demons()->detach();
            }
        });

        return redirect()->route('teams.index')
                         ->with('success', 'Time atualizado com sucesso!');
    }

    public function destroy(Team $team)
    {
        $this->authorize('delete', $team);
        $team->delete();
        return redirect()->route('teams.index')
                         ->with('success', 'Time excluído com sucesso!');
    }

    // Método auxiliar para sincronizar demônios com posições
    private function syncDemons(Team $team, array $demonIds)
    {
        // Limita a 5 demônios
        $demonIds = array_slice($demonIds, 0, 5);

        $pivotData = [];
        foreach ($demonIds as $position => $demonId) {
            $pivotData[$demonId] = ['position' => $position + 1];
        }

        $team->demons()->sync($pivotData);
    }
}
