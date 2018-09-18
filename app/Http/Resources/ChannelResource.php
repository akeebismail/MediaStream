<?php

namespace App\Http\Resources;

use App\Traits\CachableChannel;
use Illuminate\Http\Resources\Json\JsonResource;

class ChannelResource extends JsonResource
{
    use CachableChannel;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'description'   => $this->description,
            'nsfw'          => (boolean) $this->nsfw,
            'cover_color'   => $this->color,
            'avatar'        => $this->avatar,
            'subscribers_count' => $this->channelStats($this->id)['subscribersCount'],
            'comments_count'    => $this->channelStats($this->id)['commentsCount'],
            'submissions_count' => $this->channelStats($this->id)['submissionsCount'],
            'created_at'        => optional($this->created_at)->toDateTimeString()
        ];
    }
}
