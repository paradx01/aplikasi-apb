<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row w-full justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Tambah Gejala untuk Penyakit') }}
            </h2>
            <a href="{{ route('admin.symptom-diseases.index') }}" class="font-bold py-3 px-5 rounded-full text-white bg-gray-600 hover:bg-gray-700 transition">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 sm:p-10">
                
                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                        <p class="font-semibold mb-2">Terdapat kesalahan:</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li class="text-sm">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ route('admin.symptom-diseases.store') }}" method="POST" id="symptom-disease-form">
                    @csrf

                    <!-- Step 1: Pilih Penyakit -->
                    <div class="mb-8 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <label for="disease_id" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-3">
                            1. Pilih Penyakit <span class="text-red-500">*</span>
                        </label>
                        <select name="disease_id" 
                                id="disease_id" 
                                class="w-full px-4 py-3 text-lg border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-200"
                                required>
                            <option value="">-- Pilih Penyakit --</option>
                            @foreach($diseases as $disease)
                                <option value="{{ $disease->id }}" {{ old('disease_id') == $disease->id ? 'selected' : '' }}>
                                    {{ $disease->disease_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('disease_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Step 2: Pilih Gejala -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <label class="block text-lg font-semibold text-gray-700 dark:text-gray-300">
                                2. Pilih Gejala untuk Penyakit Ini <span class="text-red-500">*</span>
                            </label>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                <span id="selected-count">0</span> gejala dipilih
                            </div>
                        </div>
                        
                        <!-- Info Box -->
                        <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="text-sm text-blue-700 dark:text-blue-300">
                                    <p class="font-semibold mb-1">Cara Menggunakan:</p>
                                    <ul class="list-disc list-inside space-y-1 text-xs">
                                        <li><strong>Centang checkbox</strong> untuk memilih gejala yang terkait dengan penyakit</li>
                                        <li><strong>Toggle Umum/Kritis</strong> untuk menentukan tingkat kepentingan gejala</li>
                                        <li><strong>Biru = Gejala Umum</strong> (weight 1x) - Gejala yang sering muncul di banyak penyakit</li>
                                        <li><strong>Merah = Gejala Kritis</strong> (weight 3x) - Gejala pembeda/ciri khas penyakit ini</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Grid Gejala 4 Kolom -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                            @foreach($symptoms as $symptom)
                                <div class="symptom-card border-2 border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-md transition-all"
                                     data-symptom-id="{{ $symptom->id }}">
                                    
                                    <!-- Checkbox Header -->
                                    <label class="flex items-start cursor-pointer mb-3">
                                        <input type="checkbox" 
                                               name="symptoms[]" 
                                               value="{{ $symptom->id }}"
                                               class="symptom-checkbox w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500 mt-0.5 flex-shrink-0"
                                               onchange="toggleSymptomCard(this)">
                                        <div class="ml-3 flex-1 min-w-0">
                                            <span class="font-semibold text-gray-900 dark:text-white text-sm leading-tight block">
                                                {{ $symptom->symptom_name }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 mt-1 block">
                                            </span>
                                        </div>
                                    </label>

                                    <!-- Toggle Umum/Kritis (Initially Hidden) -->
                                    <div class="criticality-toggle hidden">
                                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">
                                            Tingkat Kepentingan:
                                        </label>
                                        <div class="flex gap-2">
                                            <!-- Umum Button -->
                                            <button type="button" 
                                                    class="flex-1 py-2 px-3 rounded-lg border-2 transition-all text-xs font-semibold criticality-btn btn-common active"
                                                    data-value="0"
                                                    onclick="setCriticality(this, {{ $symptom->id }}, 0)">
                                                <div class="flex flex-col items-center">
                                                    <svg class="w-4 h-4 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                    <span>Umum</span>
                                                    <span class="text-xs opacity-75">1x</span>
                                                </div>
                                            </button>

                                            <!-- Kritis Button -->
                                            <button type="button" 
                                                    class="flex-1 py-2 px-3 rounded-lg border-2 transition-all text-xs font-semibold criticality-btn btn-critical"
                                                    data-value="1"
                                                    onclick="setCriticality(this, {{ $symptom->id }}, 1)">
                                                <div class="flex flex-col items-center">
                                                    <svg class="w-4 h-4 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                    </svg>
                                                    <span>Kritis</span>
                                                    <span class="text-xs opacity-75">3x</span>
                                                </div>
                                            </button>
                                        </div>

                                        <!-- Hidden Input for is_critical -->
                                        <input type="hidden" 
                                               name="is_critical[{{ $symptom->id }}]" 
                                               value="0" 
                                               class="criticality-input">
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @error('symptoms')
                            <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end mt-6">
                        <button type="submit" 
                                id="submit-btn"
                                class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-6 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                disabled>
                            <span class="flex items-center justify-center">
                                Tambah Data Diagnosa
                            </span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Toggle symptom card selection
        function toggleSymptomCard(checkbox) {
            const card = checkbox.closest('.symptom-card');
            const toggle = card.querySelector('.criticality-toggle');
            
            if (checkbox.checked) {
                card.classList.add('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
                card.classList.remove('border-gray-200', 'dark:border-gray-600');
                toggle.classList.remove('hidden');
            } else {
                card.classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
                card.classList.add('border-gray-200', 'dark:border-gray-600');
                toggle.classList.add('hidden');
            }
            
            updateSelectedCount();
        }

        // Set criticality (umum/kritis)
        function setCriticality(button, symptomId, value) {
            const card = button.closest('.symptom-card');
            const buttons = card.querySelectorAll('.criticality-btn');
            const input = card.querySelector('.criticality-input');
            
            // Remove active state from all buttons
            buttons.forEach(btn => {
                btn.classList.remove('active');
                if (btn.classList.contains('btn-common')) {
                    btn.classList.remove('bg-blue-500', 'text-white', 'border-blue-500');
                    btn.classList.add('bg-white', 'dark:bg-gray-700', 'text-blue-600', 'dark:text-blue-400', 'border-blue-300');
                } else {
                    btn.classList.remove('bg-red-500', 'text-white', 'border-red-500');
                    btn.classList.add('bg-white', 'dark:bg-gray-700', 'text-red-600', 'dark:text-red-400', 'border-red-300');
                }
            });
            
            // Add active state to clicked button
            button.classList.add('active');
            if (value == 0) {
                button.classList.add('bg-blue-500', 'text-white', 'border-blue-500');
                button.classList.remove('bg-white', 'dark:bg-gray-700', 'text-blue-600', 'dark:text-blue-400', 'border-blue-300');
            } else {
                button.classList.add('bg-red-500', 'text-white', 'border-red-500');
                button.classList.remove('bg-white', 'dark:bg-gray-700', 'text-red-600', 'dark:text-red-400', 'border-red-300');
            }
            
            // Set hidden input value
            input.value = value;
        }

        // Update selected count
        function updateSelectedCount() {
            const checked = document.querySelectorAll('.symptom-checkbox:checked').length;
            document.getElementById('selected-count').textContent = checked;
            
            // Enable/disable submit button
            const submitBtn = document.getElementById('submit-btn');
            const diseaseSelect = document.getElementById('disease_id');
            
            if (checked > 0 && diseaseSelect.value) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
        }

        // Listen to disease select change
        document.getElementById('disease_id').addEventListener('change', updateSelectedCount);

        // Initialize button styles on page load
        document.addEventListener('DOMContentLoaded', function() {
            const commonButtons = document.querySelectorAll('.btn-common');
            const criticalButtons = document.querySelectorAll('.btn-critical');
            
            commonButtons.forEach(btn => {
                btn.classList.add('bg-blue-500', 'text-white', 'border-blue-500');
            });
            
            criticalButtons.forEach(btn => {
                btn.classList.add('bg-white', 'dark:bg-gray-700', 'text-red-600', 'dark:text-red-400', 'border-red-300');
            });
        });
    </script>
</x-app-layout>
