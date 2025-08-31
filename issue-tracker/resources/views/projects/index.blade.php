@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between">
  <h1 class="text-2xl font-bold tracking-tight">Projects</h1>

  {{-- Shfaq vetëm kur je i/e loguar --}}
  @auth
    <a href="{{ route('projects.create') }}"
      class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-500
             px-4 py-2 font-medium text-white shadow-sm transition hover:shadow-md active:translate-y-px">
      <span>New Project</span>
    </a>
  @endauth
  {{-- ose përdor @can nëse ke policy:
  @can('create', \App\Models\Project::class)
    ...butoni...
  @endcan
  --}}
</div>

@if($projects->isEmpty())
  <div class="rounded-2xl border border-dashed border-slate-300 bg-white/60 p-10 text-center">
    <p class="text-slate-600">No projects yet.</p>

    {{-- Në empty-state: link për krijim vetëm për user-at e loguar,
         për të tjerët ofro "Log in" --}}
    @auth
      <a href="{{ route('projects.create') }}"
        class="mt-3 inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-white shadow-sm transition hover:bg-indigo-700">
        Create your first project
      </a>
    @else
      <a href="{{ route('login') }}"
        class="mt-3 inline-flex items-center gap-2 rounded-xl bg-slate-800 px-4 py-2 text-white shadow-sm transition hover:bg-slate-900">
        Log in to create one
      </a>
    @endauth
  </div>
@else
  <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
    @foreach($projects as $project)
      @php
        $deadline = $project->deadline;
        $start = $project->start_date;
        $dueClass = $dueLabel = null;
        if ($deadline) {
          if ($deadline->isPast())      { $dueClass = 'bg-rose-100 text-rose-700';   $dueLabel = 'Overdue'; }
          elseif ($deadline->diffInDays() <= 7) { $dueClass = 'bg-amber-100 text-amber-700'; $dueLabel = 'Due '.$deadline->diffForHumans(); }
          else                          { $dueClass = 'bg-emerald-100 text-emerald-700'; $dueLabel = 'Due '.$deadline->diffForHumans(); }
        }
      @endphp

      <div class="group rounded-2xl border border-slate-200 bg-white/70 p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
        <div class="flex items-start justify-between gap-2">
          <a href="{{ route('projects.show', $project) }}"
             class="text-lg font-semibold text-slate-900 hover:underline">
            {{ $project->name }}
          </a>

          <span class="rounded-full bg-slate-100 px-3 py-0.5 text-xs text-slate-700">
            {{ $project->issues_count }} issues
          </span>
        </div>

        @if($project->description)
          <p class="mt-2 text-sm text-slate-600">
            {{ \Illuminate\Support\Str::limit($project->description, 120) }}
          </p>
        @endif

        <div class="mt-4 flex flex-wrap items-center gap-2">
          @if($start)
            <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs text-slate-700">
              Start: {{ $start->toDateString() }}
            </span>
          @endif

          @if($deadline)
            <span class="rounded-full px-2.5 py-0.5 text-xs {{ $dueClass }}">
              {{ $dueLabel ?: 'Due: '.$deadline->toDateString() }}
            </span>
          @endif
        </div>

        @auth
          <div class="mt-4 flex items-center gap-2">
            @can('update', $project)
              <a href="{{ route('projects.edit', $project) }}"
                 class="inline-flex items-center rounded-lg px-2 py-1 text-sm text-indigo-600 hover:bg-indigo-50">
                Edit
              </a>
            @endcan

            @can('delete', $project)
              <form action="{{ route('projects.destroy', $project) }}" method="POST"
                    onsubmit="return confirm('Delete this project?')">
                @csrf @method('DELETE')
                <button class="inline-flex items-center rounded-lg px-2 py-1 text-sm text-rose-600 hover:bg-rose-50">
                  Delete
                </button>
              </form>
            @endcan
          </div>
        @endauth
      </div>
    @endforeach
  </div>

  <div class="mt-6">
    {{ $projects->links() }}
  </div>
@endif
@endsection
