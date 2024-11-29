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
        return [
            'id' => $this->call_id,
            'date' => $this->formatDate($this->created_at),
            'duration' => $this->formatDuration($this->duration),
            'notes' => $this->notes,
            'type' => $this->type,
            'agent' => $this->agent_name,
            'customer' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
        ];
    }

    private function formatDuration(int $duration): string {
        $hours = floor($duration / 3600);
        $minutes = floor(($duration - $hours * 3600) / 60);
        $seconds = $duration - $hours * 3600 - $minutes * 60;
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

    private function formatDate(string $date): string {
        return date('Y-m-d', strtotime($date));
    }
}
