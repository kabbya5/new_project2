<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->loadCount('likes');
        $user = auth()->user();
        $alReadyLiked = $this->likes()->where('user_id', $user->id)->exists();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'img_url' => $this->img_url,
            'video' => $this->video,
            'content' => $this->content,
            'likes_count' =>  $this->likes_count,
            'isLiked' =>  $alReadyLiked ? true :false,
            'type' => $this->type,
            'created_at'  => $this->created_at->diffForHumans(),
            'page' => new PageResource($this->page),
            'comments_count'  => $this->comments->count(),
            'comments'=> CommentResource::collection($this->comments),
            'user' => new UserResource($this->user),
        ];
    }
}
