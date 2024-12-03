<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CallReportResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param Request|null $request *
     *
     * @return array<string, mixed>
     */
    public function toArray(?Request $request = null): array {
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

    // Duration is saved in seconds in the database.
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
