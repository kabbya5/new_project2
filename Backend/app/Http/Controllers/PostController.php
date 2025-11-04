<?php

namespace App\Http\Controllers;

use App\Events\CommentEvent;
use App\Events\LikedEvent;
use App\Http\Resources\CommentResource;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\PostResource;
use App\Models\Comment;
use App\Models\Post;
use App\Traits\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    use FileUpload;

    public function index(Request $request){
        $page = $request->input('page', 1);
        $cacheKey = 'posts_page_' . $page;
        $limit = $request->input('limit', 30);

        $posts = Cache::remember($cacheKey,2, function () use($limit) {
            return Post::with('likes.user','user', 'comments')->latest()->paginate($limit);
        });

        return PostResource::collection($posts);
    }

    public function likePost(Request $request, Post $post){
        $user = auth()->user();
        $alReadyLiked = $post->likes()->where('user_id', $user->id)->exists();
        $liked = false;
        if($alReadyLiked){
            $liked = false;
            $post->likes()->where('user_id', $user->id)->delete();
        }else{
            $post->likes()->create([
                'user_id' => $user->id,
            ]);

            $liked = true;
        }

        broadcast(new LikedEvent($post, $liked));

        return true;
    }

    public function commentPost(Request $request, Post $post){
        $request->validate([
            'image' => 'file|mimes:jpg,png,jpeg,pdf|max:2048',
        ]);
        $user = auth()->user();
        $image = $request->file('image');
        $data = [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'content' => $request->text,
        ];
        if($image){
           $data['image_url'] =  $this->uplodFile($request->file('image'), 'comment');
        }

        $comment = Comment::create();

        broadcast(new CommentEvent($comment, $post->id));

        return response()->json(['comment' => new CommentResource($comment)], 200);
    }
}
