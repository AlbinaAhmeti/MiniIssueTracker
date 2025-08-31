@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between">
  <h1 class="text-2xl font-bold tracking-tight">Tags</h1>
  <span class="text-sm text-slate-500">{{ $tags->count() }} total</span>
</div>

@include('partials.errors')

<form method="POST" action="{{ route('tags.store') }}"
  class="rounded-2xl border border-slate-200 bg-white/70 p-5 shadow-sm mb-6">
  @csrf
  <div class="grid md:grid-cols-3 gap-6">

    <div class="md:col-span-2">
      <label class="mb-1 block text-sm font-medium text-slate-700">Name (unique)</label>
      <input name="name" id="tag-name"
        value="{{ old('name') }}"
        placeholder="Design, Backend, Urgent…"
        required
        class="w-full rounded-xl border border-slate-200 bg-white/70 px-3 py-2 shadow-sm transition
                    focus:outline-none focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500
                    @error('name') border-rose-400 ring-1 ring-rose-300 @enderror">
      @error('name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
    </div>

    <div>
      <label class="mb-1 block text-sm font-medium text-slate-700">Color (hex or css name)</label>
      <input name="color" id="tag-color"
        value="{{ old('color') }}" placeholder="#22c55e"
        class="w-full rounded-xl border border-slate-200 bg-white/70 px-3 py-2 shadow-sm transition
                    focus:outline-none focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500
                    @error('color') border-rose-400 ring-1 ring-rose-300 @enderror">
      <p class="mt-1 text-xs text-slate-500">Shembuj: <code>#22c55e</code>, <code>red</code>, <code>royalblue</code></p>
      @error('color') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
    </div>
  </div>

  <div class=" flex items-center justify-between flex-wrap gap-3">
    <div id="tag-preview"
      class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs text-slate-800 ring-1 ring-slate-200 bg-slate-100">
      <span id="tag-preview-dot" class="inline-block h-2.5 w-2.5 rounded-full bg-slate-400"></span>
      <span id="tag-preview-text">Preview</span>
    </div>

    <div class="flex items-center gap-2 mt-4">
      @foreach(['#ef4444','#f59e0b','#22c55e','#06b6d4','#3b82f6','#6366f1','#a855f7','#ec4899'] as $swatch)
      <button type="button"
        class="h-7 w-7 rounded-full ring-1 ring-slate-200 transition hover:scale-105 data-[active=true]:ring-2 data-[active=true]:ring-indigo-500"
        @style(['background-color: '.$swatch])
                onclick="window.__pickSwatch(' {{ $swatch }}')"></button>
      @endforeach
    </div>
  </div>

  <div class="mt-4">
    <button class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-500
                   px-4 py-2 font-medium text-white shadow-sm transition hover:shadow-md active:translate-y-px">
      <span>Create Tag</span>
    </button>
  </div>
</form>

@if($tags->isEmpty())
<div class="rounded-2xl border border-dashed border-slate-300 bg-white/60 p-10 text-center text-slate-600">
  No tags yet. Create your first one! ✨
</div>
@else
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
  @foreach($tags as $t)
  <div class="group rounded-2xl border border-slate-200 bg-white/70 p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
    <div class="flex items-start justify-between gap-2">
      <div class="flex items-center gap-2">
        <span class="inline-block h-3 w-3 rounded-full"
          @style(['background-color: ' . ($t->color ?? ' #64748b')])></span>
        <span class="font-semibold text-slate-900">{{ $t->name }}</span>
      </div>
      <span class="text-xs text-slate-500">#{{ $t->id }}</span>
    </div>

    @if($t->color)
    <div class="mt-2 inline-flex items-center gap-2 rounded-full px-2.5 py-0.5 text-xs ring-1 ring-slate-200"
      @style(['background-color: ' . ($t->color)])>
            <svg class="h-3.5 w-3.5 opacity-80" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>
            <span class="opacity-90 text-slate-900/90">{{ $t->color }}</span>
          </div>
        @endif
      </div>
    @endforeach
  </div>
@endif

<script>
  const $name = document.getElementById(' tag-name');
      const $color=document.getElementById('tag-color');
      const $dot=document.getElementById('tag-preview-dot');
      const $text=document.getElementById('tag-preview-text');

      function updatePreview() {
      const name=$name.value.trim() || 'Preview' ;
      const color=$color.value.trim() || '#64748b' ;
      $text.textContent=name;
      $dot.style.backgroundColor=color;

      document.querySelectorAll('button[onclick^="window.__pickSwatch" ]').forEach(btn=> {
      btn.dataset.active = (btn.style.backgroundColor === toRgb(color)) ? 'true' : 'false';
      });
      }

      function toRgb(input) {
      const t = document.createElement('div');
      t.style.color = input;
      return t.style.color;
      }

      window.__pickSwatch = (hex) => { $color.value = hex; updatePreview(); };

      $name.addEventListener('input', updatePreview);
      $color.addEventListener('input', updatePreview);
      updatePreview();
      </script>
      @endsection