@extends('layouts.app')

@section('content')
@php
$statuses = ['open','in_progress','closed'];
$priorities = ['low','medium','high'];

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

$hasFilters = request()->filled('status')
|| request()->filled('priority')
|| request()->filled('tag')
|| request()->filled('q');
@endphp

<div class="mb-6 flex items-center justify-between">
  <h1 class="text-2xl font-bold tracking-tight">
    {{ isset($project) ? "Issues for: {$project->name}" : 'All Issues' }}
  </h1>

  @isset($project)
  @auth
  @can('create', [\App\Models\Issue::class, $project])
  <a href="{{ route('projects.issues.create',$project) }}"
    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-500
                  px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:shadow-md active:translate-y-px">
    New Issue
  </a>
  @endcan
  @endauth
  @endisset
</div>

<form method="GET" class="rounded-2xl border border-slate-200 bg-white/70 p-4 shadow-sm mb-6" id="filters-form">
  <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-5 items-end">
    <div>
      <label class="mb-1 block text-sm font-medium text-slate-700">Status</label>
      <select name="status" class="w-full rounded-xl border border-slate-200 bg-white/70 px-3 py-2 shadow-sm">
        <option value="">All</option>
        @foreach($statuses as $s)
        <option value="{{ $s }}" @selected(request('status')===$s)>{{ $s }}</option>
        @endforeach
      </select>
    </div>

    <div>
      <label class="mb-1 block text-sm font-medium text-slate-700">Priority</label>
      <select name="priority" class="w-full rounded-xl border border-slate-200 bg-white/70 px-3 py-2 shadow-sm">
        <option value="">All</option>
        @foreach($priorities as $p)
        <option value="{{ $p }}" @selected(request('priority')===$p)>{{ $p }}</option>
        @endforeach
      </select>
    </div>

    <div>
      <label class="mb-1 block text-sm font-medium text-slate-700">Tag</label>
      <select name="tag" class="w-full rounded-xl border border-slate-200 bg-white/70 px-3 py-2 shadow-sm">
        <option value="">All</option>
        @foreach($tags as $t)
        <option value="{{ $t->name }}" @selected(request('tag')===$t->name)>{{ $t->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="lg:col-span-2">
      <label class="mb-1 block text-sm font-medium text-slate-700">Search</label>
      <input type="text" name="q" id="search-input"
        value="{{ request('q','') }}"
        placeholder="Search title or description..."
        data-url="{{ route('issues.search') }}"
        data-project-id="{{ $project->id ?? '' }}"
        class="w-full rounded-xl border border-slate-200 bg-white/70 px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/60">
    </div>

    <div class="sm:col-span-2 lg:col-span-5 flex gap-2">
      <button class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-white shadow-sm hover:bg-indigo-700">
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

<div id="issue-list">
  @include('issues._items', ['issues' => $issues, 'stColor' => $stColor, 'prColor' => $prColor])
</div>

<script>
  (function() {
    const input = document.getElementById('search-input');
    const list = document.getElementById('issue-list');
    const form = document.getElementById('filters-form');
    if (!input || !list || !form) return;

    let timer = null;

    function params() {
      const fd = new FormData(form);
      fd.set('q', input.value || '');
      const pid = input.dataset.projectId;
      if (pid) fd.set('project_id', pid);
      return new URLSearchParams(fd);
    }

    async function doSearch() {
      const url = input.dataset.url + '?' + params().toString();
      const r = await fetch(url, {
        headers: {
          'Accept': 'application/json'
        }
      });
      if (!r.ok) return;
      const d = await r.json();
      list.innerHTML = d.html;
    }

    input.addEventListener('input', () => {
      clearTimeout(timer);
      timer = setTimeout(doSearch, 300);
    });

    form.querySelectorAll('select[name="status"], select[name="priority"], select[name="tag"]').forEach(sel => {
      sel.addEventListener('change', doSearch);
    });
  })();
</script>
@endsection