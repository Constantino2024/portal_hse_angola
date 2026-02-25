<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $data = $request->validate([
            'author_name'  => 'required|string|max:255',
            'author_email' => 'required|email',
            'body'         => 'required|string|max:2000',
        ]);

        $data['post_id'] = $post->id;
        $data['is_approved'] = false; // moderação

        Comment::create($data);

        return back()->with('success', 'Comentário enviado e aguardando aprovação.');
    }

    public function indexAjax(Request $request, Post $post)
    {
        $comments = $post->comments()
            ->where('is_approved', true)
            ->latest()
            ->paginate(5);

        return response()->json([
            'data' => collect($comments->items())->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'name' => $comment->author_name,
                    'avatar' => strtoupper(mb_substr($comment->author_name, 0, 1)),
                    'body' => $comment->body,
                    'date' => $comment->created_at->format('d/m/Y H:i'),
                ];
            }),
            'current_page' => $comments->currentPage(),
            'last_page'    => $comments->lastPage(),
            'prev_page_url'=> $comments->previousPageUrl(),
            'next_page_url'=> $comments->nextPageUrl(),
            'total'        => $comments->total(),
        ]);
    }


    public function storeAjax(Request $request, Post $post)
    {
        $data = $request->validate([
            'author_name'  => 'required|string|max:255',
            'author_email' => 'required|email',
            'body'         => 'required|string'
        ]);

        $comment = $post->comments()->create([
            ...$data,
            'is_approved' => true, // ou false se quiser moderação
        ]);

        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'name' => $comment->author_name,
                'avatar' => strtoupper(substr($comment->author_name, 0, 1)),
                'body' => $comment->body,
                'date' => $comment->created_at->format('d/m/Y H:i')
            ]
        ]);
    }

}
