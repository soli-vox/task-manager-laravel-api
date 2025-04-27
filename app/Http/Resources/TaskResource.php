<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class TaskResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        Log::info('TaskResource - due_date: ' . $this->due_date . ', created_at: ' . $this->created_at);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'dueDate' => $this->due_date ? $this->due_date->format('Y-m-d') : '2025-12-31',
            'status' => $this->status,
            'priority' => $this->priority,
            'createdAt' => $this->created_at ? $this->created_at->format('Y-m-d') : now()->format('Y-m-d'), // Default to now
            'updatedAt' => $this->updated_at ? $this->updated_at->format('Y-m-d') : now()->format('Y-m-d'), // Default to now
            'isOverdue' => $this->due_date ? now()->gt($this->due_date) : false,

        ];
    }
}
