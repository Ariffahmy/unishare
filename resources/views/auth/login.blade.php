<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - UniShare</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-8">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Welcome Back to UniShare</h2>

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p class="font-bold">Error</p>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="mb-6">
                    <label for="login_credential" class="block text-gray-700 font-medium mb-2">Email Address or Phone Number</label>
                    <input type="text" id="login_credential" name="login_credential" value="{{ old('login_credential') }}" required autofocus
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('login_credential') border-red-500 @enderror">
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>

                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-gray-700 text-sm">Remember me</label>
                    </div>
                </div>

                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                    Sign In
                </button>
            </form>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 text-center">
            <p class="text-sm text-gray-600">Don't have an account? <a href="{{ route('register') }}" class="text-purple-600 hover:underline">Register</a></p>
        </div>
    </div>
</body>
</html>


