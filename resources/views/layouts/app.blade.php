<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device.width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'E commerce') }}</title>

       <!-- Scripts -->
       @vite(['resources/css/app.css', 'resources/js/app.js'])

       <style>
        [x-cloak] {
            display: none !important
        }
        </style>
        
</head>

<body class="bg-gray-200">
@include('layouts.navigation')

    <!-- product list -->
    <main class="p-5">
        {{ $slot }}
    </main>
</body>

</html>