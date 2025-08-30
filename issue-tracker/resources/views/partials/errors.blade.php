@if ($errors->any())
  <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
    <ul class="list-disc list-inside">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif
