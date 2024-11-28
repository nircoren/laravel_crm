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

    const string DURATION = 'duration';
    const string TYPE = 'type';
    const string AGENT_RELATION_KEY = 'agent_id';
    const string CUSTOMER_RELATION_KEY = 'customer_id';

    protected $fillable = [self::DURATION, self::TYPE, self::AGENT_RELATION_KEY, self::CUSTOMER_RELATION_KEY];

    public function agent() {
        return $this->belongsTo(Agent::class);
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }
}
