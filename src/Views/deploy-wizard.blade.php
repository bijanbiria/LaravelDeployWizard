<!DOCTYPE html>
<html>
    <head>
        <title>Laravel Deploy Wizard</title>
        <link rel="stylesheet" href="{{ asset('vendor/deploy-wizard/styles.css') }}">
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    </head>
    <body>
        <div class="container mx-auto p-4" x-data="{ loading: false }">
            <h1 class="text-2xl font-bold mb-4">Laravel Deploy Wizard</h1>

            @if (session('error'))
                <p class="text-red-500">{{ session('error') }}</p>
            @endif

            <form method="POST" action="{{ url(config('deployWizard.route', 'install')) }}" @submit="loading = true">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700">Database Host:</label>
                    <input type="text" name="db_host" class="border border-gray-300 p-2 w-full" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Database Port:</label>
                    <input type="text" name="db_port" class="border border-gray-300 p-2 w-full" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Database Name:</label>
                    <input type="text" name="db_database" class="border border-gray-300 p-2 w-full" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Database Username:</label>
                    <input type="text" name="db_username" class="border border-gray-300 p-2 w-full" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Database Password:</label>
                    <input type="password" name="db_password" class="border border-gray-300 p-2 w-full">
                </div>

                <div class="mb-4">
                    <button type="submit" class="bg-blue-500 text-white p-2 w-full" x-bind:disabled="loading">
                        <span x-show="!loading">Install</span>
                        <span x-show="loading">Installing...</span>
                    </button>
                </div>
            </form>
        </div>
    </body>
</html>