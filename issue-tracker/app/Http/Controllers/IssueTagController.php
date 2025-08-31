<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\Issue;

class IssueTagController extends Controller
{
    public function attach(Request $request, Issue $issue) {
        $this->authorize('manageTags', $issue);
        $data = $request->validate(['tag_id' => ['required','exists:tags,id']]);
        $issue->tags()->syncWithoutDetaching([$data['tag_id']]);
        $issue->load('tags');
        return response()->json([
            'html' => view('issues._tag_pills', ['issue'=>$issue])->render(),
        ]);
    }

    public function detach(Issue $issue, Tag $tag) {
        $this->authorize('manageTags', $issue);
        $issue->tags()->detach($tag->id);
        $issue->load('tags');
        return response()->json([
            'html' => view('issues._tag_pills', ['issue'=>$issue])->render(),
        ]);
    }

    public function __construct()
{
    $this->middleware('auth');
}
}

