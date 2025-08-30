@php($isEdit = isset($issue) && $issue->exists)
@include('partials.errors')

<div class="grid md:grid-cols-2 gap-4">
  <div class="md:col-span-2">
    <label class="block text-sm">Title</label>
    <input name="title" class="border rounded p-2 w-full"
           value="{{ old('title', $issue->title ?? '') }}" required>
  </div>

  <div class="md:col-span-2">
    <label class="block text-sm">Description</label>
    <textarea name="description" class="border rounded p-2 w-full" rows="3">{{ old('description',$issue->description ?? '') }}</textarea>
  </div>

  <div>
    <label class="block text-sm">Status</label>
    <select name="status" class="border rounded p-2">
      @foreach($statuses as $s)
        <option value="{{ $s }}" @selected(old('status',$issue->status ?? '')===$s)>{{ $s }}</option>
      @endforeach
    </select>
  </div>
  <div>
    <label class="block text-sm">Priority</label>
    <select name="priority" class="border rounded p-2">
      @foreach($priorities as $p)
        <option value="{{ $p }}" @selected(old('priority',$issue->priority ?? '')===$p)>{{ $p }}</option>
      @endforeach
    </select>
  </div>

  <div class="md:col-span-2">
    <label class="block text-sm">Due date</label>
    <input type="date" name="due_date" class="border rounded p-2"
           value="{{ old('due_date', optional($issue->due_date ?? null)->toDateString()) }}">
  </div>
</div>

<button class="mt-4 px-3 py-2 bg-indigo-600 text-white rounded">
  {{ $isEdit ? 'Update Issue' : 'Create Issue' }}
</button>
