@if($issues->isEmpty())
  <div class="rounded-2xl border border-dashed border-slate-300 bg-white/60 p-10 text-center text-slate-600">
    No issues found. ✨
  </div>
@else
  <div class="grid gap-4">
    @foreach($issues as $issue)
      @php
        $statusKey = $issue->status instanceof \BackedEnum
          ? $issue->status->value
          : ($issue->status instanceof \UnitEnum ? $issue->status->name : (string) $issue->status);

        $priorityKey = $issue->priority instanceof \BackedEnum
          ? $issue->priority->value
          : ($issue->priority instanceof \UnitEnum ? $issue->priority->name : (string) $issue->priority);

        $stCls = $stColor[$statusKey]   ?? 'bg-slate-100 text-slate-700';
        $prCls = $prColor[$priorityKey] ?? 'bg-slate-100 text-slate-700';
      @endphp

      <div class="group rounded-2xl border border-slate-200 bg-white/70 p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
        <div class="flex justify-between items-start gap-3">
          <div>
            <a class="text-base font-semibold text-slate-900 hover:underline"
               href="{{ route('issues.show',$issue) }}">{{ $issue->title }}</a>
            <div class="text-sm text-slate-500">Project: {{ $issue->project->name }}</div>

            <div class="mt-2 flex items-center gap-2">
              <span class="rounded-full px-2.5 py-0.5 text-xs {{ $stCls }}">{{ $statusKey }}</span>
              <span class="rounded-full px-2.5 py-0.5 text-xs {{ $prCls }}">{{ $priorityKey }}</span>
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
