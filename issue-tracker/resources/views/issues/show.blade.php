@extends('layouts.app')
@section('content')

@php

$norm = fn($v) => $v instanceof \BackedEnum ? $v->value : ($v instanceof \UnitEnum ? $v->name : (string) $v);
$statusVal = $norm($issue->status ?? 'open');
$priorityVal = $norm($issue->priority ?? 'medium');

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

$dueClass = $dueText = null;
if ($issue->due_date) {
if ($issue->due_date->isPast() && $statusVal !== 'closed') {
$dueClass = 'bg-rose-100 text-rose-700';
$dueText = 'Overdue';
} elseif ($issue->due_date->diffInDays() <= 7) {
  $dueClass='bg-amber-100 text-amber-700' ;
  $dueText='Due ' .$issue->due_date->diffForHumans();
  } else {
  $dueClass = 'bg-slate-100 text-slate-700';
  $dueText = 'Due '.$issue->due_date->toDateString();
  }
  }

  $from = request('from');
  if ($from === 'project') {
  $backUrl = route('projects.show', $issue->project);
  $backLabel = 'Back to Project';
  } elseif ($from === 'issues') {
  $backUrl = route('issues.index');
  $backLabel = 'Back to Issues';
  } else {
  $prev = url()->previous();
  if ($prev && str_contains($prev, '/projects/')) {
  $backUrl = route('projects.show', $issue->project);
  $backLabel = 'Back to Project';
  } else {
  $backUrl = route('issues.index');
  $backLabel = 'Back to Issues';
  }
  }
  @endphp

  <div class="mt-6 mb-4">
    <a href="{{ $backUrl }}"
      class="group inline-flex items-center gap-2 rounded-full bg-white/70 px-3 py-1.5 text-sm
            text-slate-700 shadow-sm ring-1 ring-slate-200 transition
            hover:bg-slate-50 hover:shadow focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
      <svg class="h-4 w-4 -ml-0.5 transition group-hover:-translate-x-0.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
        <path d="M12.78 4.22a.75.75 0 0 1 0 1.06L8.06 10l4.72 4.72a.75.75 0 1 1-1.06 1.06l-5.25-5.25a.75.75 0 0 1 0-1.06l5.25-5.25a.75.75 0 0 1 1.06 0z" />
      </svg>
      <span>{{ $backLabel }}</span>
    </a>
  </div>


  <div class="rounded-2xl border border-slate-200 bg-white/70 p-5 shadow-sm">
    <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
      <div>
        <h1 class="text-2xl font-bold tracking-tight">{{ $issue->title }}</h1>
        <div class="mt-1 text-sm text-slate-600">
          Project:
          <a class="font-medium text-indigo-600 hover:underline" href="{{ route('projects.show',$issue->project) }}">
            {{ $issue->project->name }}
          </a>
        </div>
        @if($issue->description)
        <p class="mt-3 max-w-3xl text-slate-800">{{ $issue->description }}</p>
        @endif
      </div>

      <div class="flex flex-wrap items-center gap-2">
        <span class="rounded-full px-2.5 py-0.5 text-xs {{ $stColor[$statusVal] ?? 'bg-slate-100 text-slate-700' }}">
          {{ str_replace('_',' ',$statusVal) }}
        </span>
        <span class="rounded-full px-2.5 py-0.5 text-xs {{ $prColor[$priorityVal] ?? 'bg-slate-100 text-slate-700' }}">
          {{ $priorityVal }}
        </span>
        @if($dueText)
        <span class="rounded-full px-2.5 py-0.5 text-xs {{ $dueClass }}">{{ $dueText }}</span>
        @endif
      </div>
    </div>

    <div class="mt-5 flex items-center justify-between">
      <h2 class="text-lg font-semibold">Tags</h2>
      <button id="btn-manage-tags"
        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-500
                   px-3 py-2 text-sm font-medium text-white shadow-sm transition hover:shadow-md active:translate-y-px">
        Manage Tags
      </button>
    </div>

    <div id="tag-pills" class="mt-2">
      @include('issues._tag_pills',['issue'=>$issue])
    </div>
  </div>

  <div class="mt-6">
    <div class="mb-2 flex items-center justify-between">
      <h2 class="text-lg font-semibold">
        Comments (<span id="comment-count">{{ $issue->comments_count }}</span>)
      </h2>
    </div>

    <form id="new-comment-form"
      class="rounded-2xl border border-slate-200 bg-white/70 p-4 shadow-sm mb-4"
      data-store-url="{{ route('issues.comments.store',$issue) }}">
      @csrf
      <div class="grid md:grid-cols-2 gap-4">
        <div>
          <label class="mb-1 block text-sm font-medium text-slate-700">Your name</label>
          <input name="author_name"
            class="w-full rounded-xl border border-slate-200 bg-white/70 px-3 py-2 shadow-sm transition
                      focus:outline-none focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500">
          <div class="text-xs text-rose-600 mt-1" data-error="author_name"></div>
        </div>
        <div class="md:col-span-2">
          <label class="mb-1 block text-sm font-medium text-slate-700">Comment</label>
          <textarea name="body" rows="3"
            class="w-full rounded-xl border border-slate-200 bg-white/70 px-3 py-2 shadow-sm transition
                         focus:outline-none focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500"></textarea>
          <div class="text-xs text-rose-600 mt-1" data-error="body"></div>
        </div>
      </div>

      <button type="submit"
        class="mt-3 inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-white shadow-sm transition hover:bg-indigo-700">
        Add Comment
      </button>
    </form>

    <div id="comment-list"
      class="rounded-2xl border border-slate-200 bg-white/70 p-4 shadow-sm"
      data-fetch-url="{{ route('issues.comments.index',$issue) }}"></div>

    <div class="mt-3">
      <button id="load-more"
        class="hidden rounded-xl bg-slate-100 px-3 py-2 text-sm text-slate-700 transition hover:bg-slate-200">
        Load more
      </button>
    </div>
  </div>

  <div id="tag-modal"
    class="fixed inset-0 z-50 hidden flex items-center justify-center p-4
            bg-black/40 backdrop-blur-sm">
    <div data-dialog
      class="w-full max-w-md origin-center rounded-2xl border border-slate-200 bg-white/90
              p-5 shadow-2xl ring-1 ring-white/60 transition duration-200 ease-out
              opacity-0 scale-95">
      <div class="mb-3 flex items-center justify-between">
        <h3 class="flex items-center gap-2 text-sm font-semibold text-slate-800">
          <span class="inline-block h-2.5 w-2.5 rounded-full bg-indigo-500"></span>
          Attach a tag
        </h3>
        <button id="close-tag-modal"
          class="rounded-full p-1.5 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700"
          aria-label="Close">
          ✕
        </button>
      </div>

      <div class="space-y-3">
        <div id="tag-preview"
          class="hidden items-center gap-2 rounded-full bg-slate-100 px-3 py-1.5 text-xs text-slate-800 ring-1 ring-slate-200">
          <span id="tag-preview-dot" class="h-2.5 w-2.5 rounded-full"></span>
          <span id="tag-preview-name" class="font-medium"></span>
        </div>

        <div class="flex items-end gap-2">
          <label class="sr-only" for="tag-select">Tag</label>
          <select id="tag-select"
            class="flex-1 rounded-xl border border-slate-200 bg-white/70 px-3 py-2 shadow-sm transition
                       focus:outline-none focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500">
            @foreach($allTags as $t)
            <option value="{{ $t->id }}" data-color="{{ $t->color ?? '#64748b' }}">
              {{ $t->name }}
            </option>
            @endforeach
          </select>

          <button id="btn-attach-tag"
            data-attach-url="{{ route('issues.tags.attach',$issue) }}"
            class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-500
                       px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:shadow-md active:translate-y-px">
            Attach
          </button>
        </div>
      </div>
    </div>
  </div>


  <div class="flex items-end gap-2">
    <label class="sr-only" for="tag-select">Tag</label>
    <select id="tag-select"
      class="flex-1 rounded-xl border border-slate-200 bg-white/70 px-3 py-2 shadow-sm transition
                       focus:outline-none focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500">
      @foreach($allTags as $t)
      <option value="{{ $t->id }}" data-color="{{ $t->color ?? '#64748b' }}">
        {{ $t->name }}
      </option>
      @endforeach
    </select>

    <button id="btn-attach-tag"
      data-attach-url="{{ route('issues.tags.attach',$issue) }}"
      class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-500
                       px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:shadow-md active:translate-y-px">
      Attach
    </button>
  </div>
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

      const submitBtn = form.querySelector('button[type="submit"]');
      submitBtn.disabled = true;
      submitBtn.classList.add('opacity-70', 'cursor-not-allowed');

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

      submitBtn.disabled = false;
      submitBtn.classList.remove('opacity-70', 'cursor-not-allowed');
    });

    const modal = document.getElementById('tag-modal');
    document.getElementById('btn-manage-tags').addEventListener('click', () => {
      modal.classList.remove('hidden');
      setTimeout(() => document.getElementById('tag-select')?.focus(), 0);
    });
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
      modal.classList.add('hidden');
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

    (function() {
      const modal = document.getElementById('tag-modal');
      const dialog = modal?.querySelector('[data-dialog]');
      const openBtn = document.getElementById('btn-manage-tags');
      const closeBtn = document.getElementById('close-tag-modal');

      function openModal() {
        if (!modal || !dialog) return;
        modal.classList.remove('hidden');
        requestAnimationFrame(() => {
          dialog.classList.remove('opacity-0', 'scale-95');
        });
        setTimeout(() => document.getElementById('tag-select')?.focus(), 50);
      }

      function closeModal() {
        if (!modal || !dialog) return;
        dialog.classList.add('opacity-0', 'scale-95');
        dialog.addEventListener('transitionend', () => modal.classList.add('hidden'), {
          once: true
        });
      }

      openBtn?.addEventListener('click', openModal);
      closeBtn?.addEventListener('click', closeModal);

      modal?.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
      });

      window.addEventListener('keydown', (e) => {
        if (!modal.classList.contains('hidden') && e.key === 'Escape') closeModal();
      });

      const sel = document.getElementById('tag-select');
      const prev = document.getElementById('tag-preview');
      const dot = document.getElementById('tag-preview-dot');
      const name = document.getElementById('tag-preview-name');

      function updatePreview() {
        if (!sel || !prev) return;
        const opt = sel.options[sel.selectedIndex];
        if (!opt) {
          prev.classList.add('hidden');
          return;
        }
        const color = opt.getAttribute('data-color') || '#64748b';
        dot.style.backgroundColor = color;
        name.textContent = opt.textContent.trim();
        prev.classList.remove('hidden');
      }
      sel?.addEventListener('change', updatePreview);
      updatePreview();
    })();
  </script>

  @endsection