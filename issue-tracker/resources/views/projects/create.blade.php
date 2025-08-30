@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">New Project</h1>

<form method="POST" action="{{ route('projects.store') }}" class="bg-white p-4 rounded shadow">
  @csrf
  @include('projects._form', ['project' => new \App\Models\Project()])
</form>
@endsection
