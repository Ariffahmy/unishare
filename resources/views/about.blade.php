<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>About - UniShare</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white min-h-screen font-sans text-gray-800">

    <div class="container mx-auto px-4 py-16 text-center max-w-5xl">
        <!-- Header Section -->
        <h6 class="text-sm font-bold uppercase tracking-widest text-gray-500 mb-4 underline decoration-gray-400 underline-offset-4">About</h6>
        
        <h1 class="text-5xl font-bold text-gray-800 mb-6">Welcome to UniShare</h1>
        
        <p class="text-xl text-gray-500 mb-16 max-w-2xl mx-auto leading-relaxed">
            (Description about UniShare goes here. We connect students to share resources efficiently and build a sustainable community.)
        </p>

        <!-- Cards Section -->
        <div class="grid md:grid-cols-2 gap-12 max-w-4xl mx-auto">
            
            <!-- New User Card -->
            <div class="border border-black bg-white flex flex-col h-full shadow-lg relative">
                <div class="h-64 overflow-hidden relative bg-gray-100">
                     <img src="{{ asset('images/new_user.jpg') }}" alt="New User" class="w-full h-full object-cover">
                </div>
                
                <div class="p-8 text-left flex-grow flex flex-col justify-between">
                    <div>
                        <h2 class="text-2xl font-bold mb-4 text-gray-800">Are you new?</h2>
                        <p class="text-gray-600 mb-8 text-lg">
                            If you're new, register first to get Press here to create an account started.
                        </p>
                    </div>
                </div>
                
                <a href="{{ route('register') }}" class="block w-full h-full absolute inset-0"></a>
                
                <div class="px-8 pb-8 text-left">
                     <a href="{{ route('register') }}" class="text-gray-500 hover:text-black hover:underline transition-colors text-lg">
                        Press here to create an account
                     </a>
                </div>
            </div>

            <!-- Member Card -->
            <div class="border border-black bg-white flex flex-col h-full shadow-lg relative">
                <div class="h-64 overflow-hidden relative bg-gray-100">
                    <img src="{{ asset('images/login.jpg') }}" alt="Member" class="w-full h-full object-cover">
                </div>
                
                <div class="p-8 text-left flex-grow flex flex-col justify-between">
                    <div>
                        <h2 class="text-2xl font-bold mb-4 text-gray-800">Are you a member?</h2>
                        <p class="text-gray-600 mb-8 text-lg">
                            Login here
                        </p>
                    </div>
                </div>

                <a href="{{ route('login') }}" class="block w-full h-full absolute inset-0"></a>

                 <div class="px-8 pb-8 text-left">
                     <a href="{{ route('login') }}" class="text-gray-500 hover:text-black hover:underline transition-colors text-lg">
                        Press here to login
                     </a>
                </div>
            </div>

        </div>
    </div>

</body>
</html>


