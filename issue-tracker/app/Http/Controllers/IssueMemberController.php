<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\User;
use Illuminate\Http\Request;

class IssueMemberController extends Controller
{
    public function attach(Request $request, Issue $issue)
    {
        $this->authorize('update', $issue); // vetëm autori/owner lejohet

        // prano si një ID ose si array ID-sh
        $ids = $request->input('user_id');
        $ids = is_array($ids) ? $ids : [$ids];

        // valido
        $request->validate(
            is_array($request->user_id)
                ? ['user_id' => ['required','array'], 'user_id.*' => ['integer','exists:users,id']]
                : ['user_id' => ['required','integer','exists:users,id']]
        );

        // lidhi pa duplikuar
        $issue->users()->syncWithoutDetaching($ids);
        $issue->load('users');

        return response()->json([
            'html' => view('issues._member_pills', compact('issue'))->render(),
        ]);
    }

    public function detach(Issue $issue, User $user)
    {
        $this->authorize('update', $issue);

        $issue->users()->detach($user->id);
        $issue->load('users');

        return response()->json([
            'html' => view('issues._member_pills', compact('issue'))->render(),
        ]);
    }
}
