<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Another-Go | The Future of Commerce')</title>
    
    @include('components.futuristic-styles')
    
    @stack('head-scripts')
</head>
<body class="min-h-screen bg-black text-white overflow-x-hidden relative">
    @include('components.futuristic-background', ['id' => $backgroundId ?? 'default'])
    
    @include('components.futuristic-nav')
    
    <div class="relative z-10">
        @yield('content')
    </div>
    
    <!-- Footer -->
    @include('components.futuristic-footer')
    
    @stack('scripts')
</body>
</html>