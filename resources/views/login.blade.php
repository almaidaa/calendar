<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css">
    <style>
        body {
            background-color: #154c79;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 20px rgba(0,0,0,1.9);
            width: 300px;
            margin: 20px auto;
        }
        .btn {
            background-color: #3498db;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #2980b9;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="flex items-center justify-center min-h-screen">

        <div class="container">
            <img src="{{ asset('css/posco.png') }}" alt="Logo" class="mx-auto mb-4 w-32 h-40">
            <form method="POST" action="{{ route('auth') }}">
                @csrf

                {{-- <h1 class="text-center text-gray-700 text-lg font-bold mb-0" style="position: relative; top: -30px;"><strong>Log in</strong></h1> --}}

                <!-- username Address -->
                <div class="mb-4" style="position: relative; top: -30px;">
                    <label for="username" class="block text-gray-700 text-sm font-bold mb-2">username</label>
                    <input type="username" id="username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="username" value="{{ old('username') }}" required autofocus>
                </div>

                <!-- Password -->
                <div class="mb-4" style="position: relative; top: -30px;">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password" id="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="password" required autocomplete="current-password">
                </div>


                    @if(session('error'))
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-sm text-red-600">
                                <li>{{ session('error') }}</li>
                            </ul>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-sm text-green-600">
                                <li>{{ session('success') }}</li>
                            </ul>
                        </div>
                    @endif

                <div class="flex items-center justify-between">
                    <button type="submit" class="btn">Log in</button>
                    <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('register') }}">Register</a>
                </div>
            </form>
        </div>
    </div>
</body>

