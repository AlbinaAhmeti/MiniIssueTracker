<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\UpdateIssueRequest;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Issue::query()->with(['project', 'tags']);


        $query->when($request->filled('status'), fn($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('priority'), fn($q) => $q->where('priority', $request->string('priority')))
            ->when($request->filled('tag'), fn($q) => $q->whereHas('tags', fn($t) => $t->where('tags.id', $request->integer('tag'))));


        $issues = $query->latest()->paginate(10)->withQueryString();
        $tags = Tag::orderBy('name')->get();
        return view('issues.index', compact('issues', 'tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::orderBy('name')->get();
        return view('issues.create', [
            'projects' => $projects,
            'statuses' => Issue::STATUS,
            'priorities' => Issue::PRIORITY,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIssueRequest $request)
    {
        $issue = Issue::create($request->validated());
        return redirect()->route('issues.show', $issue)->with('success', 'Issue created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Issue $issue)
    {
        $issue->load(['project', 'tags', 'comments']);
        $allTags = Tag::orderBy('name')->get();
        return view('issues.show', compact('issue', 'allTags'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Issue $issue)
    {
        $projects = Project::orderBy('name')->get();
        return view('issues.edit', [
            'issue' => $issue,
            'projects' => $projects,
            'statuses' => Issue::STATUS,
            'priorities' => Issue::PRIORITY,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Issue $issue)
    {

        $issue->update($request->validated());
        return redirect()->route('issues.show', $issue)->with('success', 'Issue updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Issue $issue)
    {
        $issue->delete();
        return redirect()->route('issues.index')->with('success', 'Issue deleted');
    }
}
