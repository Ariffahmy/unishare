<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Items</title>
</head>
<body>
    <h1>Items</h1>

    <p><a href="{{ route('items.create') }}">+ Add Item</a></p>

    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    @if($items->count() === 0)
        <p>No items yet.</p>
    @else
    <ul>
        @foreach($items as $item)
        <li>
            <strong>{{ $item->title }}</strong> — {{ $item->points_per_day }} pts/day
            <br>
            <small>
                Condition: {{ $item->condition ?? 'N/A' }} <br>
                {{ $item->description ?? 'None' }}
            </small>
            <br>

        <!-- Edit button -->
        <a href="{{ route('items.edit', $item) }}">Edit</a>

        <!-- Delete button -->
        <form action="{{ route('items.destroy', $item) }}"
              method="POST"
              style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit"
                    onclick="return confirm('Delete this item?')">
                Delete
            </button>
            </form>
        </li>
    @endforeach
    </ul>


    @endif
</body>
</html>

