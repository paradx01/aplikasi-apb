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

        /* Badge styling */
        .badge-critical {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .8;
            }
        }
    </style>
    @include('partials.pwa')
</head>
<body>
    
    <!-- Topbar -->
    <section  id="topbar" class="fixed top-0 z-20 w-full transition duration-300">
        <div class="flex items-center justify-between gap-2 wrapper">
            <button onclick="smartBack('{{route('frontend.expertsystem.gejalaUmum')}}')" class="p-2 bg-white rounded-full shadow-sm">
                <img src="{{asset('assets/svgs/ic-arrow-left.svg')}}" class="size-5" alt="Back">
            </button>
            <p id="topbar-title" class="absolute text-base font-semibold top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 transition duration-100 text-gray-800">
                Pilih Gejala (Kritis)
            </p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="wrapper mt-6 pb-32">
        
        <!-- Breadcrumb Progress -->
        <div class="mb-6 bg-white rounded-2xl p-4 shadow-sm">
            <div class="flex items-center justify-between">
                
                <!-- Step 1: Completed -->
                <div class="flex flex-col items-center flex-1">
                    <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-primary">Umum</span>
                </div>

                <div class="flex-1 h-0.5 bg-primary mx-2"></div>

                <!-- Step 2: Active -->
                <div class="flex flex-col items-center flex-1">
                    <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center mb-2 ring-4 ring-primary/20">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-primary">Kritis</span>
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
        <div class="mb-4">
            <div class="bg-red-100 border-l-4 border-red-500 rounded-lg p-3">
                <div class="flex gap-2">
                    <svg class="w-4 h-4 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-xs text-red-800 leading-relaxed">
                        <span class="font-semibold">Gejala Kritis:</span> Pilih gejala khas yang lebih spesifik untuk diagnosis lebih akurat. Boleh dilewati jika tidak ada yang sesuai.
                    </p>
                </div>
            </div>
        </div>

        <!-- Summary Gejala Umum yang Dipilih -->
        <div class="mb-4 bg-white rounded-2xl p-4 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Gejala Umum Terpilih ({{ $selectedSymptoms->count() }})
            </h3>
            <div class="flex flex-wrap gap-2">
                @foreach($selectedSymptoms as $symptom)
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-primary/10 text-primary border border-primary/20">
                        {{ $symptom->symptom_name }}
                    </span>
                @endforeach
            </div>
        </div>

        <!-- Selected Counter -->
        <div class="flex items-center justify-between mb-4 bg-red-50 border border-red-200 rounded-2xl p-4 border border-red-100">
            <span class="text-sm font-medium text-gray-700">Gejala Kritis Terpilih:</span>
            <span class="text-lg font-bold text-red-600" id="selected-count">0</span>
        </div>

        <!-- Form -->
        <form action="{{ route('frontend.expertsystem.diagnosa') }}" method="GET" id="symptom-form">
            
            <!-- Hidden inputs untuk gejala umum -->
            @foreach($selectedGeneralSymptoms as $symptomId)
                <input type="hidden" name="general_symptoms[]" value="{{ $symptomId }}">
            @endforeach

            <!-- Symptoms Grid -->
            @if($gejalaKritis->count() > 0)
                <div class="space-y-3 mb-6" id="symptoms-grid">
                    @foreach($gejalaKritis as $gejala)
                        <label class="symptom-card block cursor-pointer" data-symptom-name="{{ strtolower($gejala->symptom_name) }}">
                            <input type="checkbox" 
                                   name="specific_symptoms[]" 
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
                                    <div class="flex items-start justify-between gap-2">
                                        <h4 class="font-semibold text-gray-900 text-sm mb-1 flex-1">
                                            {{ $gejala->symptom_name }}
                                        </h4>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-700 flex-shrink-0 badge-critical">
                                            KHAS
                                        </span>
                                    </div>
                                    @if($gejala->description)
                                        <p class="text-xs text-gray-500 line-clamp-2 mb-1">
                                            {{ $gejala->description }}
                                        </p>
                                    @endif
                                    <span class="inline-block text-xs font-medium text-gray-400 capitalize">
                                        {{ $gejala->type }}
                                    </span>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-2xl p-8 text-center shadow-sm">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 mb-2">Tidak Ada Gejala Kritis</h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Berdasarkan gejala umum yang Anda pilih, tidak ditemukan gejala kritis yang relevan.
                    </p>
                    <p class="text-xs text-gray-400">
                        Anda dapat langsung melanjutkan ke diagnosis.
                    </p>
                </div>
            @endif

        </form>

    </div>

    <!-- Floating Action Buttons -->
    <div class="fixed z-50 bottom-[30px] left-1/2 -translate-x-1/2 w-[calc(100dvw-32px)] max-w-[425px]">
        <div class="flex gap-3">
            <!-- Skip Button (if no critical symptoms or user wants to skip) -->
            @if($gejalaKritis->count() > 0)
                <button type="button"
                        onclick="skipCriticalSymptoms()"
                        class="flex-shrink-0 bg-white border-2 border-gray-300 text-gray-700 rounded-full py-4 px-6 font-semibold text-sm shadow-lg 
                               hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
                    <span>Lewati</span>
                </button>
            @endif

            <!-- Submit Button -->
            <button type="submit" 
                    form="symptom-form"
                    id="submit-btn"
                    class="flex-1 bg-primary text-white rounded-full py-4 font-bold text-base shadow-lg 
                           hover:bg-opacity-90 transition-all flex items-center justify-center gap-2">
                <span>Lihat Diagnosa</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Scripts -->
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

        // Update selected count
        function updateSelectedCount() {
            const checked = document.querySelectorAll('.symptom-checkbox:checked').length;
            document.getElementById('selected-count').textContent = checked;
        }

        // Skip critical symptoms (submit form without selecting any)
        function skipCriticalSymptoms() {
            document.getElementById('symptom-form').submit();
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

        // Save state sebelum submit
        document.getElementById('symptom-form').addEventListener('submit', function(e) {
            saveFormState();
        });

        // Skip function juga save state
        function skipCriticalSymptoms() {
            saveFormState();
            document.getElementById('symptom-form').submit();
        }

        // Restore state saat page load
        document.addEventListener('DOMContentLoaded', function() {
            restoreFormState();
            updateSelectedCount();
        });
    </script>
</body>
</html>
