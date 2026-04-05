<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row w-full justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Data Penyakit') }}
            </h2>
            <a href="{{ route('admin.diseases.index') }}" class="font-bold py-3 px-5 rounded-full text-white bg-gray-500 hover:bg-gray-600 transition">
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

                <form method="POST" action="{{ route('admin.diseases.update', $disease) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Symptom Name -->
                    <div>
                        <label for="symptom_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nama Gejala <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="disease_name" 
                            id="disease_name" 
                            value="{{ old('disease_name', $disease->disease_name) }}"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                            placeholder="Contoh: Migrain"
                            required
                            autofocus
                        >
                        @error('disease_name')
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
                            placeholder="Tambahkan deskripsi atau penjelasan tambahan tentang penyakit ini..."
                        >{{ old('description', $disease->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            Deskripsi membantu memberikan informasi tambahan tentang penyakit ini
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end mt-6">
                        <x-primary-button class="ms-4 bg-indigo-600 hover:bg-indigo-700">
                        {{ __('Update Data Penyakit') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
