<x-app-layout>
  <x-slot name="header">
      <div class="flex flex-row w-full justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Data Produk') }}
            </h2>
            <a href="{{ route('admin.products.index') }}" class="font-bold py-3 px-5 rounded-full text-white bg-gray-500 hover:bg-gray-600 transition">
                kembali
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
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          <!-- Kategori -->
          <div class="mt-4">
            <x-input-label for="category_id" :value="__('Kategori Obat')" />
            <select id="category_id" name="category_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
              <option value="">-- Pilih Kategori --</option>
              @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
          </div>

          <!-- Nama Produk -->
          <div class="mt-4">
            <x-input-label for="name" :value="__('Nama Produk')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $product->name)" required />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
          </div>

          <!-- Harga -->
          <div class="mt-4">
            <x-input-label for="price" :value="__('Harga')" />
            <x-text-input id="price" class="block mt-1 w-full" type="number" step="0.01" name="price" :value="old('price', $product->price)" required />
            <x-input-error :messages="$errors->get('price')" class="mt-2" />
          </div>

          <!-- Stok -->
          <div class="mt-4">
            <x-input-label for="stok" :value="__('Stok Tersedia')" />
            <x-text-input id="stok" class="block mt-1 w-full" type="number" name="stok" :value="old('stok', $product->stok)" required />
            <x-input-error :messages="$errors->get('stok')" class="mt-2" />
          </div>

          <!-- Foto Produk -->
          <div class="mt-4">
            <x-input-label :value="__('Foto Saat Ini')" />
            @if($product->photo)
              <img src="{{ Storage::url($product->photo) }}" alt="Foto lama" class="w-24 h-24 object-cover rounded-md mt-2">
              <p class="text-sm text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah.</p>
            @endif
          </div>
          <div class="mt-2">
            <x-input-label for="photo" :value="__('Upload Foto Baru (Opsional)')" />
            <x-text-input id="photo" class="block mt-1 w-full" type="file" name="photo"/>
            <x-input-error :messages="$errors->get('photo')" class="mt-2" />
          </div>

          <!-- Section Data Klinis -->
          <hr class="my-6 border-gray-300">
          <h3 class="text-lg font-semibold text-gray-700">Data Klinis (Sistem Pakar)</h3>
          
          <!-- Zat Aktif -->
          <div class="mt-4">
            <x-input-label for="active_ingredients" :value="__('Zat Aktif (pisahkan koma)')" />
            <x-text-input id="active_ingredients" class="block mt-1 w-full" type="text" name="active_ingredients" :value="old('active_ingredients', $product->active_ingredients)" required />
            <x-input-error :messages="$errors->get('active_ingredients')" class="mt-2" />
          </div>

          <!-- Komposisi -->
          <div class="mt-4">
            <x-input-label for="composition" :value="__('Komposisi')" />
            <textarea id="composition" name="composition" rows="2" class="block mt-1 w-full border-gray-300 rounded-md">{{ old('composition', $product->composition) }}</textarea>
            <x-input-error :messages="$errors->get('composition')" class="mt-2" />
          </div>

          <!-- Kontraindikasi -->
          <div class="mt-4">
            <x-input-label for="contraindications" :value="__('Kontraindikasi')" />
            <div class="space-y-2 mt-2 p-3 border rounded-md bg-gray-50">
              @foreach ($risk_options as $value => $label)
                @php
                  $checked = in_array($value, $current_risks) || (is_array(old('contraindications')) && in_array($value, old('contraindications')));
                @endphp
                <div>
                  <input 
                    id="risk_{{ Str::slug($value) }}" type="checkbox" 
                    name="contraindications[]" value="{{ $value }}"
                    class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500"
                    {{ $checked ? 'checked' : '' }}
                  >
                  <label for="risk_{{ Str::slug($value) }}" class="ml-2 text-sm text-gray-700 font-medium">{{ $label }}</label>
                </div>
              @endforeach
            </div>
            <x-input-error :messages="$errors->get('contraindications')" class="mt-2" />
          </div>

          <!-- Indikasi -->
          <div class="mt-4">
            <x-input-label for="indications" :value="__('Indikasi')" />
            <textarea id="indications" name="indications" rows="2" class="block mt-1 w-full border-gray-300 rounded-md">{{ old('indications', $product->indications) }}</textarea>
            <x-input-error :messages="$errors->get('indications')" class="mt-2" />
          </div>
          
          <!-- Efek Samping -->
          <div class="mt-4">
            <x-input-label for="side_effects" :value="__('Efek Samping')" />
            <textarea id="side_effects" name="side_effects" rows="2" class="block mt-1 w-full border-gray-300 rounded-md">{{ old('side_effects', $product->side_effects) }}</textarea>
            <x-input-error :messages="$errors->get('side_effects')" class="mt-2" />
          </div>
          
          <!-- Bentuk Sediaan -->
          <div class="mt-4">
            <x-input-label for="dosage_form" :value="__('Bentuk Sediaan')" />
            <x-text-input id="dosage_form" class="block mt-1 w-full" type="text" name="dosage_form" :value="old('dosage_form', $product->dosage_form)" required />
            <x-input-error :messages="$errors->get('dosage_form')" class="mt-2" />
          </div>


          <!-- Kategori Kehamilan -->
          <div class="mt-4">
            <x-input-label for="pregnancy_category" :value="__('Kategori Kehamilan')" />
            <select id="pregnancy_category" name="pregnancy_category" class="block mt-1 w-full border-gray-300 rounded-md">
              <option value="">-- Pilih Kategori --</option>
              @foreach(['A','B','C','D','X'] as $cat)
                <option value="{{ $cat }}" {{ old('pregnancy_category', $product->pregnancy_category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
              @endforeach
            </select>
            <x-input-error :messages="$errors->get('pregnancy_category')" class="mt-2" />
          </div>

          <hr class="my-6 border-gray-300">

          <!-- Section Aturan Dosis Medication Rules (Lanjutan dari jawaban sebelumnya) -->
          <h3 class="text-lg font-semibold text-gray-700 mt-6">Aturan Dosis per Kategori Usia</h3>
            <div class="flex flex-col gap-4 mt-2">
              <!-- Satuan -->
              <div class="mt-4">
                <x-input-label for="unit" :value="__('Satuan Dosis')" />
                <x-text-input id="unit" class="block mt-1 w-full" type="text" name="unit" :value="old('unit', $product->unit)" required />
                <x-input-error :messages="$errors->get('unit')" class="mt-2" />
              </div>
            @foreach($medication_rules as $i => $rule)
              <div class="bg-gray-50 rounded-xl p-4 flex flex-col gap-3 shadow-sm">
                <div class="text-[15px] mb-2">Kategori: {{ old("rules.$i.special_condition", $rule->special_condition ?? '') }}</div>
                <div class="flex flex-col gap-2">
                  <div class="flex gap-2">
                    <div class="flex-1">
                      <label class="block text-xs font-medium mb-1">Usia Min</label>
                      <input type="number" name="rules[{{$i}}][min_age]" min="0"
                            class="w-full px-3 py-2 rounded-md border border-gray-300 bg-white"
                            value="{{ old("rules.$i.min_age", $rule->min_age) }}">
                    </div>
                    <div class="flex-1">
                      <label class="block text-xs font-medium mb-1">Usia Max</label>
                      <input type="number" name="rules[{{$i}}][max_age]" min="0"
                            class="w-full px-3 py-2 rounded-md border border-gray-300 bg-white"
                            value="{{ old("rules.$i.max_age", $rule->max_age) }}">
                    </div>
                  </div>
                  <div class="flex gap-2">
                    <div class="flex-1">
                      <label class="block text-xs font-medium mb-1">Dosis</label>
                      <input type="text" name="rules[{{$i}}][dosage]" class="w-full px-3 py-2 rounded-md border border-gray-300 bg-white"
                            value="{{ old("rules.$i.dosage", $rule->default_dosage) }}">
                    </div>
                    <div class="flex-1">
                      <label class="block text-xs font-medium mb-1">Petunjuk Pakai</label>
                      <input type="text" name="rules[{{$i}}][usage_instruction]" class="w-full px-3 py-2 rounded-md border border-gray-300 bg-white"
                            value="{{ old("rules.$i.usage_instruction", $rule->usage_instruction) }}">
                    </div>
                  </div>
                  <div class="flex gap-2">
                    <div class="flex-1">
                      <label class="block text-xs font-medium mb-1">Frekuensi (kali/hari)</label>
                      <input type="number" name="rules[{{$i}}][frequency]" min="1" max="10"
                            class="w-full px-3 py-2 rounded-md border border-gray-300 bg-white"
                            value="{{ old("rules.$i.frequency", $rule->default_frequency) }}">
                    </div>
                    <div class="flex-1">
                      <label class="block text-xs font-medium mb-1">Durasi (hari)</label>
                      <input type="text" name="rules[{{$i}}][duration]" class="w-full px-3 py-2 rounded-md border border-gray-300 bg-white"
                            value="{{ old("rules.$i.duration", $rule->duration) }}">
                    </div>
                  </div>
                  <input type="hidden" name="rules[{{$i}}][special_condition]" value="{{ old("rules.$i.special_condition", $rule->special_condition) }}">
                </div>
              </div>
            @endforeach
          </div>

          <div class="flex items-center justify-end mt-6">
            <x-primary-button class="ms-4 bg-indigo-600 hover:bg-indigo-700">
              {{ __('Update Data Produk') }}
            </x-primary-button>
          </div>
        </form>
      </div>
    </div>
  </div>
</x-app-layout>
