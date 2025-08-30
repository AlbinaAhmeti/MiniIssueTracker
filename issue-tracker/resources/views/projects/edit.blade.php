@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Edit Project</h1>

<form method="POST" action="{{ route('projects.update', $project) }}" class="bg-white p-4 rounded shadow">
  @csrf
  @method('PUT')
  @include('projects._form', ['project' => $project])
</form>
@endsection
