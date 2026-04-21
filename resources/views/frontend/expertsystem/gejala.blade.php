<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekomendasi Obat | Parma</title>
    <script src="https://cdn.tailwindcss.com"></script>  
    <link rel="stylesheet" href="{{asset('css/main.css')}}">
    <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css'">
    <style>
        html, body { 
            width: 100%; 
            overflow-x: hidden !important; 
            padding-top: 2.5rem;
        }
        wrapper {
            max-width: 425px; 
            margin-left: auto;
            margin-right: auto;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }
        .scrolled {
          background-color: #ffffff; /* Warna solid saat scroll */
          box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06);
        }

        /* Custom Checkbox Style */
        .symptom-checkbox:checked + .symptom-card-content {
            border-color: var(--primary-color, #FF6B2C) !important;
            background-color: rgba(255, 107, 44, 0.05) !important;
        }
        
        /* Smooth transitions */
        .symptom-card {
            transition: all 0.2s ease-in-out;
        }
        
        .symptom-card:active {
            transform: scale(0.98);
        }
    </style>
    @include('partials.pwa')
</head>
<body>
    <!-- Topbar -->
    <section  id="topbar" class="fixed top-0 z-20 w-full transition duration-300">
          <div class="flex items-center justify-between gap-2 wrapper">
            <button onclick="smartBack('{{route('frontend.expertsystem.index')}}')" class="p-2 bg-white rounded-full shadow-sm">
                <img src="{{asset('assets/svgs/ic-arrow-left.svg')}}" class="size-5" alt="Back">
            </button>
            <p id="topbar-title" class="absolute text-base font-semibold top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 transition duration-100 text-gray-800">
                Pilih Gejala (Umum)
            </p>
          </div>
    </section>

    <!-- Main Content -->
    <div class="wrapper mt-6 pb-32">
        
        <!-- Breadcrumb Progress -->
        <div class="mb-6 bg-white rounded-2xl p-4 shadow-sm">
            <div class="flex items-center justify-between">
                
                <!-- Step 1: Active -->
                <div class="flex flex-col items-center flex-1">
                    <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-primary">Umum</span>
                </div>

                <div class="flex-1 h-0.5 bg-gray-200 mx-2"></div>

                <!-- Step 2: Inactive -->
                <div class="flex flex-col items-center flex-1">
                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-400">Kritis</span>
                </div>

                <div class="flex-1 h-0.5 bg-gray-200 mx-2"></div>

                <!-- Step 3: Inactive -->
                <div class="flex flex-col items-center flex-1">
                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-400">Diagnosa</span>
                </div>

                <div class="flex-1 h-0.5 bg-gray-200 mx-2"></div>

                <!-- Step 4: Inactive -->
                <div class="flex flex-col items-center flex-1">
                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-400">Obat</span>
                </div>

            </div>
        </div>

        <!-- Info Card -->
        <div class="mb-6">
            <div class="bg-blue-50 border-l-4 border-primary rounded-lg p-4">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-xs text-gray-700">
                        <span class="font-semibold">Gejala Umum:</span> Pilih gejala yang Anda rasakan seperti demam, pusing, atau mual. Minimal 1 gejala diperlukan.
                    </p>
                </div>
            </div>
        </div>


        <!-- Selected Counter -->
        <div class="flex items-center justify-between mb-4 bg-white rounded-2xl p-4">
            <span class="text-sm font-medium text-gray-700">Gejala Terpilih:</span>
            <span class="text-lg font-bold text-primary" id="selected-count">0</span>
        </div>

        <!-- Form -->
        <form action="{{ route('frontend.expertsystem.gejalaKritis') }}" method="GET" id="symptom-form">
            
            <!-- Symptoms Grid -->
            <div class="space-y-3 mb-6" id="symptoms-grid">
                @forelse($gejalaList as $gejala)
                    <label class="symptom-card block cursor-pointer" data-symptom-name="{{ strtolower($gejala->symptom_name) }}">
                        <input type="checkbox" 
                               name="symptoms[]" 
                               value="{{ $gejala->id }}"
                               class="symptom-checkbox hidden"
                               onchange="toggleSymptom(this)">
                        
                        <div class="symptom-card-content bg-white border-2 border-gray-200 rounded-2xl p-4 flex items-center gap-3">
                            <!-- Checkbox Visual -->
                            <div class="checkbox-visual w-6 h-6 border-2 border-gray-300 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white hidden" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>

                            <!-- Symptom Info -->
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-gray-900 text-sm mb-1">
                                    {{ $gejala->symptom_name }}
                                </h4>
                                @if($gejala->description)
                                    <p class="text-xs text-gray-500 line-clamp-1">
                                        {{ $gejala->description }}
                                    </p>
                                @endif
                                <span class="inline-block mt-1 text-xs font-medium text-gray-400 capitalize">
                                    {{ $gejala->type }}
                                </span>
                            </div>
                        </div>
                    </label>
                @empty
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada gejala</h3>
                        <p class="mt-1 text-sm text-gray-500">Data gejala belum tersedia di sistem.</p>
                    </div>
                @endforelse
            </div>
        </form>
    </div>

    <!-- Floating Action Button -->
    <div class="fixed z-50 bottom-[30px] left-1/2 -translate-x-1/2 w-[calc(100dvw-32px)] max-w-[425px]">
        <button type="submit" 
                form="symptom-form"
                id="submit-btn"
                class="w-full bg-primary text-white rounded-full py-4 font-bold text-base shadow-lg 
                       hover:bg-opacity-90 disabled:bg-gray-300 disabled:cursor-not-allowed disabled:shadow-none
                       transition-all flex items-center justify-center gap-2"
                disabled>
            <span>Lanjut ke Gejala Kritis</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
        </button>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="wrapper mt-4">
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-2xl">
            <p class="text-sm font-semibold">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
      integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="{{asset('scripts/global.js')}}"></script>
    <script src="{{asset('scripts/fixedTopbar.js')}}"></script>
    <script src="{{asset('scripts/smartBack.js')}}"></script>
    <script>
        // Toggle symptom selection with Primary color
        function toggleSymptom(checkbox) {
            const card = checkbox.closest('.symptom-card');
            const cardContent = card.querySelector('.symptom-card-content');
            const checkboxVisual = card.querySelector('.checkbox-visual');
            const checkIcon = checkboxVisual.querySelector('svg');
            
            if (checkbox.checked) {
                // Selected state with PRIMARY color
                cardContent.classList.remove('border-gray-200', 'bg-white');
                cardContent.classList.add('border-primary', 'bg-primary/5');
                checkboxVisual.classList.remove('border-gray-300');
                checkboxVisual.classList.add('border-primary', 'bg-primary');
                checkIcon.classList.remove('hidden');
            } else {
                // Unselected state
                cardContent.classList.add('border-gray-200', 'bg-white');
                cardContent.classList.remove('border-primary', 'bg-primary/5');
                checkboxVisual.classList.add('border-gray-300');
                checkboxVisual.classList.remove('border-primary', 'bg-primary');
                checkIcon.classList.add('hidden');
            }
            
            updateSelectedCount();
        }

        // Update selected count and button state
        function updateSelectedCount() {
            const checked = document.querySelectorAll('.symptom-checkbox:checked').length;
            document.getElementById('selected-count').textContent = checked;
            
            const submitBtn = document.getElementById('submit-btn');
            if (checked > 0) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
        }

        // Search functionality
        document.getElementById('search-symptom').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.symptom-card');
            
            cards.forEach(card => {
                const symptomName = card.getAttribute('data-symptom-name');
                if (symptomName.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateSelectedCount();
        });

        // Override form submit untuk save state sebelum pindah
        document.getElementById('symptom-form').addEventListener('submit', function(e) {
            // Jangan clear state, biar bisa back dengan state utuh
            saveFormState();
        });

        // Restore state saat page load
        document.addEventListener('DOMContentLoaded', function() {
            restoreFormState();
            updateSelectedCount();
        });
    </script>

</body>
</html>