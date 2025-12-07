<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Link Expired</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-lg text-center">
        
        <!-- Warning Icon -->
        <div class="mb-6">
            <svg class="mx-auto h-16 w-16 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>

        <!-- Expired Message -->
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Verification Link Expired</h2>
        <p class="text-gray-600 mb-8">
            This verification link has expired. Please request a new verification email.
        </p>

        <!-- Resend Button -->
        <a href="{{ route('login') }}" class="inline-block w-full px-6 py-3 text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium transition-colors duration-200 mb-4">
            Go to Login
        </a>

        <!-- Additional Help -->
        <p class="text-sm text-gray-500">
            You can request a new verification link after logging in.
        </p>
    </div>
</body>
</html>
