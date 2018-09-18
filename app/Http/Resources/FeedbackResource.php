<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackResource extends JsonResource
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
            'id'          => $this->id,
            'subject'     => $this->subject,
            'user_id'     => $this->user_id,
            'description' => $this->description,
            'created_at'  => optional($this->created_at)->toDateTimeString(),

            'author' => new UserResource($this->owner),
        ];
    }
}
