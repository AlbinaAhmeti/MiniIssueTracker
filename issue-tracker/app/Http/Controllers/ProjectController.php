<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Http\Requests\ProjectRequest;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::withCount('issues')->latest()->paginate(10);
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $this->authorize('create', Project::class);
        return view('projects.create');
    }

    public function store(ProjectRequest $request)
    {
        $this->authorize('create', Project::class);
        $data = $request->validated();

        $project = $request->user()->ownedProjects()->create($data);

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Project created.');
    }

    public function show(Project $project)
    {
        $issues = $project->issues()->with(['tags'])->withCount('comments')->latest()->paginate(10);
        return view('projects.show', compact('project', 'issues'));
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);
        return view('projects.edit', compact('project'));
    }

    public function update(ProjectRequest $request, Project $project)
    {
        $this->authorize('update', $project);
        $project->update($request->validated());
        return redirect()->route('projects.show', $project)->with('success', 'Project updated.');
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted.');
    }

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);

        $this->authorizeResource(\App\Models\Project::class, 'project', [
            'except' => ['index', 'show']
        ]);
    }
}
