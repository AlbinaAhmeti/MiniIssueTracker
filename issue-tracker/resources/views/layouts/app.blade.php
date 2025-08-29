<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Issue Tracker' }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
        body {
            font-family: system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
        }

        .container {
            max-width: 1100px;
            margin: 20px auto;
            padding: 0 16px;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            background: #eee;
            margin-right: 4px
        }

        .error {
            color: #b91c1c
        }

        .btn {
            display: inline-block;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            text-decoration: none
        }

        .btn-primary {
            background: #111;
            color: #fff
        }

        .btn-danger {
            background: #b91c1c;
            color: #fff
        }

        .btn-ghost {
            background: #fff
        }

        .row {
            display: flex;
            gap: 16px;
            flex-wrap: wrap
        }

        .col {
            flex: 1 1 0
        }

        .card {
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 16px
        }

        .muted {
            color: #666
        }

        .input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 8px
        }

        .select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #fff
        }

        .mb-2 {
            margin-bottom: 8px
        }

        .mb-3 {
            margin-bottom: 12px
        }

        .mb-4 {
            margin-bottom: 16px
        }
    </style>
</head>

<body>
    <nav class="container mb-4">
        <a class="btn" href="{{ route('projects.index') }}">Projects</a>
        <a class="btn" href="{{ route('issues.index') }}">Issues</a>
        <a class="btn" href="{{ route('tags.index') }}">Tags</a>
    </nav>
    <div class="container">
        @if (session('success'))
        <div class="card" style="border-color:#22c55e">{{ session('success') }}</div>
        @endif
        @yield('content')
    </div>
    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        async function ajax(url, options = {}) {
            const res = await fetch(url, {
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin',
                ...options
            });
            if (!res.ok) throw new Error(await res.text());
            return res.json();
        }
    </script>
    @stack('scripts')
</body>

</html>