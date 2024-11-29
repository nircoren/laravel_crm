<?php

namespace App\Models;

use App\Enums\CallType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int      $duration
 * @property CallType $type
 * @property int      $agent_id
 * @property int      $customer_id
 */
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
