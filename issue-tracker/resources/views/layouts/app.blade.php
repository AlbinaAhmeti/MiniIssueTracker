<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mini Issue Tracker</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-50 text-slate-900">
  <nav class="p-4 bg-white shadow">
    <a href="{{ route('projects.index') }}" class="font-semibold">Projects</a>
    <a href="{{ route('tags.index') }}" class="ml-4">Tags</a>
    <a href="{{ route('issues.index') }}" class="ml-4">Issues</a>
  </nav>
  <main class="max-w-5xl mx-auto p-4">
    @if(session('success'))
      <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
    @endif
    @yield('content')
  </main>
</body>
</html>
