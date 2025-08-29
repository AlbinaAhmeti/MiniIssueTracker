@extends('layouts.app')
@section('content')
    <h2>Krijo Project</h2>
    <form method="POST" action="{{ route('projects.store') }}" class="card">
        @csrf
        <label>Emri</label>
        <input class="input mb-2" name="name" value="{{ old('name') }}">
        @error('name')<div class="error mb-2">{{ $message }}</div>@enderror

        <label>Përshkrimi</label>
        <textarea class="input mb-2" name="description">{{ old('description') }}</textarea>

        <div class="row">
            <div class="col">
                <label>Start Date</label>
                <input type="date" class="input mb-2" name="start_date" value="{{ old('start_date') }}">
                @error('start_date')<div class="error mb-2">{{ $message }}</div>@enderror
            </div>
            <div class="col">
                <label>Deadline</label>
                <input type="date" class="input mb-2" name="deadline" value="{{ old('deadline') }}">
                @error('deadline')<div class="error mb-2">{{ $message }}</div>@enderror
            </div>
        </div>

        <button class="btn btn-primary">Save</button>
    </form>
@endsection