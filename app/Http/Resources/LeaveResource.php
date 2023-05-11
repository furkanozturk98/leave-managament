<?php

namespace App\Http\Resources;

use App\Models\Leave;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Leave
 */
class LeaveResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            "user_id" => $this->user_id,
            "user_name" => $this->user->name ?? '',
            'leave_type_id' => $this->leave_type_id,
            'leave_type_description' => $this->type->title ?? '',
            'description' => $this->description,
            'start_date' => $this->start_date->toDateTimeString(),
            'end_date' => $this->end_date->toDateTimeString(),
            'status' => $this->status,
            'approved_by' => $this->approved_by,
            'approved_by_name' => $this->approvedBy->name ?? '',
            'approved_at' => $this->approved_at,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString()
        ];
    }
}
