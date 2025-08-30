@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Tags</h1>

@include('partials.errors')

<form method="POST" action="{{ route('tags.store') }}" class="bg-white p-4 rounded shadow mb-4">
  @csrf
  <div class="grid md:grid-cols-2 gap-3">
    <div>
      <label class="block text-sm">Name (unique)</label>
      <input name="name" class="border rounded p-2 w-full" value="{{ old('name') }}" required>
    </div>
    <div>
      <label class="block text-sm">Color (hex or css name)</label>
      <input name="color" class="border rounded p-2 w-full" value="{{ old('color') }}" placeholder="#22c55e">
      <div class="text-xs text-slate-500 mt-1">Shembuj: <code>#22c55e</code>, <code>red</code></div>
    </div>
  </div>
  <button class="mt-3 px-3 py-2 bg-indigo-600 text-white rounded">Create Tag</button>
</form>

<div class="bg-white p-4 rounded shadow">
  @forelse($tags as $t)
    <div class="flex items-center justify-between py-2 border-b last:border-b-0">
      <div class="flex items-center gap-2">
        <span class="inline-block w-3 h-3 rounded" @style(['background-color: ' . ($t->color ?? '#64748b')])></span>
        <span class="font-medium">{{ $t->name }}</span>
        @if($t->color) <span class="text-xs text-slate-500">{{ $t->color }}</span> @endif
      </div>
      <span class="text-xs text-slate-500">#{{ $t->id }}</span>
    </div>
  @empty
    <div class="text-slate-500">No tags yet.</div>
  @endforelse
</div>
@endsection
