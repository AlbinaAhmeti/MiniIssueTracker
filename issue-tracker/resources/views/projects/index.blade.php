@extends('layouts.app')
@section('content')
    <div class="row mb-3">
        <div><a class="btn btn-primary" href="{{ route('projects.create') }}">+ New Project</a></div>
    </div>
    <div class="row">
        @foreach ($projects as $p)
            <div class="col card">
                <h3><a href="{{ route('projects.show', $p) }}">{{ $p->name }}</a></h3>
                <p class="muted">{{ Str::limit($p->description, 120) }}</p>
                <p class="muted">Issues: {{ $p->issues_count }}</p>
                <div>
                    <a class="btn" href="{{ route('projects.edit', $p) }}">Edit</a>
                    <form method="POST" action="{{ route('projects.destroy', $p) }}" style="display:inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger" onclick="return confirm('Delete?')">Delete</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $projects->links() }}</div>
@endsection