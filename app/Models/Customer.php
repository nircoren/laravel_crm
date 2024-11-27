<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['agent_id', 'customer_id', 'duration'];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function calls()
    {
        return $this->hasMany(Call::class);
    }
}