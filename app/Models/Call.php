<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Call extends Model {
    use HasFactory;

    protected $fillable = ['duration', 'type','notes'];

    public function agent() {
        return $this->belongsTo(Agent::class);
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }
}
