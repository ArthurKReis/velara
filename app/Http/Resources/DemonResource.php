<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DemonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'race' => $this->race,
            'level' => $this->level,
            'stats' => [
                'strength' => $this->strength,
                'magic' => $this->magic,
                'vitality' => $this->vitality,
                'agility' => $this->agility,
                'luck' => $this->luck,
            ],
            'resistances' => [
                'fire' => $this->res_fire,
                'ice' => $this->res_ice,
                'elec' => $this->res_elec,
                'force' => $this->res_force,
                'light' => $this->res_light,
                'dark' => $this->res_dark,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}