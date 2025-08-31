<div class="flex flex-wrap gap-2">
  @foreach($issue->tags as $tag)
  <span class="inline-flex items-center gap-2 text-xs px-2 py-1 rounded bg-slate-100">
    <span class="w-2 h-2 rounded-full" @style(['background-color: ' . ($tag->color ?? ' #64748b')])></span>
    {{ $tag->name }}
    @auth
    @can('manageTags', $issue)
    <button type="button"
      class="ml-1 text-slate-500 hover:text-rose-600"
      data-detach-url="{{ route('issues.tags.detach', [$issue, $tag]) }}">
      ✕
    </button>
    @endcan
    @endauth

  </span>
  @endforeach
  @if($issue->tags->isEmpty())
  <span class="text-sm text-slate-500">No tags</span>
  @endif
</div>