<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row w-full justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Halaman Data Diagnosa') }}
            </h2>
            <a href="{{ route('admin.symptom-diseases.create') }}" class="font-bold py-3 px-5 rounded-full text-white bg-indigo-700 hover:bg-indigo-800 transition">
                Tambah Diagnosa
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Alert Success -->
            @if(session('success'))
            <div class="mb-5 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @forelse($diseases as $disease)
                    <!-- Accordion Item -->
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg mb-4 last:mb-0 overflow-hidden">
                        
                        <!-- Accordion Header (Clickable) -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700">
                            <!-- Left: Disease Info (Clickable untuk expand) -->
                            <button type="button" 
                                    class="accordion-header flex items-center gap-x-3 flex-1 hover:bg-gray-100 dark:hover:bg-gray-600 transition -m-4 p-4 rounded-l-lg"
                                    onclick="toggleAccordion(this)">
                                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                </svg>
                                <div class="text-left flex-1">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                        {{ $disease->disease_name }}
                                    </h3>
                                </div>
                                
                                <!-- Chevron Icon -->
                                <svg class="chevron w-5 h-5 text-gray-500 dark:text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <!-- Right: Action Buttons -->
                            <div class="flex items-center gap-2 ml-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-200">
                                    {{ $disease->symptoms->count() }} Gejala
                                </span>
                                
                                <!-- Edit Button (PINDAH KE SINI) -->
                                <a href="{{ route('admin.symptom-diseases.edit', $disease->id) }}" 
                                   class="p-2 rounded-full text-white bg-indigo-600 hover:bg-indigo-700 transition" 
                                   title="Edit Gejala Penyakit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <!-- Accordion Content (Hidden by default) -->
                        <div class="accordion-content hidden">
                            @if($disease->symptoms->count() > 0)
                                <div class="p-4 bg-white dark:bg-gray-800">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                        @foreach($disease->symptoms as $symptom)
                                            <div class="flex items-center gap-x-3 p-3 border border-gray-200 dark:border-gray-700 rounded-lg">
                                                <!-- Type Icon -->
                                                <div class="flex-shrink-0">
                                                    @if($symptom->pivot->is_critical)
                                                        <div class="w-8 h-8 flex items-center justify-center rounded-full bg-red-100 dark:bg-red-900">
                                                            <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                            </svg>
                                                        </div>
                                                    @else
                                                        <div class="w-8 h-8 flex items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900">
                                                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Symptom Name & Badge -->
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                        {{ $symptom->symptom_name }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $symptom->pivot->is_critical ? 'Kritis (3x)' : 'Umum (1x)' }}
                                                    </p>
                                                </div>

                                                <!-- Actions -->
                                                <div class="flex items-center gap-x-2 ml-3 flex-shrink-0">
                                                    <form method="POST" 
                                                          action="{{ route('admin.symptom-diseases.destroy', $symptom->pivot->id) }}" 
                                                          class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="p-2 rounded-full text-white bg-red-600 hover:bg-red-700 transition" 
                                                                title="Delete">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="p-4 bg-gray-50 dark:bg-gray-700">
                                    <p class="text-sm text-gray-500 dark:text-gray-400 italic text-center">
                                        Belum ada gejala yang terkait dengan penyakit ini
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="mt-4 text-lg text-slate-500 dark:text-slate-400">
                            Belum ada relasi gejala-penyakit
                        </p>
                        <a href="{{ route('admin.symptom-diseases.create') }}" 
                           class="mt-4 inline-block font-bold py-3 px-5 rounded-full text-white bg-indigo-700 hover:bg-indigo-800 transition">
                            Tambah Relasi Pertama
                        </a>
                    </div>
                @endforelse

            </div>
        </div>
    </div>

    <!-- JavaScript for Accordion -->
    <script>
        function toggleAccordion(button) {
            const accordionItem = button.closest('.border');
            const content = accordionItem.querySelector('.accordion-content');
            const chevron = button.querySelector('.chevron');
            
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                chevron.style.transform = 'rotate(180deg)';
            } else {
                content.classList.add('hidden');
                chevron.style.transform = 'rotate(0deg)';
            }
        }
    </script>
</x-app-layout>