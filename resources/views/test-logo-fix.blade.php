<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Logo Fix</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-6">Test Logo Component Fix</h1>
        
        <div class="space-y-6">
            <!-- Direct access test -->
            <div>
                <h3 class="text-lg font-semibold mb-2">Direct Image Access:</h3>
                <img src="{{ asset('images/logo.png') }}" alt="Direct Logo" class="h-16 w-auto border">
                <p class="text-sm text-gray-600 mt-1">URL: {{ asset('images/logo.png') }}</p>
            </div>
            
            <!-- Component test -->
            <div>
                <h3 class="text-lg font-semibold mb-2">Via Component:</h3>
                <x-site-logo variant="header" size="lg" :show-text="true" />
            </div>
            
            <!-- Simple logo test -->
            <div>
                <h3 class="text-lg font-semibold mb-2">Default Logo:</h3>
                <img src="{{ asset('images/logo.png') }}" alt="Default Logo" class="h-16 w-auto border">
                <p class="text-sm text-gray-600 mt-1">URL: {{ asset('images/logo.png') }}</p>
            </div>
            
            <div class="bg-green-50 border border-green-200 rounded p-4">
                <h4 class="font-semibold text-green-800">Test Success!</h4>
                <p class="text-green-700">Jika Anda melihat logo di atas tanpa error 403, berarti masalah sudah teratasi.</p>
            </div>
        </div>
    </div>
</body>
</html>