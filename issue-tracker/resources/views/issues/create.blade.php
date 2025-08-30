@extends('layouts.app')
@section('content')
<h1 class="text-2xl font-bold mb-4">New Issue ({{ $project->name }})</h1>

<form method="POST" action="{{ route('projects.issues.store',$project) }}" class="bg-white p-4 rounded shadow">
  @csrf
  @include('issues._form', ['issue'=>new \App\Models\Issue()])
</form>
@endsection
