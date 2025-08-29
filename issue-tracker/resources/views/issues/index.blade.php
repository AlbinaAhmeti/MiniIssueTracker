@extends('layouts.app')
@section('content')
    <div class="row mb-3">
        <div class="col">
            <a class="btn btn-primary" href="{{ route('issues.create') }}">+ New Issue</a>
        </div>
        <form class="col card" method="GET" action="{{ route('issues.index') }}">
            <div class="row">
                <div class="col">
                    <label>Status</label>
                    <select name="status" class="select">
                        <option value="">Any</option>
                        @foreach (App\Models\Issue::STATUS as $s)
                            <option value="{{ $s }}" @selected(request('status') === $s)>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <label>Priority</label>
                    <select name="priority" class="select">
                        <option value="">Any</option>
                        @foreach (App\Models\Issue::PRIORITY as $p)
                            <option value="{{ $p }}" @selected(request('priority') === $p)>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <label>Tag</label>
                    <select name="tag" class="select">
                        <option value="">Any</option>
                        @foreach ($tags as $t)
                            <option value="{{ $t->id }}" @selected(request('tag') == $t->id)>{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col" style="align-self:flex-end">
                    <button class="btn">Filter</button>
                </div>
            </div>
        </form>
    </div>

    @foreach ($issues as $issue)
        <div class="card mb-2">
            <a href="{{ route('issues.show', $issue) }}"><strong>{{ $issue->title }}</strong></a>
            <div class="muted">Project: {{ $issue->project->name }} • Status: {{ $issue->status }} • Priority:
                {{ $issue->priority }}</div>
            <div>
                @foreach ($issue->tags as $t)
                    <span class="badge">#{{ $t->name }}</span>
                @endforeach
            </div>
        </div>
    @endforeach
    <div class="mt-4">{{ $issues->links() }}</div>
@endsection