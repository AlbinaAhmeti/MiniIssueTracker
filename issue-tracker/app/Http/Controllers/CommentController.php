<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Issue;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function index(Issue $issue) {
        $comments = $issue->comments()->latest()->paginate(5);
        return response()->json([
            'html' => view('issues._comment_list', compact('comments'))->render(),
            'next' => $comments->nextPageUrl(),
        ]);
    }

    public function store(CommentRequest $request, Issue $issue) {
        $comment = $issue->comments()->create($request->validated());
        return response()->json([
            'html' => view('issues._comment_item', ['comment'=>$comment])->render(),
            'count' => $issue->comments()->count(),
        ], 201);
    }
}

