<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CallResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $arr  = parent::toArray($request);
        return [
            'call id' => $arr['id'],
            'duration' => $this->formatDuration($arr['duration']),
            'type' => $arr['type'],
            'created at' => $this->formatDate($arr['created_at']),
            'customer id' => $arr['customer_id'],
            'agent id' => $arr['agent_id'],
        ];
    }

    private function formatDuration(int $duration): string
    {
        $hours = floor($duration / 3600);
        $minutes = floor(($duration - $hours * 3600) / 60);
        $seconds = $duration - $hours * 3600 - $minutes * 60;
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

    private function formatDate(string $date): string
    {
        return date('Y-m-d', strtotime($date));
    }
}
