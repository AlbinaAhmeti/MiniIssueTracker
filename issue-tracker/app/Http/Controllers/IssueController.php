<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Tag;
use App\Models\Issue;
use App\Http\Requests\IssueRequest;

class IssueController extends Controller
{
    public function index(Project $project = null)
    {
        $q = Issue::query()
            ->with(['project', 'tags'])
            ->withCount('comments')
            ->latest();

        if ($project) $q->where('project_id', $project->id);
        if ($s = request('status'))   $q->where('status', $s);
        if ($p = request('priority')) $q->where('priority', $p);
        if ($t = request('tag'))      $q->whereHas('tags', fn($qq) => $qq->where('name', $t));

        if ($term = trim(request('q', ''))) {
            $q->where(function ($qq) use ($term) {
                $qq->where('title', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
            });
        }

        $issues = $q->paginate(10)->withQueryString();
        $tags   = Tag::orderBy('name')->get();

        $stColor = [
            'open' => 'bg-emerald-100 text-emerald-700',
            'in_progress' => 'bg-amber-100 text-amber-700',
            'closed' => 'bg-slate-200 text-slate-700',
        ];
        $prColor = [
            'low' => 'bg-sky-100 text-sky-700',
            'medium' => 'bg-indigo-100 text-indigo-700',
            'high' => 'bg-rose-100 text-rose-700',
        ];

        return view('issues.index', compact('issues', 'tags', 'project', 'stColor', 'prColor'));
    }

    public function search(Request $request)
{
    $q = Issue::query()
        ->with(['project', 'tags'])
        ->withCount('comments')
        ->latest();

    if ($pid = $request->input('project_id'))  $q->where('project_id', $pid);
    if ($s   = $request->input('status'))      $q->where('status', $s);
    if ($p   = $request->input('priority'))    $q->where('priority', $p);
    if ($t   = $request->input('tag'))         $q->whereHas('tags', fn($qq) => $qq->where('name', $t));
    if ($term = trim($request->input('q', ''))) {
        $q->where(function ($qq) use ($term) {
            $qq->where('title', 'like', "%{$term}%")
               ->orWhere('description', 'like', "%{$term}%");
        });
    }

    $issues = $q->paginate(10)->withQueryString();

    $stColor = [
        'open' => 'bg-emerald-100 text-emerald-700',
        'in_progress' => 'bg-amber-100 text-amber-700',
        'closed' => 'bg-slate-200 text-slate-700',
    ];
    $prColor = [
        'low' => 'bg-sky-100 text-sky-700',
        'medium' => 'bg-indigo-100 text-indigo-700',
        'high' => 'bg-rose-100 text-rose-700',
    ];

    $html = view('issues._items', compact('issues', 'stColor', 'prColor'))->render();

    return response()->json([
        'html'  => $html,
        'count' => $issues->total(),
    ]);
}

    public function create(Project $project)
    {
        $this->authorize('create', [\App\Models\Issue::class, $project]);
        return view('issues.create', [
            'project' => $project,
            'statuses' => ['open', 'in_progress', 'closed'],
            'priorities' => ['low', 'medium', 'high']
        ]);
    }

    public function store(IssueRequest $request, Project $project)
    {
        $this->authorize('create', [\App\Models\Issue::class, $project]);
        $data = $request->validated();
        $data['project_id'] = $project->id;
        $data['created_by'] = $request->user()->id;
        $issue = Issue::create($data);
        return redirect()->route('issues.show', $issue)->with('success', 'Issue created.');
    }

    public function show(Issue $issue)
    {
        $issue->load(['project', 'tags', 'users'])->loadCount('comments');
        $allTags = \App\Models\Tag::orderBy('name')->get();
        $allUsers = \App\Models\User::orderBy('name')->get();
        return view('issues.show', compact('issue', 'allTags', 'allUsers'));
    }


    public function edit(Issue $issue)
    {
        $this->authorize('update', $issue);
        return view('issues.edit', [
            'issue' => $issue,
            'statuses' => ['open', 'in_progress', 'closed'],
            'priorities' => ['low', 'medium', 'high']
        ]);
    }

    public function update(IssueRequest $request, Issue $issue)
    {
        $this->authorize('update', $issue);
        $issue->update($request->validated());
        return redirect()->route('issues.show', $issue)->with('success', 'Issue updated.');
    }

    public function destroy(Issue $issue)
    {
        $this->authorize('delete', $issue);
        $project = $issue->project;
        $issue->delete();
        return redirect()->route('projects.show', $project)->with('success', 'Issue deleted.');
    }

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }
}
