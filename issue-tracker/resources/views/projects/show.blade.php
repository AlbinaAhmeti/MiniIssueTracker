@extends('layouts.app')
@section('content')
    <h2>{{ $project->name }}</h2>
    <p class="muted">{{ $project->description }}</p>
    <p class="muted">Start: {{ $project->start_date?->toDateString() }} | Deadline:
        {{ $project->deadline?->toDateString() }}
    </p>
    <h3 class="mb-2">Issues</h3>
    @foreach ($project->issues as $issue)
        <div class="card mb-2">
            <a href="{{ route('issues.show', $issue) }}"><strong>{{ $issue->title }}</strong></a>
            <div class="muted">Status: {{ $issue->status }} • Priority: {{ $issue->priority }}</div>
            <div>
                @foreach ($issue->tags as $t)
                    <span class="badge" style="background: #f3f4f6">#{{ $t->name }}</span>
                @endforeach
            </div>
        </div>
    @endforeach
@endsection