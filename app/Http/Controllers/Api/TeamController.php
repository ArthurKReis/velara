<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $teams = Team::where('user_id', $request->user()->id)
                     ->with('demons')
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);

        return TeamResource::collection($teams);
    }

    public function store(StoreTeamRequest $request)
    {
        $team = Team::create([
            'user_id' => $request->user()->id,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        if ($request->has('demons')) {
            $this->syncDemons($team, $request->demons);
        }

        return new TeamResource($team->load('demons'));
    }

    public function show(Team $team, Request $request)
    {
        if ($team->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Não autorizado.'], 403);
        }

        return new TeamResource($team->load('demons'));
    }

    public function update(UpdateTeamRequest $request, Team $team)
    {
        if ($team->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Não autorizado.'], 403);
        }

        $team->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        if ($request->has('demons')) {
            $this->syncDemons($team, $request->demons);
        } else {
            $team->demons()->detach();
        }

        return new TeamResource($team->load('demons'));
    }

    public function destroy(Team $team, Request $request)
    {
        if ($team->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Não autorizado.'], 403);
        }

        $team->delete();

        return response()->json(['message' => 'Time excluído com sucesso.']);
    }

    private function syncDemons(Team $team, array $demonIds)
    {
        $demonIds = array_slice($demonIds, 0, 5);

        $pivotData = [];
        foreach ($demonIds as $position => $demonId) {
            $pivotData[$demonId] = ['position' => $position + 1];
        }

        $team->demons()->sync($pivotData);
    }
}