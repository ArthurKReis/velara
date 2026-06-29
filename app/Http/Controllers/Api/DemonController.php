<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DemonResource;
use App\Models\Demon;
use Illuminate\Http\Request;

class DemonController extends Controller
{
    public function index(Request $request)
    {
        $query = Demon::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('race', 'LIKE', "%{$search}%");
        }

        $demons = $query->orderBy('name')->paginate(20);

        return DemonResource::collection($demons);
    }

    public function show(Demon $demon)
    {
        return new DemonResource($demon);
    }
}