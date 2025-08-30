@extends('layouts.app')
@section('content')
<h1 class="text-2xl font-bold mb-4">
  {{ isset($project) ? "Issues for: {$project->name}" : 'All Issues' }}
</h1>

<form class="bg-white p-4 rounded shadow mb-4 flex gap-3 items-end">
  <div>
    <label class="block text-sm">Status</label>
    <select name="status" class="border rounded p-2">
      <option value="">All</option>
      @foreach(['open','in_progress','closed'] as $s)
      <option value="{{ $s }}" @selected(request('status')===$s)>{{ $s }}</option>
      @endforeach
    </select>
  </div>
  <div>
    <label class="block text-sm">Priority</label>
    <select name="priority" class="border rounded p-2">
      <option value="">All</option>
      @foreach(['low','medium','high'] as $p)
      <option value="{{ $p }}" @selected(request('priority')===$p)>{{ $p }}</option>
      @endforeach
    </select>
  </div>
  <div>
    <label class="block text-sm">Tag</label>
    <select name="tag" class="border rounded p-2">
      <option value="">All</option>
      @foreach($tags as $t)
      <option value="{{ $t->name }}" @selected(request('tag')===$t->name)>{{ $t->name }}</option>
      @endforeach
    </select>
  </div>
  <button class="px-3 py-2 bg-indigo-600 text-white rounded">Filter</button>
</form>

@foreach($issues as $issue)
<div class="bg-white p-4 rounded shadow mb-3">
  <div class="flex justify-between items-start gap-3">
    <div>
      <a class="font-semibold" href="{{ route('issues.show',$issue) }}">{{ $issue->title }}</a>
      <div class="text-sm text-slate-500">Project: {{ $issue->project->name }}</div>
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