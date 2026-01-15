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
                    <strong>{{ $item->title }}</strong>
                    — {{ $item->points_per_day }} pts/day
                </li>
            @endforeach
        </ul>
    @endif
</body>
</html>
