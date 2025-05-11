<!DOCTYPE html>
<html>
    <head>
        <title>Laravel Deploy Wizard - Installation</title>
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    </head>
    <body>
        <div class="max-w-3xl mx-auto p-4"
             x-data="{ step: {{ $step }}, db_connection: '{{ $dbConnection }}' }">

            <h1 class="text-2xl font-bold mb-6">Laravel Deploy Wizard</h1>

            <!-- Step Navigation -->
            <div class="flex space-x-4 mb-4">
                <button @click="step = 1" :class="step === 1 ? 'bg-blue-500 text-white' : 'bg-gray-200'" class="px-4 py-2 rounded">Step 1</button>
                <button @click="step = 2" :class="step === 2 ? 'bg-blue-500 text-white' : 'bg-gray-200'" class="px-4 py-2 rounded">Step 2</button>
                <button @click="step = 3" :class="step === 3 ? 'bg-blue-500 text-white' : 'bg-gray-200'" class="px-4 py-2 rounded">Step 3</button>
                <button @click="step = 4" :class="step === 4 ? 'bg-blue-500 text-white' : 'bg-gray-200'" class="px-4 py-2 rounded">Step 4</button>
            </div>

            <!-- Step 1 -->
            <form x-show="step === 1" method="POST" action="{{ route('deploy-wizard.step1') }}" class="space-y-4">
                @csrf
                <input type="text" name="app_name" placeholder="Application Name" value="{{ $appName }}" class="border p-2 w-full">
                <input type="text" name="app_url" placeholder="Application URL" value="{{ $appUrl }}" class="border p-2 w-full">
                <button type="submit" class="bg-blue-500 text-white p-2 w-full">Next Step</button>
            </form>

            <!-- Step 2 -->
            <form x-show="step === 2" method="POST" action="{{ route('deploy-wizard.step2') }}" class="space-y-4">
                @csrf
                <input type="text" name="app_locale" value="{{ $appLocale }}" placeholder="Locale (e.g., en)" class="border p-2 w-full">
                <input type="text" name="app_fallback_locale" value="{{ $appFallbackLocale }}" placeholder="Fallback Locale" class="border p-2 w-full">
                <input type="text" name="app_faker_locale" value="{{ $appFakerLocale }}" placeholder="Faker Locale" class="border p-2 w-full">
                <button type="submit" class="bg-blue-500 text-white p-2 w-full">Next Step</button>
            </form>

            <!-- Step 3 -->
            <form x-show="step === 3" method="POST" action="{{ route('deploy-wizard.step3') }}" class="space-y-4">
                @csrf
                <select name="db_connection" x-model="db_connection" class="border p-2 w-full">
                    <option value="sqlite">SQLite</option>
                    <option value="mysql">MySQL</option>
                    <option value="pgsql">PostgreSQL</option>
                    <option value="sqlsrv">SQL Server</option>
                </select>

                <template x-if="db_connection !== 'sqlite'">
                    <div class="space-y-4">
                        <input type="text" name="db_host" value="{{ $dbHost }}" placeholder="Database Host" class="border p-2 w-full">
                        <input type="text" name="db_port" value="{{ $dbPort }}" placeholder="Database Port" class="border p-2 w-full">
                        <input type="text" name="db_database" value="{{ $dbName }}" placeholder="Database Name" class="border p-2 w-full">
                        <input type="text" name="db_username" value="{{ $dbUser }}" placeholder="Database Username" class="border p-2 w-full">
                        <input type="password" name="db_password" value="{{ $dbPassword }}" placeholder="Database Password" class="border p-2 w-full">
                    </div>
                </template>

                <button type="submit" class="bg-green-500 text-white p-2 w-full">Next Step</button>
            </form>

            <!-- Step 4 -->
            <form x-show="step === 4" method="POST" action="{{ route('deploy-wizard.step4') }}" class="space-y-4">
                @csrf
                <button type="submit" class="bg-green-500 text-white p-2 w-full">Finish Setup</button>
            </form>
        </div>
    </body>
</html>
