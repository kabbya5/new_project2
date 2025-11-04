<?php

namespace App\Events;

use App\Models\Post;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LikedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $post;
    public $liked;

    public function __construct(Post $post, $liked)
    {
        $this->post = $post;
        $this->liked = $liked;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('post.like'),
        ];
    }

    public function broadcastWith()
    {
        $data = [
            'post_id' => $this->post->id,
            'likes_count' => $this->post->likes->count(),
        ];

        \Log::info('Broadcasting with data:', $data);
        return $data;
    }
}
