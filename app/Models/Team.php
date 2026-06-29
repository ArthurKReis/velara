<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'description'
    ];

    // Relacionamento com User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relacionamento N:N com Demon via pivô demon_team
    public function demons()
    {
        return $this->belongsToMany(Demon::class, 'demon_team')
                    ->withPivot('position')
                    ->withTimestamps()
                    ->orderBy('pivot_position');
    }
}