@extends('layouts.app')
@section('content')
<h1 class="text-2xl font-bold mb-2">{{ $project->name }}</h1>
<p class="mb-4 text-slate-700">{{ $project->description }}</p>

<div class="flex gap-2 mb-4">
  <a href="{{ route('projects.edit',$project) }}" class="px-3 py-2 bg-slate-200 rounded">Edit</a>
  <form method="POST" action="{{ route('projects.destroy',$project) }}">
    @csrf @method('DELETE')
    <button class="px-3 py-2 bg-red-600 text-white rounded" onclick="return confirm('Delete project?')">Delete</button>
  </form>
  <a href="{{ route('projects.issues.create',$project) }}" class="px-3 py-2 bg-indigo-600 text-white rounded">New Issue</a>
</div>

<h2 class="text-xl font-semibold mb-2">Issues</h2>
@foreach($issues as $issue)
<div class="bg-white p-4 rounded shadow mb-3">
  <div class="flex justify-between items-start gap-3">
    <div>
      <a class="font-semibold" href="{{ route('issues.show',$issue) }}">{{ $issue->title }}</a>
      @include('issues._tag_pills',['issue'=>$issue])
    </div>

    <div class="flex items-center gap-2">
      <span class="text-xs px-2 py-1 rounded bg-slate-100">{{ $issue->status }} • {{ $issue->priority }}</span>

      <a href="{{ route('issues.edit', $issue) }}"
        class="text-indigo-600 text-sm px-2 py-1 rounded hover:underline">Edit</a>

      <form action="{{ route('issues.destroy', $issue) }}" method="POST"
        onsubmit="return confirm('Delete this issue?')">
        @csrf
        @method('DELETE')
        <button class="text-red-600 text-sm px-2 py-1 rounded">Delete</button>
      </form>
    </div>
  </div>
</div>
@endforeach

{{ $issues->links() }}
@endsection
