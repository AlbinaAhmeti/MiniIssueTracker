@php($isEdit = isset($project) && $project->exists)
@include('partials.errors')

<div class="grid md:grid-cols-2 gap-4">
    <div class="md:col-span-2">
        <label class="block text-sm">Name</label>
        <input name="name" class="border rounded p-2 w-full"
            value="{{ old('name', $project->name ?? '') }}" required>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm">Description</label>
        <textarea name="description" class="border rounded p-2 w-full" rows="3">{{ old('description', $project->description ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-sm">Start date</label>
        <input type="date" name="start_date" class="border rounded p-2 w-full"
            value="{{ old('start_date', optional($project->start_date ?? null)->toDateString()) }}">
    </div>
    <div>
        <label class="block text-sm">Deadline</label>
        <input type="date" name="deadline" class="border rounded p-2 w-full"
            value="{{ old('deadline', optional($project->deadline ?? null)->toDateString()) }}">
    </div>
</div>

<button class="mt-4 px-3 py-2 bg-indigo-600 text-white rounded">
    {{ $isEdit ? 'Update Project' : 'Create Project' }}
</button>