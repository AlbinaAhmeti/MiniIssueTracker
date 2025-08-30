<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Tag;
use App\Models\Issue;
use App\Http\Requests\IssueRequest;

class IssueController extends Controller
{
    public function index(Project $project = null) {
        $q = Issue::query()->with(['project','tags'])->withCount('comments')->latest();

        if ($project) $q->where('project_id', $project->id);
        if ($s = request('status'))   $q->where('status', $s);
        if ($p = request('priority')) $q->where('priority', $p);
        if ($t = request('tag'))      $q->whereHas('tags', fn($qq)=>$qq->where('name',$t));

        $issues = $q->paginate(10)->withQueryString();
        $tags   = Tag::orderBy('name')->get();

        $projects = $project ? collect() : Project::orderBy('name')->get();

        return view('issues.index', compact('issues','tags','project'));
    }

    public function create(Project $project) {
        return view('issues.create', [
            'project' => $project,
            'statuses' => ['open','in_progress','closed'],
            'priorities' => ['low','medium','high']
        ]);
    }

    public function store(IssueRequest $request, Project $project) {
        // project_id vjen nga forma (ose e zëvendëson me $project->id)
        $data = $request->validated();
        $data['project_id'] = $project->id;
        $issue = Issue::create($data);
        return redirect()->route('issues.show', $issue)->with('success','Issue created.');
    }

    public function show(Issue $issue) {
        $issue->load(['project','tags'])->loadCount('comments');
        $allTags = Tag::orderBy('name')->get(); // për modalin attach/detach
        return view('issues.show', compact('issue','allTags'));
    }

    public function edit(Issue $issue) {
        return view('issues.edit', [
            'issue' => $issue,
            'statuses' => ['open','in_progress','closed'],
            'priorities' => ['low','medium','high']
        ]);
    }

    public function update(IssueRequest $request, Issue $issue) {
        $issue->update($request->validated());
        return redirect()->route('issues.show', $issue)->with('success','Issue updated.');
    }

    public function destroy(Issue $issue) {
        $project = $issue->project;
        $issue->delete();
        return redirect()->route('projects.show', $project)->with('success','Issue deleted.');
    }
}
