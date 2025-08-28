<?php

namespace App\Http\Controllers;


use App\Models\Issue;
use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Issue $issue, Request $request)
    {
        $comments = $issue->comments()->paginate(5);
        return response()->json([
            'data' => $comments->items(),
            'meta' => [
                'current_page' => $comments->currentPage(),
                'last_page' => $comments->lastPage(),
                'next_page_url' => $comments->nextPageUrl(),
                'prev_page_url' => $comments->previousPageUrl(),
            ],
        ]);
    }


    public function store(Issue $issue, StoreCommentRequest $request)
    {
        $comment = $issue->comments()->create($request->validated());
        return response()->json([
            'message' => 'Comment created',
            'comment' => $comment,
        ], 201);
    }
}
