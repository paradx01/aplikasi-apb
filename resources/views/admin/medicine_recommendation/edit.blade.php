<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Edit Rekomendasi Obat
            </h2>
            <a href="{{ route('admin.medicine-recommendation.index') }}"
               class="font-bold py-2 px-4 rounded-full text-white bg-gray-600 hover:bg-gray-700 transition">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if ($errors->any())
                <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg">
                    <p class="font-semibold mb-2">Terdapat kesalahan:</p>
                    <ul class="list-disc list-inside space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.medicine-recommendation.update', $recommendation->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Left Column: Form Fields -->
                    <div class="lg:col-span-2 space-y-6">
                        
                        <!-- Card: Penyakit -->
                        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">
                                Penyakit
                            </h3>
                            <select name="disease_id" id="disease_id"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                           focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200" required>
                                <option value="">-- Pilih Penyakit --</option>
                                @foreach($diseases as $disease)
                                    <option value="{{ $disease->id }}"
                                        {{ old('disease_id', $recommendation->disease_id) == $disease->id ? 'selected' : '' }}>
                                        {{ $disease->disease_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Card: Profil Target Pasien (Umur) -->
                        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Profil Target Pasien
                            </h3>

                            <div class="space-y-5">
                                <!-- Umur -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Rentang Umur Target <span class="text-red-500">*</span>
                                    </label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <input type="number" name="min_age"
                                                   value="{{ old('min_age', $recommendation->min_age) }}"
                                                   min="0" max="150" placeholder="Min"
                                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                                          focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200" required>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Minimal (tahun)</p>
                                        </div>
                                        <div>
                                            <input type="number" name="max_age"
                                                   value="{{ old('max_age', $recommendation->max_age) }}"
                                                   min="0" max="150" placeholder="Max"
                                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                                          focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200" required>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Maksimal (tahun)</p>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Contoh: 0–12 untuk anak (sirup), 12–120 untuk dewasa (tablet).
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Card: Priority & Notes -->
                        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">
                                Pengaturan Rekomendasi
                            </h3>

                            <div class="space-y-5">
                                <div>
                                    <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Prioritas <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="priority" id="priority"
                                           value="{{ old('priority', $recommendation->priority) }}"
                                           min="1" max="100"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                                  focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200" required>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Semakin kecil angka = prioritas lebih tinggi (1 = paling utama).
                                    </p>
                                </div>

                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Catatan Tambahan (opsional)
                                    </label>
                                    <textarea name="notes" id="notes" rows="3"
                                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                                     focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                                              placeholder="Catatan khusus untuk rekomendasi ini (misal: gunakan bila keluhan dominan mual, dsb)...">{{ old('notes', $recommendation->notes) }}</textarea>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Right Column: Product Selection -->
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 lg:sticky lg:top-6">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                Pilih Produk
                            </h3>

                            <!-- Search -->
                            <div class="mb-4">
                                <input type="text" id="search-product"
                                       placeholder="Cari produk..."
                                       class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                                              focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                            </div>

                            <!-- Product Grid -->
                            <div class="space-y-2 max-h-[600px] overflow-y-auto pr-2" id="product-grid">
                                @foreach($products as $product)
                                    @php
                                        $isSelected = old('product_id', $recommendation->product_id) == $product->id;
                                    @endphp
                                    <div class="product-card border-2 rounded-lg p-3 cursor-pointer transition hover:shadow-md
                                                {{ $isSelected ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-200 dark:border-gray-600' }}"
                                         data-product-name="{{ strtolower($product->name) }}">
                                        <label class="flex items-center gap-3 cursor-pointer">
                                            <input type="radio" name="product_id" value="{{ $product->id }}"
                                                   class="product-radio w-5 h-5 text-indigo-600 focus:ring-indigo-500 flex-shrink-0"
                                                   {{ $isSelected ? 'checked' : '' }}
                                                   onchange="toggleProductCard(this)"
                                                   required>
                                            
                                            <!-- Product Image -->
                                            <div class="w-12 h-12 flex-shrink-0 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden">
                                                @if(!empty($product->photo))
                                                    <img src="{{ Storage::url($product->photo) }}" 
                                                         alt="{{ $product->name }}"
                                                         class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Product Name -->
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 line-clamp-2">
                                                    {{ $product->name }}
                                                </p>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            @error('product_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                </div>

                <!-- Submit Buttons -->
                <div class="mt-6 flex flex-col sm:flex-row gap-3">
                    <button type="submit"
                            class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition">
                        Update Rekomendasi
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script>
        function toggleProductCard(radio) {
            document.querySelectorAll('.product-card').forEach(card => {
                card.classList.remove('border-indigo-500','bg-indigo-50','dark:bg-indigo-900/20');
                card.classList.add('border-gray-200','dark:border-gray-600');
            });
            
            const card = radio.closest('.product-card');
            card.classList.add('border-indigo-500','bg-indigo-50','dark:bg-indigo-900/20');
            card.classList.remove('border-gray-200','dark:border-gray-600');
        }

        document.getElementById('search-product').addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('.product-card').forEach(card => {
                const name = card.getAttribute('data-product-name');
                card.style.display = name.includes(term) ? 'block' : 'none';
            });
        });
    </script>
</x-app-layout>
