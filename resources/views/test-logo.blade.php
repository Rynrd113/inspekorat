<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Logo Component</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto space-y-8">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Test Logo Component</h1>
        
        <!-- Header Logo Test -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Header Logo (Large)</h2>
            <x-site-logo variant="header" size="lg" :show-text="true" />
        </div>
        
        <!-- Header Logo Medium -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Header Logo (Medium)</h2>
            <x-site-logo variant="header" size="md" :show-text="true" />
        </div>
        
        <!-- Header Logo Small -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Header Logo (Small)</h2>
            <x-site-logo variant="header" size="sm" :show-text="true" />
        </div>
        
        <!-- Footer Logo Test -->
        <div class="bg-gray-800 p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4 text-white">Footer Logo (Medium - White Text)</h2>
            <x-site-logo variant="footer" size="md" :show-text="true" class="text-white" />
        </div>
        
        <!-- Icon Only -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Icon Only (No Text)</h2>
            <x-site-logo variant="header" size="md" :show-text="false" />
        </div>
        
        <!-- Responsive Test -->
        <div class="bg-blue-600 p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4 text-white">Responsive Test (Mobile Hidden)</h2>
            <x-site-logo variant="header" size="lg" :show-text="true" class="text-white" />
            <x-site-logo variant="header" size="sm" :show-text="false" class="text-white md:hidden ml-4" />
        </div>
    </div>
</body>
</html>