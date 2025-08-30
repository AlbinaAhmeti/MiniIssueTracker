@extends('layouts.app')
@section('content')
<div class="flex justify-between items-center mb-4">
  <h1 class="text-2xl font-bold">Projects</h1>
  <a href="{{ route('projects.create') }}" class="px-3 py-2 bg-indigo-600 text-white rounded">New Project</a>
</div>

@foreach($projects as $project)
  <div class="bg-white p-4 rounded shadow mb-3">
    <div class="flex justify-between">
      <div>
        <a class="font-semibold" href="{{ route('projects.show', $project) }}">{{ $project->name }}</a>
        <p class="text-sm text-slate-600">{{ $project->description }}</p>
      </div>
      <div class="text-sm text-slate-500">{{ $project->issues_count }} issues</div>
    </div>
  </div>
@endforeach

{{ $projects->links() }}
@endsection
