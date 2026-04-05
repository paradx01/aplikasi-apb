<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row w-full justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Edit Gejala untuk: <span class="text-indigo-600">{{ $disease->disease_name }}</span>
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
                <form action="{{ route('admin.symptom-diseases.update', $disease->id) }}" method="POST" id="symptom-disease-form">
                    @csrf
                    @method('PUT')

                    <!-- Disease Info (Read Only) -->
                    <div class="mb-8 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-4 p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
                            <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm text-indigo-600 dark:text-indigo-400 font-medium">Penyakit</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $disease->disease_name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Saat ini</p>
                                <p class="text-lg font-semibold text-indigo-600 dark:text-indigo-400">
                                    {{ $existingSymptoms->count() }} gejala
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Pilih Gejala -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <label class="block text-lg font-semibold text-gray-700 dark:text-gray-300">
                                Pilih & Atur Gejala untuk Penyakit Ini
                            </label>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                <span id="selected-count">{{ $existingSymptoms->count() }}</span> gejala dipilih
                            </div>
                        </div>
                        
                        <!-- Info Box -->
                        <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="text-sm text-blue-700 dark:text-blue-300">
                                    <p class="font-semibold mb-1">Mode Edit:</p>
                                    <ul class="list-disc list-inside space-y-1 text-xs">
                                        <li><strong>Centang</strong> untuk memilih/menambah gejala</li>
                                        <li><strong>Uncheck</strong> untuk menghapus gejala dari penyakit ini</li>
                                        <li><strong>Toggle Umum/Kritis</strong> untuk mengubah tingkat kepentingan</li>
                                        <li>Gejala yang sudah dipilih akan otomatis ter-centang</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Grid Gejala 4 Kolom -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                            @foreach($symptoms as $symptom)
                                @php
                                    // Cek apakah gejala ini sudah terhubung dengan penyakit
                                    $existingRelation = $existingSymptoms->firstWhere('id', $symptom->id);
                                    $isChecked = $existingRelation !== null;
                                    $isCritical = $isChecked ? $existingRelation->pivot->is_critical : 0;
                                @endphp

                                <div class="symptom-card border-2 {{ $isChecked ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-200 dark:border-gray-600' }} rounded-lg p-4 hover:shadow-md transition-all"
                                     data-symptom-id="{{ $symptom->id }}">
                                    
                                    <!-- Checkbox Header -->
                                    <label class="flex items-start cursor-pointer mb-3">
                                        <input type="checkbox" 
                                               name="symptoms[]" 
                                               value="{{ $symptom->id }}"
                                               class="symptom-checkbox w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500 mt-0.5 flex-shrink-0"
                                               {{ $isChecked ? 'checked' : '' }}
                                               onchange="toggleSymptomCard(this)">
                                        <div class="ml-3 flex-1 min-w-0">
                                            <span class="font-semibold text-gray-900 dark:text-white text-sm leading-tight block">
                                                {{ $symptom->symptom_name }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 mt-1 block">
                                                Tipe: {{ ucfirst($symptom->type) }}
                                            </span>
                                        </div>
                                    </label>

                                    <!-- Toggle Umum/Kritis -->
                                    <div class="criticality-toggle {{ $isChecked ? '' : 'hidden' }}">
                                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">
                                            Tingkat Kepentingan:
                                        </label>
                                        <div class="flex gap-2">
                                            <!-- Umum Button -->
                                            <button type="button" 
                                                    class="flex-1 py-2 px-3 rounded-lg border-2 transition-all text-xs font-semibold criticality-btn btn-common {{ $isCritical == 0 ? 'active' : '' }}"
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
                                                    class="flex-1 py-2 px-3 rounded-lg border-2 transition-all text-xs font-semibold criticality-btn btn-critical {{ $isCritical == 1 ? 'active' : '' }}"
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
                                               value="{{ $isCritical }}" 
                                               class="criticality-input">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" 
                                id="submit-btn"
                                class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-6 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <span class="flex items-center justify-center">
                                Update Data Diagnosa
                            </span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- JavaScript (SAMA dengan Create) -->
    <script>
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

        function setCriticality(button, symptomId, value) {
            const card = button.closest('.symptom-card');
            const buttons = card.querySelectorAll('.criticality-btn');
            const input = card.querySelector('.criticality-input');
            
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
            
            button.classList.add('active');
            if (value == 0) {
                button.classList.add('bg-blue-500', 'text-white', 'border-blue-500');
                button.classList.remove('bg-white', 'dark:bg-gray-700', 'text-blue-600', 'dark:text-blue-400', 'border-blue-300');
            } else {
                button.classList.add('bg-red-500', 'text-white', 'border-red-500');
                button.classList.remove('bg-white', 'dark:bg-gray-700', 'text-red-600', 'dark:text-red-400', 'border-red-300');
            }
            
            input.value = value;
        }

        function updateSelectedCount() {
            const checked = document.querySelectorAll('.symptom-checkbox:checked').length;
            document.getElementById('selected-count').textContent = checked;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const commonButtons = document.querySelectorAll('.btn-common.active');
            const criticalButtons = document.querySelectorAll('.btn-critical.active');
            
            commonButtons.forEach(btn => {
                btn.classList.add('bg-blue-500', 'text-white', 'border-blue-500');
                btn.classList.remove('bg-white', 'dark:bg-gray-700', 'text-blue-600', 'dark:text-blue-400', 'border-blue-300');
            });
            
            criticalButtons.forEach(btn => {
                btn.classList.add('bg-red-500', 'text-white', 'border-red-500');
                btn.classList.remove('bg-white', 'dark:bg-gray-700', 'text-red-600', 'dark:text-red-400', 'border-red-300');
            });
            
            // Style non-active buttons
            document.querySelectorAll('.btn-common:not(.active)').forEach(btn => {
                btn.classList.add('bg-white', 'dark:bg-gray-700', 'text-blue-600', 'dark:text-blue-400', 'border-blue-300');
            });
            
            document.querySelectorAll('.btn-critical:not(.active)').forEach(btn => {
                btn.classList.add('bg-white', 'dark:bg-gray-700', 'text-red-600', 'dark:text-red-400', 'border-red-300');
            });
        });
    </script>
</x-app-layout>
