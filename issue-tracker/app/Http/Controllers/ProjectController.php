<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Http\Requests\ProjectRequest;

class ProjectController extends Controller
{
    public function index() {
        $projects = Project::withCount('issues')->latest()->paginate(10);
        return view('projects.index', compact('projects'));
    }

    public function create() { return view('projects.create'); }

    public function store(ProjectRequest $request) {
        $project = Project::create($request->validated());
        return redirect()->route('projects.show', $project)->with('success','Project created.');
    }

    public function show(Project $project) {
        // Shfaq projektin me issues (eager load për të shmangur N+1)
        $issues = $project->issues()->with(['tags'])->withCount('comments')->latest()->paginate(10);
        return view('projects.show', compact('project','issues'));
    }

    public function edit(Project $project) { return view('projects.edit', compact('project')); }

    public function update(ProjectRequest $request, Project $project) {
        $project->update($request->validated());
        return redirect()->route('projects.show', $project)->with('success','Project updated.');
    }

    public function destroy(Project $project) {
        $project->delete();
        return redirect()->route('projects.index')->with('success','Project deleted.');
    }
}

