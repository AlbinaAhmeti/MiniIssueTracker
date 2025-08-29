@extends('layouts.app')
@section('content')
    <h2>Edito Issue</h2>
    <form method="POST" action="{{ route('issues.update', $issue) }}" class="card">
        @csrf @method('PUT')
        <label>Project</label>
        <select class="select mb-2" name="project_id">
            @foreach ($projects as $p)
                <option value="{{ $p->id }}" @selected($issue->project_id == $p->id)>{{ $p->name }}</option>
            @endforeach
        </select>

        <label>Titulli</label>
        <input class="input mb-2" name="title" value="{{ old('title', $issue->title) }}">

        <label>Përshkrimi</label>
        <textarea class="input mb-2" name="description">{{ old('description', $issue->description) }}</textarea>

        <div class="row">
            <div class="col">
                <label>Status</label>
                <select class="select" name="status">
                    @foreach ($statuses as $s)
                        <option value="{{ $s }}" @selected($issue->status == $s)>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label>Priority</label>
                <select class="select" name="priority">
                    @foreach ($priorities as $p)
                        <option value="{{ $p }}" @selected($issue->priority == $p)>{{ $p }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label>Due date</label>
                <input type="date" class="input" name="due_date"
                    value="{{ old('due_date', $issue->due_date?->format('Y-m-d')) }}">
            </div>
        </div>

        <button class="btn btn-primary">Update</button>
    </form>
@endsection