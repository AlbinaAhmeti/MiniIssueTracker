@extends('layouts.app')
@section('content')
<h1 class="text-2xl font-bold mb-2">{{ $issue->title }}</h1>
<div class="mb-2 text-sm text-slate-500">
  Project: <a class="underline" href="{{ route('projects.show',$issue->project) }}">{{ $issue->project->name }}</a>
</div>
<p class="mb-4">{{ $issue->description }}</p>

<div class="flex items-center gap-2 mb-3">
  <span class="text-xs px-2 py-1 bg-slate-100 rounded">{{ $issue->status }}</span>
  <span class="text-xs px-2 py-1 bg-slate-100 rounded">{{ $issue->priority }}</span>
  @if($issue->due_date)
  <span class="text-xs px-2 py-1 bg-yellow-100 rounded">Due: {{ $issue->due_date->toDateString() }}</span>
  @endif
</div>

<div class="flex justify-between items-center mb-2">
  <h2 class="text-lg font-semibold">Tags</h2>
  <button id="btn-manage-tags" class="px-3 py-2 bg-slate-200 rounded">Manage Tags</button>
</div>
<div id="tag-pills">
  @include('issues._tag_pills',['issue'=>$issue])
</div>

<hr class="my-5">

<h2 class="text-lg font-semibold mb-2">Comments (<span id="comment-count">{{ $issue->comments_count }}</span>)</h2>

<form id="new-comment-form" class="bg-white p-3 rounded shadow mb-3"
  data-store-url="{{ route('issues.comments.store',$issue) }}">
  @csrf
  <div class="grid md:grid-cols-2 gap-3">
    <div>
      <label class="block text-sm">Your name</label>
      <input name="author_name" class="border rounded p-2 w-full">
      <div class="text-sm text-red-600 mt-1" data-error="author_name"></div>
    </div>
    <div class="md:col-span-2">
      <label class="block text-sm">Comment</label>
      <textarea name="body" class="border rounded p-2 w-full" rows="3"></textarea>
      <div class="text-sm text-red-600 mt-1" data-error="body"></div>
    </div>
  </div>
  <button type="submit" class="mt-3 px-3 py-2 bg-indigo-600 text-white rounded">Add Comment</button>
</form>

<div id="comment-list" data-fetch-url="{{ route('issues.comments.index',$issue) }}"></div>
<div class="mt-3"><button id="load-more" class="hidden px-3 py-2 bg-slate-200 rounded">Load more</button></div>

<div id="tag-modal" class="fixed inset-0 bg-black/30 hidden items-center justify-center">
  <div class="bg-white p-4 rounded w-full max-w-md">
    <div class="flex justify-between items-center mb-2">
      <h3 class="font-semibold">Attach a tag</h3>
      <button id="close-tag-modal" class="text-slate-500">✕</button>
    </div>
    <div class="flex gap-2 items-end">
      <select id="tag-select" class="border rounded p-2 flex-1">
        @foreach($allTags as $t)
        <option value="{{ $t->id }}">{{ $t->name }}</option>
        @endforeach
      </select>
      <button id="btn-attach-tag"
        data-attach-url="{{ route('issues.tags.attach',$issue) }}"
        class="px-3 py-2 bg-indigo-600 text-white rounded">Attach</button>
    </div>
  </div>
</div>

<script>
  const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  const listEl = document.getElementById('comment-list');
  const loadMoreBtn = document.getElementById('load-more');
  let nextUrl = null;

  async function loadComments(url) {
    const r = await fetch(url);
    const d = await r.json();
    if (!listEl.innerHTML) listEl.innerHTML = d.html;
    else listEl.insertAdjacentHTML('beforeend', d.html);
    nextUrl = d.next;
    loadMoreBtn.classList.toggle('hidden', !nextUrl);
  }
  loadComments(listEl.dataset.fetchUrl);
  loadMoreBtn.addEventListener('click', () => nextUrl && loadComments(nextUrl));

  const form = document.getElementById('new-comment-form');
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    form.querySelectorAll('[data-error]').forEach(el => el.textContent = '');
    const fd = new FormData(form);
    const r = await fetch(form.dataset.storeUrl, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrf,
        'Accept': 'application/json'
      },
      body: fd
    });
    if (r.status === 201) {
      const d = await r.json();
      listEl.insertAdjacentHTML('afterbegin', d.html);
      form.reset();
      document.getElementById('comment-count').textContent = d.count;
    } else if (r.status === 422) {
      const ejson = await r.json();
      Object.entries(ejson.errors).forEach(([f, msgs]) => {
        const t = form.querySelector(`[data-error="${f}"]`);
        if (t) t.textContent = msgs.join(', ');
      });
    }
  });

  const modal = document.getElementById('tag-modal');
  document.getElementById('btn-manage-tags').addEventListener('click', () => modal.classList.remove('hidden'));
  document.getElementById('close-tag-modal').addEventListener('click', () => modal.classList.add('hidden'));

  document.getElementById('btn-attach-tag').addEventListener('click', async () => {
    const tagId = document.getElementById('tag-select').value;
    const url = document.getElementById('btn-attach-tag').dataset.attachUrl;
    const r = await fetch(url, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrf,
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        tag_id: tagId
      })
    });
    const d = await r.json();
    document.getElementById('tag-pills').innerHTML = d.html;
  });

  document.getElementById('tag-pills').addEventListener('click', async (e) => {
    const btn = e.target.closest('[data-detach-url]');
    if (!btn) return;
    const r = await fetch(btn.dataset.detachUrl, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': csrf,
        'Accept': 'application/json'
      }
    });
    const d = await r.json();
    document.getElementById('tag-pills').innerHTML = d.html;
  });
</script>
@endsection