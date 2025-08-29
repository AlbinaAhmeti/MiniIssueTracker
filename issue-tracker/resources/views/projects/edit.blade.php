@extends('layouts.app')
@section('content')
    <h2>Edito Project</h2>
    <form method="POST" action="{{ route('projects.update', $project) }}" class="card">
        @csrf @method('PUT')
        <label>Emri</label>
        <input class="input mb-2" name="name" value="{{ old('name', $project->name) }}">
        @error('name')<div class="error mb-2">{{ $message }}</div>@enderror

        <label>Përshkrimi</label>
        <textarea class="input mb-2" name="description">{{ old('description', $project->description) }}</textarea>

        <div class="row">
            <div class="col">
                <label>Start Date</label>
                <input type="date" class="input mb-2" name="start_date"
                    value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}">
            </div>
            <div class="col">
                <label>Deadline</label>
                <input type="date" class="input mb-2" name="deadline"
                    value="{{ old('deadline', $project->deadline?->format('Y-m-d')) }}">
            </div>
        </div>

        <button class="btn btn-primary">Update</button>
    </form>
@endsection