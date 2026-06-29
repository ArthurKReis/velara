<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demon extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'race', 'level', 'strength', 'magic', 'vitality',
        'agility', 'luck', 'res_fire', 'res_ice', 'res_elec',
        'res_force', 'res_light', 'res_dark', 'image_url'
    ];

    // Relacionamento N:N com Team via pivô demon_team
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'demon_team')
                    ->withPivot('position')
                    ->withTimestamps();
    }

    // Fusões como ingrediente A
    public function fusionsAsA()
    {
        return $this->hasMany(Fusion::class, 'demon_a_id');
    }

    // Fusões como ingrediente B
    public function fusionsAsB()
    {
        return $this->hasMany(Fusion::class, 'demon_b_id');
    }

    // Fusões como resultado
    public function fusionsAsResult()
    {
        return $this->hasMany(Fusion::class, 'demon_result_id');
    }
    
}