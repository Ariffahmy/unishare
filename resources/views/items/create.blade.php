<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Add Item</title>
</head>
<body>
    <h1>Add Item</h1>

    @if ($errors->any())
        <ul style="color:red;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('items.store') }}">
        @csrf

        <p>
            <label>Title</label><br>
            <input type="text" name="title" value="{{ old('title') }}">
        </p>

        <p>
            <label>Category</label><br>
            <input type="text" name="category" value="{{ old('category') }}">
        </p>

        <p>
            <label>Description</label><br>
            <textarea name="description" rows="4">{{ old('description') }}</textarea>
        </p>

        <p>
            <label>Condition</label><br>
            <select name="condition">
            <option value="">-- Select condition --</option>
            <option value="New" {{ old('condition') == 'New' ? 'selected' : '' }}>New</option>
            <option value="Good" {{ old('condition') == 'Good' ? 'selected' : '' }}>Good</option>
            <option value="Fair" {{ old('condition') == 'Fair' ? 'selected' : '' }}>Fair</option>
            <option value="Poor" {{ old('condition') == 'Poor' ? 'selected' : '' }}>Poor</option>
            </select>
        </p>

        <p>
            <label>Points per day</label><br>
            <input type="number" name="points_per_day" value="{{ old('points_per_day', 0) }}">
        </p>

        <p>
            <label>Max days</label><br>
            <input type="number" name="max_days" value="{{ old('max_days', 7) }}">
        </p>

        <button type="submit">Save</button>
    </form>

    <p><a href="{{ route('items.index') }}">Back</a></p>
</body>
</html>
