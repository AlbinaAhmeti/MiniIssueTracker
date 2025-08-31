<div class="flex flex-wrap gap-2">
  @forelse($issue->users as $u)
    <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs text-slate-800 ring-1 ring-slate-200">
      <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-indigo-600 text-white text-[10px]">
        {{ strtoupper(substr($u->name,0,1)) }}
      </span>
      <span>{{ $u->name }}</span>

      @auth
        @can('update',$issue)
          <button type="button"
                  class="ml-1 text-slate-500 hover:text-rose-600"
                  data-detach-url="{{ route('issues.members.detach', [$issue, $u]) }}">
            ✕
          </button>
        @endcan
      @endauth
    </span>
  @empty
    <span class="text-xs text-slate-500">No members</span>
  @endforelse
</div>
