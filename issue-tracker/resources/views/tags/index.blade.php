@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col card">
            <h3>Tags</h3>
            <table style="width:100%">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Color</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tags as $t)
                        <tr>
                            <td>#{{ $t->name }}</td>
                            <td>{{ $t->color }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $tags->links() }}
        </div>
        <div class="col card">
            <h3>Krijo Tag</h3>
            <form method="POST" action="{{ route('tags.store') }}">
                @csrf
                <label>Emri</label>
                <input class="input mb-2" name="name" value="{{ old('name') }}">
                @error('name')<div class="error mb-2">{{ $message }}</div>@enderror

                <label>Ngjyra</label>
                <input class="input mb-2" name="color" value="{{ old('color') }}">

                <button class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
@endsection