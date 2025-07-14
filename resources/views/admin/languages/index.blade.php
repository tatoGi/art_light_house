<x-admin.admin-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6">Manage Language Translations</h1>

        <!-- Display success message if translation is saved successfully -->
        @if (session('success'))
            <div class="alert alert-success bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Language selection form -->
        <form method="GET" action="{{ route('languages.index', app()->getLocale()) }}" class="mb-4">
            <div class="mb-4">
                <label for="lang" class="block text-gray-700 text-sm font-bold mb-2">Select Language:</label>
                <select name="lang" id="lang" class="form-control w-full py-2 px-3 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-300" onchange="this.form.submit()">
                    @foreach ($languages as $language)
                        <option value="{{ $language }}" {{ $language === $defaultLang ? 'selected' : '' }}>{{ strtoupper($language) }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <!-- Translation form -->
        <form method="POST" action="{{ route('languages.update', app()->getLocale()) }}" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf

            <!-- Translation key and value input fields -->
            <input type="hidden" name="lang" value="{{ $defaultLang }}">

            <div class="mb-4">
                <label for="key" class="block text-gray-700 text-sm font-bold mb-2">Translation Key:</label>
                <input type="text" name="key" id="key" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-300" required>
            </div>
            <div class="mb-6">
                <label for="value" class="block text-gray-700 text-sm font-bold mb-2">Translation Value:</label>
                <input type="text" name="value" id="value" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-300" required>
            </div>

            <!-- Submit button -->
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Save Translation
                </button>
            </div>
        </form>

        <!-- Existing translations -->
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h2 class="text-2xl font-bold mb-4">Existing Translations ({{ strtoupper($defaultLang) }})</h2>
            <ul>
                @foreach ($translations as $key => $value)
                    <li class="mb-2">
                        <strong>{{ $key }}:</strong> {{ $value }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</x-admin.admin-layout>
