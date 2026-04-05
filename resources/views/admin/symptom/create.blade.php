<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row w-full justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Tambah Data Gejala') }}
            </h2>
            <a href="{{ route('admin.symptoms.index') }}" class="font-bold py-3 px-5 rounded-full text-white bg-gray-500 hover:bg-gray-600 transition">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-10">
                
                <!-- Alert Errors -->
                @if($errors->any())
                <div class="mb-5 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Oops!</strong>
                    <span class="block sm:inline">Terdapat kesalahan pada form:</span>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('admin.symptoms.store') }}" class="space-y-6">
                    @csrf

                    <!-- Symptom Name -->
                    <div>
                        <label for="symptom_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nama Gejala <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="symptom_name" 
                            id="symptom_name" 
                            value="{{ old('symptom_name') }}"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                            placeholder="Contoh: Sakit Kepala"
                            required
                            autofocus
                        >
                        @error('symptom_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tipe Gejala <span class="text-red-500">*</span>
                        </label>
                        
                        <div class="space-y-2">
                            <!-- Gejala Umum -->
                            <label class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <input 
                                    type="radio" 
                                    name="type" 
                                    value="umum" 
                                    {{ old('type') == 'umum' ? 'checked' : '' }}
                                    class="mr-3 text-blue-600 focus:ring-blue-500"
                                    required
                                >
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Gejala Umum 
                                    <span class="text-xs text-gray-500">(screening awal)</span>
                                </span>
                            </label>

                            <!-- Gejala Kritis -->
                            <label class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <input 
                                    type="radio" 
                                    name="type" 
                                    value="kritis" 
                                    {{ old('type') == 'kritis' ? 'checked' : '' }}
                                    class="mr-3 text-red-600 focus:ring-red-500"
                                    required
                                >
                                <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Gejala Kritis 
                                    <span class="text-xs text-gray-500">(pembeda diagnosa)</span>
                                </span>
                            </label>
                        </div>
                        
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Deskripsi <span class="text-gray-400 text-sm">(Opsional)</span>
                        </label>
                        <textarea 
                            name="description" 
                            id="description" 
                            rows="4"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                            placeholder="Tambahkan deskripsi atau penjelasan tambahan tentang gejala ini..."
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            Deskripsi membantu memberikan informasi tambahan tentang gejala ini
                        </p>
                    </div>

                    <!-- Information Box -->
                    <div class="bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-500 p-4 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700 dark:text-blue-200">
                                    <strong>Catatan:</strong> Gejala Umum digunakan untuk screening awal, sedangkan Gejala Kritis digunakan untuk membedakan antar penyakit dengan bobot lebih tinggi dalam sistem scoring.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="ms-4">
                            {{ __('Tambah Data Gejala') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
