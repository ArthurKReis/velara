<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fusion extends Model
{
    use HasFactory;

    protected $fillable = [
        'demon_a_id', 'demon_b_id', 'demon_result_id'
    ];

    public function demonA()
    {
        return $this->belongsTo(Demon::class, 'demon_a_id');
    }

    public function demonB()
    {
        return $this->belongsTo(Demon::class, 'demon_b_id');
    }

    public function demonResult()
    {
        return $this->belongsTo(Demon::class, 'demon_result_id');
    }
}