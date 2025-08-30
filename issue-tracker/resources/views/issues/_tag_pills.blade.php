<div class="flex flex-wrap gap-2">
  @foreach($issue->tags as $tag)
    <span class="inline-flex items-center gap-2 text-xs px-2 py-1 rounded bg-slate-100">
      <span class="w-2 h-2 rounded-full" @style(['background-color: ' . ($tag->color ?? '#64748b')])></span>
      {{ $tag->name }}
      <button class="text-red-600" title="Detach" data-detach-url="{{ route('issues.tags.detach', [$issue,$tag]) }}">✕</button>
    </span>
  @endforeach
  @if($issue->tags->isEmpty())
    <span class="text-sm text-slate-500">No tags</span>
  @endif
</div>
