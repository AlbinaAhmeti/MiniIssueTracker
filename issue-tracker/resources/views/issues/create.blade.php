@extends('layouts.app')
@section('content')
    <h2>Krijo Issue</h2>
    <form method="POST" action="{{ route('issues.store') }}" class="card">
        @csrf
        <label>Project</label>
        <select class="select mb-2" name="project_id">
            @foreach ($projects as $p)
                <option value="{{ $p->id }}">{{ $p->name }}</option>
            @endforeach
        </select>

        <label>Titulli</label>
        <input class="input mb-2" name="title" value="{{ old('title') }}">
        @error('title')<div class="error mb-2">{{ $message }}</div>@enderror

        <label>Përshkrimi</label>
        <textarea class="input mb-2" name="description">{{ old('description') }}</textarea>

        <div class="row">
            <div class="col">
                <label>Status</label>
                <select class="select" name="status">
                    @foreach ($statuses as $s)
                        <option value="{{ $s }}">{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label>Priority</label>
                <select class="select" name="priority">
                    @foreach ($priorities as $p)
                        <option value="{{ $p }}">{{ $p }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label>Due date</label>
                <input type="date" class="input" name="due_date" value="{{ old('due_date') }}">
            </div>
        </div>

        <button class="btn btn-primary">Save</button>
    </form>
@endsection