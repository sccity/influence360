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
        $rawAttributes = $this->getAttributes();
        \Log::info('ActivityResource - raw attributes:', $rawAttributes);

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
            'additional'    => isset($rawAttributes['additional']) ? json_decode($rawAttributes['additional'], true) : [],
        ];

        if ($this->additional) {
            // Since 'additional' is already cast to array, we can use it directly
            $additional = $this->additional;

            $data['additional'] = $additional;

            if ($this->type === 'email') {
                $data['formatted_title'] = $this->title;
                $data['email_subject']   = $additional['subject'] ?? '';
                $data['email_body']      = $additional['body'] ?? '';
            } elseif ($this->type === 'system') {
                $attribute = $additional['attribute'] ?? '';
                $oldValue  = $additional['old']['label'] ?? 'N/A';
                $newValue  = $additional['new']['label'] ?? 'N/A';
                $data['formatted_title'] = "{$this->title}: {$oldValue} → {$newValue}";
                $data['attribute']       = $attribute;
            } else {
                $data['formatted_title'] = $this->title;
            }
        }

        return $data;
    }
}
