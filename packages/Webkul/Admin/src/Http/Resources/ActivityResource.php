<?php

namespace Webkul\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Admin\Http\Resources\UserResource;
use Webkul\Admin\Http\Resources\ActivityParticipantResource;
use Webkul\Admin\Http\Resources\ActivityFileResource;

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
        $data = [
            'id'            => $this->id,
            'title'         => $this->title,
            'type'          => $this->type,
            'comment'       => $this->comment,
            'schedule_from' => $this->schedule_from,
            'schedule_to'   => $this->schedule_to,
            'is_done'       => $this->is_done,
            'user'          => new UserResource($this->whenLoaded('user')),
            'participants'  => ActivityParticipantResource::collection($this->whenLoaded('participants')),
            'files'         => ActivityFileResource::collection($this->whenLoaded('files')),
            'location'      => $this->location,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];

        if ($this->additional) {
            $additional = json_decode($this->additional, true);
            $data['additional'] = $additional;

            // Format the title for display
            if ($this->type === 'system') {
                $oldValue = $additional['old']['value'] ?? 'Empty';
                $newValue = $additional['new']['value'] ?? 'Empty';
                $data['formatted_title'] = "{$this->title} : {$oldValue} → {$newValue}";
            } else {
                $data['formatted_title'] = $this->title;
            }
        }

        return $data;
    }
}
