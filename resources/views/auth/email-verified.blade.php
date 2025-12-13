<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verified Successfully</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-lg text-center">
        
        <!-- Success Icon -->
        <div class="mb-6">
            <svg class="mx-auto h-16 w-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>

        <!-- Success Message -->
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Email Verified Successfully!</h2>
        <p class="text-gray-600 mb-8">
            Your email has been verified. You can now log in to your account.
        </p>

        <!-- Login Button -->
        <a href="{{ route('login') }}" class="inline-block w-full px-6 py-3 text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium transition-colors duration-200">
            Go to Login
        </a>

</body>
</html>
