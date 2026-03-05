<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $nextVisit = $this->visits()->latest()->first();
        $formattedNextVisit = $nextVisit && $nextVisit->next_visit_date ? $nextVisit->next_visit_date->format('M d, Y') : null;
        $lastVisit = $this->visits()->latest()->skip(1)->first();
        $formattedLastVisit = $lastVisit && $lastVisit->next_visit_date ? $lastVisit->next_visit_date->format('M d, Y') : null;
        return [
            'id' => $this->id,
            'name' => $this->user->name,
            'age' => $this->age,
            'status' => $this->status,
            'ai_insight' => $this->latestAiAnalysisResult->ai_insight ?? 'No analysis available yet',
            'last_visit' => $formattedLastVisit ?? $this->created_at->format('M d, Y'),
            'next_appointment' => $formattedNextVisit ?? 'No appointment scheduled',
        ];
    }
}
