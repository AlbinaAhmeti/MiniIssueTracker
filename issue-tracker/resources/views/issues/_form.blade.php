@php
  $isEdit = isset($issue) && $issue->exists;

  $statuses   = $statuses   ?? ['open','in_progress','closed'];
  $priorities = $priorities ?? ['low','medium','high'];

  $norm = function ($v) {
      if ($v instanceof \BackedEnum) return $v->value;
      if ($v instanceof \UnitEnum)   return $v->name;
      return (string) $v;
  };

  $currentStatus   = $norm(old('status',   $issue->status   ?? 'open'));
  $currentPriority = $norm(old('priority', $issue->priority ?? 'medium'));

  $stColor = [
    'open'        => 'bg-emerald-100 text-emerald-700 ring-emerald-800',
    'in_progress' => 'bg-amber-100 text-amber-700 ring-amber-800',
    'closed'      => 'bg-slate-200 text-slate-700 ring-slate-800',
  ];
  $prColor = [
    'low'    => 'bg-sky-100 text-sky-700 ring-sky-800',
    'medium' => 'bg-indigo-100 text-indigo-700 ring-indigo-800',
    'high'   => 'bg-rose-100 text-rose-700 ring-rose-800',
  ];

  $cancelUrl = $isEdit
      ? route('issues.index', $issue)
      : (isset($project) ? route('projects.issues.index', $project) : route('issues.show'));
@endphp

@include('partials.errors')

<div class="space-y-6">
  <div class="grid md:grid-cols-2 gap-6">

    <div class="md:col-span-2">
      <label class="mb-1 block text-sm font-medium text-slate-700">
        Title <span class="text-rose-500">*</span>
      </label>
      <input
        type="text"
        name="title"
        placeholder="Short, descriptive title…"
        value="{{ old('title', $issue->title ?? '') }}"
        required
        class="w-full rounded-xl border border-slate-200 bg-white/70 px-3 py-2 shadow-sm transition
               focus:outline-none focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500
               @error('title') border-rose-400 ring-1 ring-rose-300 @enderror">
      @error('title') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
      <label class="mb-1 block text-sm font-medium text-slate-700">Description</label>
      <textarea
        name="description"
        rows="3"
        placeholder="What’s the problem? Any acceptance criteria?"
        class="w-full rounded-xl border border-slate-200 bg-white/70 px-3 py-2 shadow-sm transition
               focus:outline-none focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500
               @error('description') border-rose-400 ring-1 ring-rose-300 @enderror">{{ old('description', $issue->description ?? '') }}</textarea>
      @error('description') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
    </div>

    <div>
      <label class="mb-1 block text-sm font-medium text-slate-700">Status</label>
      <div class="flex flex-wrap gap-2">
        @foreach($statuses as $s)
          @php
            $sVal   = $norm($s);
            $active = $currentStatus === $sVal;
            $cls    = $stColor[$sVal] ?? 'bg-slate-100 text-slate-700 ring-slate-200';
            $id     = 'st-'.$sVal;
          @endphp

          <div>
            <input id="{{ $id }}" type="radio" name="status" value="{{ $sVal }}"
                   class="peer sr-only" {{ $active ? 'checked' : '' }}>
            <label for="{{ $id }}"
                   class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs ring-1 select-none transition
                          {{ $cls }} opacity-80 hover:opacity-100
                          peer-checked:ring-2 peer-checked:shadow-sm peer-checked:opacity-100 peer-focus:ring-2 peer-checked:scale-[1.02]">
              <span class="relative flex h-2.5 w-2.5 items-center justify-center">
                <span class="h-2.5 w-2.5 rounded-full bg-current/40"></span>
                <svg class="absolute hidden h-3 w-3 peer-checked:block" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M16.7 5.3a1 1 0 0 1 0 1.4l-7.3 7.3a1 1 0 0 1-1.4 0L3.3 10a1 1 0 1 1 1.4-1.4l3 3 6.6-6.6a1 1 0 0 1 1.4 0Z" clip-rule="evenodd"/>
                </svg>
              </span>
              <span class="font-medium capitalize">{{ str_replace('_',' ', $sVal) }}</span>
            </label>
          </div>
        @endforeach
      </div>
      @error('status') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
    </div>

    <div>
      <label class="mb-1 block text-sm font-medium text-slate-700">Priority</label>
      <div class="flex flex-wrap gap-2">
        @foreach($priorities as $p)
          @php
            $pVal   = $norm($p);
            $active = $currentPriority === $pVal;
            $cls    = $prColor[$pVal] ?? 'bg-slate-100 text-slate-700 ring-slate-200';
            $id     = 'pr-'.$pVal;
          @endphp

          <div>
            <input id="{{ $id }}" type="radio" name="priority" value="{{ $pVal }}"
                   class="peer sr-only" {{ $active ? 'checked' : '' }}>
            <label for="{{ $id }}"
                   class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs ring-1 select-none transition
                          {{ $cls }} opacity-80 hover:opacity-100
                          peer-checked:ring-2 peer-checked:shadow-sm peer-checked:opacity-100 peer-focus:ring-2 peer-checked:scale-[1.02]">
              <span class="relative flex h-2.5 w-2.5 items-center justify-center">
                <span class="h-2.5 w-2.5 rounded-full bg-current/40"></span>
                <svg class="absolute hidden h-3 w-3 peer-checked:block" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M16.7 5.3a1 1 0 0 1 0 1.4l-7.3 7.3a1 1 0 0 1-1.4 0L3.3 10a1 1 0 1 1 1.4-1.4l3 3 6.6-6.6a1 1 0 0 1 1.4 0Z" clip-rule="evenodd"/>
                </svg>
              </span>
              <span class="font-medium capitalize">{{ $pVal }}</span>
            </label>
          </div>
        @endforeach
      </div>
      @error('priority') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
      <label class="mb-1 block text-sm font-medium text-slate-700">Due date</label>
      <div class="relative max-w-md">
        <input
          type="date"
          name="due_date"
          value="{{ old('due_date', optional($issue->due_date ?? null)->toDateString()) }}"
          class="w-full rounded-xl border border-slate-200 bg-white/70 px-3 py-2 pr-10 shadow-sm transition
                 focus:outline-none focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500
                 @error('due_date') border-rose-400 ring-1 ring-rose-300 @enderror">
        <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
          <path d="M6 2a1 1 0 0 0-1 1v1H4a2 2 0 0 0-2 2v1h16V6a2 2 0 0 0-2-2h-1V3a1 1 0 1 0-2 0v1H7V3a1 1 0 0 0-1-1z"></path>
          <path d="M18 9H2v7a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
        </svg>
      </div>
      @error('due_date') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
      <p class="mt-1 text-xs text-slate-500">Tip: leave blank if not sure yet.</p>
    </div>

  </div>

  <div class="flex items-center gap-3">
    <button type="submit"
      class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-500
             px-4 py-2 font-medium text-white shadow-sm transition hover:shadow-md active:translate-y-px">
      {{ $isEdit ? 'Save Changes' : 'Create Issue' }}
    </button>

    <a href="{{ $cancelUrl }}"
       class="text-slate-600 hover:text-slate-800 text-sm underline-offset-2 hover:underline">
      Cancel
    </a>
  </div>
</div>
