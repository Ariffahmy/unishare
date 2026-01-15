<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edit Item</title>
</head>
<body>
    <h1>Edit Item</h1>

    @if ($errors->any())
        <ul style="color:red;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('items.update', $item) }}">
        @csrf
        @method('PUT')

        <p>
            <label>Title</label><br>
            <input type="text" name="title" value="{{ old('title', $item->title) }}">
        </p>

        <p>
            <label>Category</label><br>
            <input type="text" name="category" value="{{ old('category', $item->category) }}">
        </p>

        <p>
            <label>Points per day</label><br>
            <input type="number" name="points_per_day" value="{{ old('points_per_day', $item->points_per_day) }}">
        </p>

        <p>
            <label>Max days</label><br>
            <input type="number" name="max_days" value="{{ old('max_days', $item->max_days) }}">
        </p>

        <button type="submit">Update</button>
    </form>

    <p><a href="{{ route('items.index') }}">Back</a></p>
</body>
</html>
