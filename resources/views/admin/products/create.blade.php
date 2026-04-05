<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row w-full justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Tambah Data Produk') }}
            </h2>
            <a href="{{ route('admin.products.index') }}" class="font-bold py-3 px-5 rounded-full text-white bg-gray-500 hover:bg-gray-600 transition">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden p-10 shadow-sm sm:rounded-lg">
                @if($errors->any())
                    @foreach($errors->all() as $error)
                        <div class="py-3 w-full rounded-3xl bg-red-500 text-white">
                            {{$error}}
                        </div>
                    @endforeach
                @endif
                <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Kategori (Category ID) -->
                    <div class="mt-4">
                        <x-input-label for="category" :value="__('Kategori Obat')" />
                        <select id="category_id" name="category_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            <option value="">-- Pilih Kategori --</option>
                            @forelse ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @empty
                            @endforelse
                        </select>
                        <x-input-error :messages="$errors->get('category')" class="mt-2" />
                    </div>

                    <!-- Name -->
                    <div class="mt-4">
                        <x-input-label for="name" :value="__('Nama Produk')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Harga -->
                    <div class="mt-4">
                        <x-input-label for="price" :value="__('Harga')" />
                        <x-text-input id="price" class="block mt-1 w-full" type="number" step="0.01" name="price" :value="old('price')" required autofocus autocomplete="price"/>
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                    </div>

                    <!-- Stok -->
                    <div class="mt-4">
                        <x-input-label for="stock" :value="__('Stok Tersedia')" />
                        <x-text-input id="stock" class="block mt-1 w-full" type="number" name="stock" :value="old('stock')" required autofocus autocomplete="stock"/>
                        <x-input-error :messages="$errors->get('stok')" class="mt-2" />
                    </div>

                    <!-- Foto Obat -->
                    <div class="mt-4">
                        <x-input-label for="photo" :value="__('Foto Obat')" />
                        <x-text-input id="photo" class="block mt-1 w-full" type="file" name="photo" required autofocus autocomplete="photo" />
                        <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                    </div>

                    <hr class="my-6 border-gray-300">
                    <h3 class="text-lg font-semibold text-gray-700">Data Klinis (Sistem Pakar)</h3>

                    <!-- Zat Aktif (Active Ingredients) -->
                    <div class="mt-4">
                        <x-input-label for="active_ingredients" :value="__('Zat Aktif (Dipisahkan Koma)')" />
                        <p class="text-sm text-gray-500">Contoh: Paracetamol, Pseudoefedrin, CTM</p>
                        <x-text-input id="active_ingredients" class="block mt-1 w-full" type="text" name="active_ingredients" :value="old('active_ingredients')" required autofocus autocomplete="active_ingredients"/>
                        <x-input-error :messages="$errors->get('active_ingredients')" class="mt-2" />
                    </div>

                    <!-- Kontraindikasi (Checkbox Terstruktur) -->
                    <div class="mt-4">
                        <x-input-label for="contraindications" :value="__('Kontraindikasi (Filter Keamanan Wajib)')" />
                        <p class="text-xs text-gray-500 mt-1 mb-2">
                            Pilih kondisi kesehatan yang menjadi kontraindikasi untuk obat ini. Sistem akan memfilter obat ini dari rekomendasi user dengan kondisi tersebut.
                        </p>
                        
                        @php
                            // Ambil nilai lama: dari old() atau dari product existing (array)
                            $selectedContra = old('contraindications', $product->contraindications ?? []);
                            if (!is_array($selectedContra)) {
                                $selectedContra = $selectedContra ? json_decode($selectedContra, true) : [];
                            }
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-2 p-4 border rounded-md bg-gray-50 dark:bg-gray-800">
                            
                            <!-- 1. Hipertensi -->
                            <div class="flex items-start">
                                <input 
                                    id="contra_hypertension" 
                                    type="checkbox" 
                                    name="contraindications[]" 
                                    value="Hipertensi" 
                                    {{ in_array('Hipertensi', $selectedContra) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500 mt-1"
                                >
                                <label for="contra_hypertension" class="ml-2 text-sm text-gray-700 dark:text-gray-300 font-medium">
                                    Hipertensi (Tekanan Darah Tinggi)
                                </label>
                            </div>

                            <!-- 2. Gangguan Jantung -->
                            <div class="flex items-start">
                                <input 
                                    id="contra_heart_disorder" 
                                    type="checkbox" 
                                    name="contraindications[]" 
                                    value="Gangguan Jantung" 
                                    {{ in_array('Gangguan Jantung', $selectedContra) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500 mt-1"
                                >
                                <label for="contra_heart_disorder" class="ml-2 text-sm text-gray-700 dark:text-gray-300 font-medium">
                                    Gangguan Jantung
                                </label>
                            </div>

                            <!-- 3. Diabetes -->
                            <div class="flex items-start">
                                <input 
                                    id="contra_diabetes" 
                                    type="checkbox" 
                                    name="contraindications[]" 
                                    value="Diabetes" 
                                    {{ in_array('Diabetes', $selectedContra) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500 mt-1"
                                >
                                <label for="contra_diabetes" class="ml-2 text-sm text-gray-700 dark:text-gray-300 font-medium">
                                    Diabetes (Kencing Manis)
                                </label>
                            </div>

                            <!-- 4. Maag/Tukak Lambung -->
                            <div class="flex items-start">
                                <input 
                                    id="contra_stomach_ulcer" 
                                    type="checkbox" 
                                    name="contraindications[]" 
                                    value="Tukak Lambung" 
                                    {{ in_array('Tukak Lambung', $selectedContra) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500 mt-1"
                                >
                                <label for="contra_stomach_ulcer" class="ml-2 text-sm text-gray-700 dark:text-gray-300 font-medium">
                                    Maag / Tukak Lambung
                                </label>
                            </div>

                            <!-- 5. Gangguan Ginjal -->
                            <div class="flex items-start">
                                <input 
                                    id="contra_kidney_disorder" 
                                    type="checkbox" 
                                    name="contraindications[]" 
                                    value="Gangguan Ginjal" 
                                    {{ in_array('Gangguan Ginjal', $selectedContra) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500 mt-1"
                                >
                                <label for="contra_kidney_disorder" class="ml-2 text-sm text-gray-700 dark:text-gray-300 font-medium">
                                    Gangguan Ginjal
                                </label>
                            </div>

                            <!-- 6. Gangguan Hati/Liver -->
                            <div class="flex items-start">
                                <input 
                                    id="contra_liver_disorder" 
                                    type="checkbox" 
                                    name="contraindications[]" 
                                    value="Gangguan Hati" 
                                    {{ in_array('Gangguan Hati', $selectedContra) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500 mt-1"
                                >
                                <label for="contra_liver_disorder" class="ml-2 text-sm text-gray-700 dark:text-gray-300 font-medium">
                                    Gangguan Hati / Liver
                                </label>
                            </div>

                            <!-- 7. Asma -->
                            <div class="flex items-start">
                                <input 
                                    id="contra_asthma" 
                                    type="checkbox" 
                                    name="contraindications[]" 
                                    value="Asma" 
                                    {{ in_array('Asma', $selectedContra) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500 mt-1"
                                >
                                <label for="contra_asthma" class="ml-2 text-sm text-gray-700 dark:text-gray-300 font-medium">
                                    Asma
                                </label>
                            </div>

                            <!-- 8. Glaukoma -->
                            <div class="flex items-start">
                                <input 
                                    id="contra_glaucoma" 
                                    type="checkbox" 
                                    name="contraindications[]" 
                                    value="Glaukoma" 
                                    {{ in_array('Glaukoma', $selectedContra) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500 mt-1"
                                >
                                <label for="contra_glaucoma" class="ml-2 text-sm text-gray-700 dark:text-gray-300 font-medium">
                                    Glaukoma (Tekanan Bola Mata Tinggi)
                                </label>
                            </div>

                            <!-- 9. Gangguan Prostat -->
                            <div class="flex items-start">
                                <input 
                                    id="contra_prostate_disorder" 
                                    type="checkbox" 
                                    name="contraindications[]" 
                                    value="Gangguan Prostat" 
                                    {{ in_array('Gangguan Prostat', $selectedContra) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500 mt-1"
                                >
                                <label for="contra_prostate_disorder" class="ml-2 text-sm text-gray-700 dark:text-gray-300 font-medium">
                                    Gangguan Prostat / Pembesaran Prostat (BPH)
                                </label>
                            </div>

                            <!-- 10. Hipertiroidisme -->
                            <div class="flex items-start">
                                <input 
                                    id="contra_hyperthyroidism" 
                                    type="checkbox" 
                                    name="contraindications[]" 
                                    value="Hipertiroidisme" 
                                    {{ in_array('Hipertiroidisme', $selectedContra) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500 mt-1"
                                >
                                <label for="contra_hyperthyroidism" class="ml-2 text-sm text-gray-700 dark:text-gray-300 font-medium">
                                    Hipertiroidisme
                                </label>
                            </div>

                            <!-- 11. Defisiensi G6PD -->
                            <div class="flex items-start">
                                <input 
                                    id="contra_g6pd" 
                                    type="checkbox" 
                                    name="contraindications[]" 
                                    value="Defisiensi G6PD" 
                                    {{ in_array('Defisiensi G6PD', $selectedContra) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500 mt-1"
                                >
                                <label for="contra_g6pd" class="ml-2 text-sm text-gray-700 dark:text-gray-300 font-medium">
                                    Defisiensi G6PD
                                </label>
                            </div>

                            <!-- 12. Alergi Paracetamol -->
                            <div class="flex items-start">
                                <input 
                                    id="contra_allergy_paracetamol" 
                                    type="checkbox" 
                                    name="contraindications[]" 
                                    value="Alergi Paracetamol" 
                                    {{ in_array('Alergi Paracetamol', $selectedContra) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500 mt-1"
                                >
                                <label for="contra_allergy_paracetamol" class="ml-2 text-sm text-gray-700 dark:text-gray-300 font-medium">
                                    Alergi Paracetamol (Sanmol, Panadol, Bodrex, dll)
                                </label>
                            </div>

                            <!-- 13. Alergi NSAID -->
                            <div class="flex items-start">
                                <input 
                                    id="contra_allergy_nsaid" 
                                    type="checkbox" 
                                    name="contraindications[]" 
                                    value="Alergi NSAID" 
                                    {{ in_array('Alergi NSAID', $selectedContra) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500 mt-1"
                                >
                                <label for="contra_allergy_nsaid" class="ml-2 text-sm text-gray-700 dark:text-gray-300 font-medium">
                                    Alergi NSAID (Ibuprofen, Asam Mefenamat, dll)
                                </label>
                            </div>

                            <!-- 14. Alergi Aspirin -->
                            <div class="flex items-start">
                                <input 
                                    id="contra_allergy_aspirin" 
                                    type="checkbox" 
                                    name="contraindications[]" 
                                    value="Alergi Aspirin" 
                                    {{ in_array('Alergi Aspirin', $selectedContra) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500 mt-1"
                                >
                                <label for="contra_allergy_aspirin" class="ml-2 text-sm text-gray-700 dark:text-gray-300 font-medium">
                                    Alergi Aspirin
                                </label>
                            </div>

                            <!-- 15. Alergi Antihistamin -->
                            <div class="flex items-start">
                                <input 
                                    id="contra_allergy_antihistamine" 
                                    type="checkbox" 
                                    name="contraindications[]" 
                                    value="Alergi Antihistamin" 
                                    {{ in_array('Alergi Antihistamin', $selectedContra) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500 mt-1"
                                >
                                <label for="contra_allergy_antihistamine" class="ml-2 text-sm text-gray-700 dark:text-gray-300 font-medium">
                                    Alergi Antihistamin (CTM, Cetirizine, Loratadine, dll)
                                </label>
                            </div>

                            <!-- 16. Alergi Dekongestan -->
                            <div class="flex items-start">
                                <input 
                                    id="contra_allergy_decongestant" 
                                    type="checkbox" 
                                    name="contraindications[]" 
                                    value="Alergi Dekongestan" 
                                    {{ in_array('Alergi Dekongestan', $selectedContra) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500 mt-1"
                                >
                                <label for="contra_allergy_decongestant" class="ml-2 text-sm text-gray-700 dark:text-gray-300 font-medium">
                                    Alergi Dekongestan (Pseudoefedrin)
                                </label>
                            </div>

                            <!-- 17. Alergi Bromhexine -->
                            <div class="flex items-start">
                                <input 
                                    id="contra_allergy_bromhexine" 
                                    type="checkbox" 
                                    name="contraindications[]" 
                                    value="Alergi Bromhexine" 
                                    {{ in_array('Alergi Bromhexine', $selectedContra) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500 mt-1"
                                >
                                <label for="contra_allergy_bromhexine" class="ml-2 text-sm text-gray-700 dark:text-gray-300 font-medium">
                                    Alergi Bromhexine
                                </label>
                            </div>

                            <!-- 18. Alergi Guaifenesin -->
                            <div class="flex items-start">
                                <input 
                                    id="contra_allergy_guaifenesin" 
                                    type="checkbox" 
                                    name="contraindications[]" 
                                    value="Alergi Guaifenesin" 
                                    {{ in_array('Alergi Guaifenesin', $selectedContra) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500 mt-1"
                                >
                                <label for="contra_allergy_guaifenesin" class="ml-2 text-sm text-gray-700 dark:text-gray-300 font-medium">
                                    Alergi Guaifenesin
                                </label>
                            </div>

                            <!-- 19. Alergi Antasida -->
                            <div class="flex items-start">
                                <input 
                                    id="contra_allergy_antacid" 
                                    type="checkbox" 
                                    name="contraindications[]" 
                                    value="Alergi Antasida" 
                                    {{ in_array('Alergi Antasida', $selectedContra) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500 mt-1"
                                >
                                <label for="contra_allergy_antacid" class="ml-2 text-sm text-gray-700 dark:text-gray-300 font-medium">
                                    Alergi Antasida
                                </label>
                            </div>

                        </div>

                        <!-- Info Box -->
                        <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-500 rounded">
                            <div class="flex">
                                <svg class="w-5 h-5 text-blue-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div class="text-sm text-blue-700 dark:text-blue-200">
                                    <strong>Catatan:</strong> Obat ini akan <strong>TIDAK ditampilkan</strong> kepada user yang memiliki kondisi kesehatan yang dicentang di atas. Untuk kehamilan, gunakan field "Pregnancy Category" di bawah.
                                </div>
                            </div>
                        </div>

                        <x-input-error :messages="$errors->get('contraindications')" class="mt-2" />
                    </div>

                    <!-- Komposisi (Composition) -->
                    <div class="mt-4">
                        <x-input-label for="composition" :value="__('Komposisi Detail')" />
                        <textarea id="composition" name="composition" rows="2"
                            class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            required>{{ old('composition') }}</textarea>
                        <x-input-error :messages="$errors->get('composition')" class="mt-2" />
                    </div>

                    <!-- Indikasi (Indications) -->
                    <div class="mt-4">
                        <x-input-label for="indications" :value="__('Indikasi Umum')" />
                        <textarea id="indications" name="indications" rows="2"
                            class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            required>{{ old('indications') }}</textarea>
                        <x-input-error :messages="$errors->get('indications')" class="mt-2" />
                    </div>

                    <!-- Efek Samping (Side Effects) -->
                    <div class="mt-4">
                        <x-input-label for="side_effects" :value="__('Efek Samping')" />
                        <textarea id="side_effects" name="side_effects" rows="2"
                            class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            required>{{ old('side_effects') }}</textarea>
                        <x-input-error :messages="$errors->get('side_effects')" class="mt-2" />
                    </div>

                    <!-- Bentuk Sediaan -->
                    <div class="mt-4">
                        <x-input-label for="dosage_form" :value="__('Bentuk Sediaan')" />
                        <p class="text-sm text-gray-500">Tablet, Sirup, Kapsul, dsb</p>
                        <x-text-input id="dosage_form" class="block mt-1 w-full" type="text"
                            name="dosage_form" :value="old('dosage_form')" required autofocus/>
                        <x-input-error :messages="$errors->get('dosage_form')" class="mt-2" />
                    </div>

                    <!-- Kategori Kehamilan -->
                    <div class="mt-4 mb-4">
                        <x-input-label for="pregnancy_category" :value="__('Kategori Kehamilan')" />
                        <select id="pregnancy_category" name="pregnancy_category" class="block mt-1 w-full border-gray-300 rounded-md">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="A">A [Aman, uji tuntas pada ibu & janin (praktis hampir tidak ada)]</option>
                            <option value="B">B [Risiko rendah (masih boleh jika manfaat melebihi risiko)]</option>
                            <option value="C">C [Risiko sedang (masih boleh jika manfaat melebihi risiko)]</option>
                            <option value="D">D [Berisiko nyata pada janin, hanya dipakai jika sangat butuh]</option>
                            <option value="X">X [Contraindicated — dilarang keras untuk ibu hamil]</option>
                        </select>
                        <x-input-error :messages="$errors->get('pregnancy_category')" class="mt-2" />
                    </div>

                    <hr class="my-6 border-gray-300">
                    <h3 class="text-lg font-semibold text-gray-700 mt-6">Aturan Dosis per Kategori Usia</h3>

                    <!-- Pengaturan Dosis -->
                    <div class="mt-4">
                        <x-input-label for="unit" :value="__('Satuan Dosis')" />
                        <p class="text-sm text-gray-500">mg, ml, dsb</p>
                        <x-text-input id="unit" class="block mt-1 w-full" type="text"
                            name="unit" :value="old('unit')" required autofocus/>
                        <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                    </div>
                    
                    <div class="flex flex-col gap-4 mt-4">
                        @foreach(['Anak-anak', 'Dewasa', 'Lansia'] as $i => $kategori)
                        <div class="flex flex-col gap-3 shadow-sm">
                            <div class="text-[15px] mb-2">Kategori: {{ $kategori }}</div>
                            <div class="flex flex-col gap-2">
                            <div class="flex gap-2">
                                <div class="flex-1">
                                <label class="block text-xs font-medium mb-1">Usia Min</label>
                                <input type="number" name="rules[{{$i}}][min_age]" min="0" class="w-full px-3 py-2 rounded-md border border-gray-300 bg-white">
                                </div>
                                <div class="flex-1">
                                <label class="block text-xs font-medium mb-1">Usia Max</label>
                                <input type="number" name="rules[{{$i}}][max_age]" min="0" class="w-full px-3 py-2 rounded-md border border-gray-300 bg-white">
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <div class="flex-1">
                                <label class="block text-xs font-medium mb-1">Dosis</label>
                                <input type="text" name="rules[{{$i}}][dosage]" class="w-full px-3 py-2 rounded-md border border-gray-300 bg-white" placeholder="Contoh: 1/2 tablet, 1 sendok teh" required>
                                </div>
                                <div class="flex-1">
                                <label class="block text-xs font-medium mb-1">Petunjuk Pemakaian</label>
                                <input type="text" name="rules[{{$i}}][usage_instruction]" class="w-full px-3 py-2 rounded-md border border-gray-300 bg-white" placeholder="Contoh: Setelah makan" required>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <div class="flex-1">
                                <label class="block text-xs font-medium mb-1">Frekuensi (kali/hari)</label>
                                <input type="number" name="rules[{{$i}}][frequency]" min="1" max="10" class="w-full px-3 py-2 rounded-md border border-gray-300 bg-white" required>
                                </div>
                                <div class="flex-1">
                                <label class="block text-xs font-medium mb-1">Durasi (hari)</label>
                                <input type="text" name="rules[{{$i}}][duration]" class="w-full px-3 py-2 rounded-md border border-gray-300 bg-white" required>
                                </div>
                            </div>
                            <input type="hidden" name="rules[{{$i}}][special_condition]" value="{{ $kategori }}">
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="ms-4">
                            {{ __('Tambah Data Produk') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
