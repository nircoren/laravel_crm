<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int      $agent_id
 * @property int      $customer_id
 * @property CallType $type
 * @property int      $duration
 */
class Call extends Model {
    use HasFactory;

    const string DURATION = 'duration';
    const string TYPE = 'type';
    const string CUSTOMER_RELATION_KEY = 'customer_id';
    const string AGENT_RELATION_KEY = 'agent_id';

    protected $fillable = ['agent_id', 'customer_id', self::DURATION, self::TYPE];

    public function getFormattedDuration(): string {
        return gmdate('i:s', $this->duration);
    }

    public function agent() {
        return $this->belongsTo(Agent::class);
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }
}
