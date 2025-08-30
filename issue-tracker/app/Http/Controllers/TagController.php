<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use App\Http\Requests\TagRequest;

class TagController extends Controller
{
    public function index() {
        $tags = Tag::orderBy('name')->get();
        return view('tags.index', compact('tags'));
    }

    public function store(TagRequest $request) {
        Tag::create($request->validated());
        return back()->with('success','Tag created.');
    }
}
