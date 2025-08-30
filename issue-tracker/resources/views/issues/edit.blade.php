@extends('layouts.app')
@section('content')
<h1 class="text-2xl font-bold mb-4">Edit Issue</h1>

<form method="POST" action="{{ route('issues.update',$issue) }}" class="bg-white p-4 rounded shadow">
  @csrf @method('PUT')
  @include('issues._form')
</form>
@endsection
