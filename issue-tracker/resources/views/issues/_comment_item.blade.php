<div class="bg-white p-3 rounded shadow mb-2">
  <div class="text-sm text-slate-500 mb-1">{{ $comment->author_name }} • {{ $comment->created_at->diffForHumans() }}</div>
  <div>{{ $comment->body }}</div>
</div>
