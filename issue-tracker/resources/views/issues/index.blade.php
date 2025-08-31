@extends('layouts.app')

@section('content')
@php
  $statuses   = ['open','in_progress','closed'];
  $priorities = ['low','medium','high'];

  $stColor = [
    'open'         => 'bg-emerald-100 text-emerald-700',
    'in_progress'  => 'bg-amber-100 text-amber-700',
    'closed'       => 'bg-slate-200 text-slate-700',
  ];
  $prColor = [
    'low'    => 'bg-sky-100 text-sky-700',
    'medium' => 'bg-indigo-100 text-indigo-700',
    'high'   => 'bg-rose-100 text-rose-700',
  ];

  $hasFilters = request()->filled('status') || request()->filled('priority') || request()->filled('tag');
@endphp

<div class="mb-6 flex items-center justify-between">
  <h1 class="text-2xl font-bold tracking-tight">
    {{ isset($project) ? "Issues for: {$project->name}" : 'All Issues' }}
  </h1>

  @isset($project)
    <a href="{{ route('projects.issues.create',$project) }}"
       class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-500
              px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:shadow-md active:translate-y-px">
      New Issue
    </a>
  @endisset
</div>

<form method="GET" class="rounded-2xl border border-slate-200 bg-white/70 p-4 shadow-sm mb-6">
  <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4 items-end">
    <div>
      <label class="mb-1 block text-sm font-medium text-slate-700">Status</label>
      <select name="status"
              class="w-full rounded-xl border border-slate-200 bg-white/70 px-3 py-2 shadow-sm
                     focus:outline-none focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500">
        <option value="">All</option>
        @foreach($statuses as $s)
          <option value="{{ $s }}" @selected(request('status')===$s)>{{ $s }}</option>
        @endforeach
      </select>
    </div>

    <div>
      <label class="mb-1 block text-sm font-medium text-slate-700">Priority</label>
      <select name="priority"
              class="w-full rounded-xl border border-slate-200 bg-white/70 px-3 py-2 shadow-sm
                     focus:outline-none focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500">
        <option value="">All</option>
        @foreach($priorities as $p)
          <option value="{{ $p }}" @selected(request('priority')===$p)>{{ $p }}</option>
        @endforeach
      </select>
    </div>

    <div>
      <label class="mb-1 block text-sm font-medium text-slate-700">Tag</label>
      <select name="tag"
              class="w-full rounded-xl border border-slate-200 bg-white/70 px-3 py-2 shadow-sm
                     focus:outline-none focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500">
        <option value="">All</option>
        @foreach($tags as $t)
          <option value="{{ $t->name }}" @selected(request('tag')===$t->name)>{{ $t->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="flex gap-2">
      <button class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-white shadow-sm
                     transition hover:bg-indigo-700">
        Filter
      </button>

      @if($hasFilters)
        <a href="{{ isset($project) ? route('projects.issues.index',$project) : route('issues.index') }}"
           class="inline-flex items-center rounded-xl px-3 py-2 text-sm text-slate-700 bg-slate-100 hover:bg-slate-200">
          Reset
        </a>
      @endif
    </div>
  </div>
</form>

@if($issues->isEmpty())
  <div class="rounded-2xl border border-dashed border-slate-300 bg-white/60 p-10 text-center text-slate-600">
    No issues found. Try changing filters. ✨
  </div>
@else
  <div class="grid gap-4">
    @foreach($issues as $issue)
      @php
        if ($issue->status instanceof \BackedEnum) {
          $statusKey = $issue->status->value;
        } elseif ($issue->status instanceof \UnitEnum) {
          $statusKey = $issue->status->name;
        } else {
          $statusKey = (string) $issue->status;
        }

        if ($issue->priority instanceof \BackedEnum) {
          $priorityKey = $issue->priority->value;
        } elseif ($issue->priority instanceof \UnitEnum) {
          $priorityKey = $issue->priority->name;
        } else {
          $priorityKey = (string) $issue->priority;
        }

        $stCls = $stColor[$statusKey]   ?? 'bg-slate-100 text-slate-700';
        $prCls = $prColor[$priorityKey] ?? 'bg-slate-100 text-slate-700';
      @endphp

      <div class="group rounded-2xl border border-slate-200 bg-white/70 p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
        <div class="flex justify-between items-start gap-3">
          <div>
            <a class="text-base font-semibold text-slate-900 hover:underline"
               href="{{ route('issues.show',$issue) }}">{{ $issue->title }}</a>
            <div class="text-sm text-slate-500">Project: {{ $issue->project->name }}</div>
            <div class="mt-2">
              @include('issues._tag_pills',['issue'=>$issue])
            </div>
          </div>

          <div class="flex items-center gap-2">
            <span class="rounded-full px-2.5 py-0.5 text-xs {{ $stCls }}">{{ $statusKey }}</span>
            <span class="rounded-full px-2.5 py-0.5 text-xs {{ $prCls }}">{{ $priorityKey }}</span>

            <a href="{{ route('issues.edit', $issue) }}"
               class="inline-flex items-center rounded-lg px-2 py-1 text-sm text-indigo-600 hover:bg-indigo-50">
              Edit
            </a>
            <form action="{{ route('issues.destroy', $issue) }}" method="POST"
                  onsubmit="return confirm('Delete this issue?')">
              @csrf @method('DELETE')
              <button class="inline-flex items-center rounded-lg px-2 py-1 text-sm text-rose-600 hover:bg-rose-50">
                Delete
              </button>
            </form>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="mt-6">
    {{ $issues->links() }}
  </div>
@endif
@endsection
