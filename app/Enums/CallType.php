<?php

namespace App\Enums;

enum CallType: string {
    case INBOUND = 'inbound';
    case OUTBOUND = 'outbound';
    case MISSED = 'missed';

    public static function values(): array {
        return array_column(self::cases(), 'value');
    }
}
