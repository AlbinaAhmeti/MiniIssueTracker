@php($isEdit = isset($project) && $project->exists)
@include('partials.errors')

<div class="space-y-6">
    <div class="grid md:grid-cols-2 gap-6">

        <div class="md:col-span-2">
            <label class="mb-1 block text-sm font-medium text-slate-700">
                Name <span class="text-rose-500">*</span>
            </label>
            <input
                type="text"
                name="name"
                placeholder="Amazing Website Revamp"
                value="{{ old('name', $project->name ?? '') }}"
                required
                class="w-full rounded-xl border border-slate-200 bg-white/70 px-3 py-2 shadow-sm transition
               focus:outline-none focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500
               @error('name') border-rose-400 ring-1 ring-rose-300 @enderror">
            <p class="mt-1 text-xs text-slate-500">Give your project a short, friendly name.</p>
            @error('name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div class="md:col-span-2">
            <label class="mb-1 block text-sm font-medium text-slate-700">Description</label>
            <textarea
                name="description"
                rows="3"
                placeholder="A few words about the goal or scope…"
                class="w-full rounded-xl border border-slate-200 bg-white/70 px-3 py-2 shadow-sm transition
               focus:outline-none focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500
               @error('description') border-rose-400 ring-1 ring-rose-300 @enderror">{{ old('description', $project->description ?? '') }}</textarea>
            @error('description') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Start date</label>
            <div class="relative">
                <input
                    type="date"
                    name="start_date"
                    value="{{ old('start_date', optional($project->start_date ?? null)->toDateString()) }}"
                    class="w-full rounded-xl border border-slate-200 bg-white/70 px-3 py-2 pr-10 shadow-sm transition
                 focus:outline-none focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500
                 @error('start_date') border-rose-400 ring-1 ring-rose-300 @enderror">
                <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1z"></path>
                    <path d="M18 9H2v7a2 2 0 002 2h12a2 2 0 002-2V9z"></path>
                </svg>
            </div>
            @error('start_date') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Deadline</label>
            <div class="relative">
                <input
                    type="date"
                    name="deadline"
                    value="{{ old('deadline', optional($project->deadline ?? null)->toDateString()) }}"
                    class="w-full rounded-xl border border-slate-200 bg-white/70 px-3 py-2 pr-10 shadow-sm transition
                 focus:outline-none focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500
                 @error('deadline') border-rose-400 ring-1 ring-rose-300 @enderror">
                <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1z"></path>
                    <path d="M18 9H2v7a2 2 0 002 2h12a2 2 0 002-2V9z"></path>
                </svg>
            </div>
            @error('deadline') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            <p class="mt-1 text-xs text-slate-500">Tip: leave blank if not sure yet.</p>
        </div>

    </div>

    @php($isEdit = isset($project) && $project->exists)
    @php($cancelUrl = $isEdit ? route('projects.show', $project) : route('projects.index'))

    <div class="flex items-center gap-3">
        <button type="submit"
            class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-500
           px-4 py-2 font-medium text-white shadow-sm transition hover:shadow-md active:translate-y-px">
            <span>{{ $isEdit ? 'Save Changes' : 'Create Project' }}</span>
        </button>

        <a href="{{ $cancelUrl }}"
            class="text-slate-600 hover:text-slate-800 text-sm underline-offset-2 hover:underline">
            Cancel
        </a>
    </div>

</div>