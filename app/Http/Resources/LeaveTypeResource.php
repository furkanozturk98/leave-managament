<?php

namespace App\Http\Resources;

use App\Models\LeaveType;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin LeaveType
 */
class LeaveTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->title,
            'description' => $this->description
        ];
    }
}
