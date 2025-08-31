@extends('layouts.app')

@section('content')
@php
$start = $project->start_date ?? null;
$deadline = $project->deadline ?? null;

$dueClass = $dueLabel = null;
if ($deadline) {
if ($deadline->isPast()) {
$dueClass = 'bg-rose-100 text-rose-700';
$dueLabel = 'Overdue';
} elseif ($deadline->diffInDays() <= 7) {
  $dueClass='bg-amber-100 text-amber-700' ;
  $dueLabel='Due ' . $deadline->diffForHumans();
  } else {
  $dueClass = 'bg-emerald-100 text-emerald-700';
  $dueLabel = 'Due ' . $deadline->diffForHumans();
  }
  }
  @endphp

  <div class="mt-6 mb-3">
    <a href="{{ route('projects.index') }}"
      class="group inline-flex items-center gap-2 rounded-full bg-white/70 px-3 py-1.5 text-sm
            text-slate-700 shadow-sm ring-1 ring-slate-200 transition
            hover:bg-slate-50 hover:shadow focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
      <svg class="h-4 w-4 -ml-0.5 transition group-hover:-translate-x-0.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
        <path d="M12.78 4.22a.75.75 0 0 1 0 1.06L8.06 10l4.72 4.72a.75.75 0 1 1-1.06 1.06l-5.25-5.25a.75.75 0 0 1 0-1.06l5.25-5.25a.75.75 0 0 1 1.06 0z" />
      </svg>
      <span>Back to Projects</span>
    </a>
  </div>

  <div class="rounded-2xl border border-slate-200 bg-white/70 p-5 shadow-sm">
    <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
      <div>
        <h1 class="text-2xl font-bold tracking-tight">{{ $project->name }}</h1>
        @if($project->description)
        <p class="mt-1 max-w-3xl text-slate-700">{{ $project->description }}</p>
        @endif

        <div class="mt-3 flex flex-wrap items-center gap-2">
          @if($start)
          <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs text-slate-700">
            Start: {{ $start->toDateString() }}
          </span>
          @endif

          @if($deadline)
          <span class="rounded-full px-2.5 py-0.5 text-xs {{ $dueClass }}">
            {{ $dueLabel ?: 'Deadline: '.$deadline->toDateString() }}
          </span>
          @endif

          <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs text-slate-700">
            {{ $project->issues()->count() }} issues
          </span>
        </div>
      </div>

      @auth
      @if(auth()->id() === $project->owner_id)
      <div class="flex justify-end">
        <a href="{{ route('projects.issues.create', $project) }}"
          class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-500
                px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:shadow-md active:translate-y-px">
          New Issue
        </a>
      </div>
      @endif
      @endauth
    </div>

    @auth
    <div class="mt-3 flex justify-end gap-2">
      @can('update', $project)
      <a href="{{ route('projects.edit',$project) }}"
        class="inline-flex items-center rounded-xl px-3 py-2 text-sm text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition">
        Edit
      </a>
      @endcan

      @can('delete', $project)
      <form method="POST" action="{{ route('projects.destroy',$project) }}"
        onsubmit="return confirm('Delete project?')">
        @csrf @method('DELETE')
        <button
          class="inline-flex items-center rounded-xl px-3 py-2 text-sm text-rose-700 bg-rose-50 hover:bg-rose-100 transition">
          Delete
        </button>
      </form>
      @endcan
    </div>
    @endauth
  </div>

  <div class="mt-6 flex items-center justify-between">
    <h2 class="text-xl font-semibold">Issues</h2>
    <a href="{{ route('issues.index') }}" class="text-sm text-slate-600 hover:underline">View all issues</a>
  </div>

  @if($issues->isEmpty())
  <div class="mt-3 rounded-2xl border border-dashed border-slate-300 bg-white/60 p-10 text-center">
    <p class="text-slate-600">No issues yet.</p>

    @auth
    <a href="{{ route('projects.issues.create',$project) }}"
      class="mt-3 inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-white shadow-sm transition hover:bg-indigo-700">
      Create the first issue
    </a>
    @else
    <a href="{{ route('login') }}"
      class="mt-3 inline-flex items-center gap-2 rounded-xl bg-slate-800 px-4 py-2 text-white shadow-sm transition hover:bg-slate-900">
      Log in to create one
    </a>
    @endauth
  </div>
  @else
  <div class="mt-3 grid gap-4 md:grid-cols-2">
    @foreach($issues as $issue)
    @php
    $iDueClass = $iDueLabel = null;
    if ($issue->due_date) {
    if ($issue->due_date->isPast() && $issue->status !== 'closed') {
    $iDueClass = 'bg-rose-100 text-rose-700';
    $iDueLabel = 'Overdue';
    } elseif ($issue->due_date->diffInDays() <= 7) {
      $iDueClass='bg-amber-100 text-amber-700' ;
      $iDueLabel='Due ' . $issue->due_date->diffForHumans();
      } else {
      $iDueClass = 'bg-slate-100 text-slate-700';
      $iDueLabel = 'Due ' . $issue->due_date->toDateString();
      }
      }
      @endphp

      <div class="group rounded-2xl border border-slate-200 bg-white/70 p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
        <div class="flex items-start justify-between gap-3">
          <div>
            <a class="text-base font-semibold text-slate-900 hover:underline"
              href="{{ route('issues.show',$issue) }}">{{ $issue->title }}</a>

            <div class="mt-1 flex flex-wrap items-center gap-2">
              <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs text-slate-700">{{ $issue->status }}</span>
              <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs text-slate-700">{{ $issue->priority }}</span>
              @if($issue->due_date)
              <span class="rounded-full px-2.5 py-0.5 text-xs {{ $iDueClass }}">{{ $iDueLabel }}</span>
              @endif
            </div>

            <div class="mt-3">
              @include('issues._tag_pills',['issue'=>$issue])
            </div>
          </div>

          @auth
          <div class="flex items-center gap-2">
            @can('update', $issue)
            <a href="{{ route('issues.edit', $issue) }}"
              class="inline-flex items-center rounded-lg px-2 py-1 text-sm text-indigo-600 hover:bg-indigo-50">
              Edit
            </a>
            @endcan

            @can('delete', $issue)
            <form action="{{ route('issues.destroy', $issue) }}" method="POST"
              onsubmit="return confirm('Delete this issue?')">
              @csrf @method('DELETE')
              <button class="inline-flex items-center rounded-lg px-2 py-1 text-sm text-rose-600 hover:bg-rose-50">
                Delete
              </button>
            </form>
            @endcan
          </div>
          @endauth
        </div>
      </div>
      @endforeach
  </div>

  <div class="mt-6">
    {{ $issues->links() }}
  </div>
  @endif
  @endsection