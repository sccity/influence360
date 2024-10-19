<?php

namespace Webkul\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'parent_id'     => $this->parent_id ?? null,
            'title'         => $this->title,
            'type'          => $this->type,
            'comment'       => $this->comment,
            'additional'    => [
                'from' => $this->additional['from'] ?? '',
                'to' => $this->additional['to'] ?? [],
                'cc' => $this->additional['cc'] ?? [],
                'bcc' => $this->additional['bcc'] ?? [],
            ],
            'schedule_from' => $this->schedule_from,
            'schedule_to'   => $this->schedule_to,
            'is_done'       => $this->is_done,
            'user'          => $this->user ? new UserResource($this->user) : null,
            'files'         => $this->files ? $this->files->toArray() : [],
            'participants'  => $this->participants ? $this->participants->toArray() : [],
            'location'      => $this->location,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
