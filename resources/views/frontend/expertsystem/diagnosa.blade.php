<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekomendasi Obat | Parma</title>
    <script src="https://cdn.tailwindcss.com"></script>  
    <link rel="stylesheet" href="{{asset('css/main.css')}}">
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
        
        /* Confidence bar animation */
        .confidence-bar {
            transition: width 1s ease-out;
        }

        /* Disease card hover effect */
        .disease-card {
            transition: all 0.2s ease-in-out;
        }

        .disease-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        /* Radio button custom style */
        .disease-radio:checked + .disease-card-content {
            border-color: var(--primary-color, #FF6B2C);
            background-color: rgba(255, 107, 44, 0.05);
        }
    </style>
    @include('partials.pwa')
</head>
<body>
    
    <!-- Topbar -->
    <section  id="topbar" class="fixed top-0 z-20 w-full transition duration-300">
          <div class="flex items-center justify-between gap-2 wrapper">
            <button onclick="smartBack('{{route('frontend.expertsystem.gejalaKritis', ['symptoms' => request()->input('general_symptoms', [])])}}')" class="p-2 bg-white rounded-full shadow-sm">
                <img src="{{asset('assets/svgs/ic-arrow-left.svg')}}" class="size-5" alt="Back">
            </button>
            <p id="topbar-title" class="absolute text-base font-semibold top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 transition duration-100 text-gray-800">
                Hasil Diagnosa
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

                <!-- Step 2: Completed -->
                <div class="flex flex-col items-center flex-1">
                    <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-primary">Kritis</span>
                </div>

                <div class="flex-1 h-0.5 bg-primary mx-2"></div>

                <!-- Step 3: Active -->
                <div class="flex flex-col items-center flex-1">
                    <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center mb-2 ring-4 ring-primary/20">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-primary">Diagnosa</span>
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

        <!-- Warning jika tidak ada critical match -->
        @php
            $hasCriticalMatch = $diseases->where('matched_critical', '>', 0)->count() > 0;
        @endphp

        @if(!$hasCriticalMatch && $diseases->count() > 0)
        <div class="mb-6">
            <div class="bg-yellow-50 border-l-4 border-yellow-400 rounded-lg p-3">
                <div class="flex gap-2">
                    <svg class="w-4 h-4 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        <p class="text-xs text-yellow-800 font-semibold mb-1">
                            Diagnosis Berdasarkan Gejala Umum
                        </p>
                        <p class="text-xs text-yellow-700 leading-relaxed">
                            Tidak ada gejala khas teridentifikasi. Diagnosis ini bersifat <strong>indikasi awal</strong>. 
                            Konsultasi dengan dokter sangat disarankan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Summary Gejala Terpilih -->
        <div class="mb-6 bg-white rounded-2xl p-4 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Gejala yang Anda Alami ({{ $allSymptoms->count() }})
            </h3>
            <div class="flex flex-wrap gap-2">
                @foreach($allSymptoms as $symptom)
                    @php
                        // Cek apakah gejala ini kritis
                        $isCritical = in_array($symptom->id, request()->input('specific_symptoms', []));
                    @endphp
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium 
                                {{ $isCritical ? 'bg-red-100 text-red-700 border border-red-200' : 'bg-gray-100 text-gray-700 border border-gray-200' }}">
                        @if($isCritical)
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                        {{ $symptom->symptom_name }}
                    </span>
                @endforeach
            </div>
        </div>

        <!-- Info Header -->
        <div class="mb-4">
            <h2 class="text-lg font-bold text-gray-800 mb-1">Kemungkinan Penyakit</h2>
            <p class="text-sm text-gray-600">Pilih salah satu untuk melihat rekomendasi obat</p>
        </div>

        <!-- Form untuk pilih penyakit -->
        <form action="{{ route('frontend.expertsystem.rekomendasi') }}" method="GET" id="disease-form">
            
            <!-- Pass confidence & has_critical untuk warning di rekomendasi -->
            <input type="hidden" name="has_critical" value="{{ $hasCriticalMatch ? '1' : '0' }}">

            <!-- Disease Cards -->
            @forelse($diseases as $index => $diseaseData)
                <label class="disease-card block cursor-pointer mb-4">
                    <input type="radio" 
                           name="disease_id" 
                           value="{{ $diseaseData['disease']->id }}"
                           data-confidence="{{ $diseaseData['confidence'] }}"
                           class="disease-radio hidden"
                           onchange="selectDisease(this)"
                           {{ $index === 0 ? 'checked' : '' }}>
                    
                    <div class="disease-card-content bg-white border-2 border-gray-200 rounded-2xl p-4 shadow-sm">
                        
                        <!-- Header: Disease Name & Confidence -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="text-base font-bold text-gray-900 mb-1 flex items-center gap-2">
                                    {{ $diseaseData['disease']->disease_name }}
                                    
                                    <!-- Badge jika ada critical match -->
                                    @if($diseaseData['matched_critical'] > 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-700">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $diseaseData['matched_critical'] }} KHAS
                                        </span>
                                    @endif
                                </h3>
                                
                                @if($diseaseData['disease']->description)
                                    <p class="text-xs text-gray-500 line-clamp-2">
                                        {{ $diseaseData['disease']->description }}
                                    </p>
                                @endif
                            </div>

                            <!-- Confidence Badge -->
                            <div class="flex-shrink-0 ml-3">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-primary">
                                        {{ $diseaseData['confidence'] }}%
                                    </div>
                                    <div class="text-xs text-gray-500">Kesesuaian</div>
                                </div>
                            </div>
                        </div>

                        <!-- Confidence Bar -->
                        <div class="mb-3">
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="confidence-bar h-full rounded-full transition-all duration-1000
                                            {{ $diseaseData['confidence'] >= 70 ? 'bg-green-500' : 
                                               ($diseaseData['confidence'] >= 50 ? 'bg-yellow-500' : 'bg-orange-500') }}"
                                     style="width: {{ $diseaseData['confidence'] }}%">
                                </div>
                            </div>
                        </div>

                        <!-- Match Details -->
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div class="bg-gray-50 rounded-lg p-2">
                                <div class="text-xs text-gray-500 mb-1">Gejala Umum</div>
                                <div class="text-sm font-semibold text-gray-700">
                                    {{ $diseaseData['matched_symptoms'] - $diseaseData['matched_critical'] }}/{{ $diseaseData['total_symptoms'] - $diseaseData['total_critical'] }}
                                </div>
                            </div>
                            
                            <div class="bg-red-50 rounded-lg p-2">
                                <div class="text-xs text-red-600 mb-1">Gejala Khas</div>
                                <div class="text-sm font-semibold text-red-700">
                                    {{ $diseaseData['matched_critical'] }}/{{ $diseaseData['total_critical'] }}
                                </div>
                            </div>
                        </div>

                        <!-- Interpretasi -->
                        <div class="p-3 rounded-lg
                                    {{ $diseaseData['matched_critical'] >= 2 ? 'bg-red-50 border border-red-100' : 
                                       ($diseaseData['matched_critical'] == 1 ? 'bg-yellow-50 border border-yellow-100' : 'bg-gray-50 border border-gray-100') }}">
                            @if($diseaseData['matched_critical'] >= 2)
                                <p class="text-xs text-red-800">
                                    <strong>Diagnosis Kuat:</strong> Beberapa gejala khas teridentifikasi. Sangat disarankan konsultasi dokter.
                                </p>
                            @elseif($diseaseData['matched_critical'] == 1)
                                <p class="text-xs text-yellow-800">
                                    <strong>Kemungkinan Sedang:</strong> Satu gejala khas teridentifikasi. Pertimbangkan pemantauan lebih lanjut.
                                </p>
                            @else
                                <p class="text-xs text-gray-600">
                                    <strong>Kemungkinan Rendah:</strong> Hanya gejala umum yang cocok. Diagnosis bersifat indikasi awal.
                                </p>
                            @endif
                        </div>

                    </div>
                </label>
            @empty
                <!-- Empty State -->
                <div class="bg-white rounded-2xl p-8 text-center shadow-sm">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 mb-2">Tidak Dapat Mendiagnosa</h3>
                    <p class="text-sm text-gray-600 mb-6 leading-relaxed">
                        Gejala yang Anda alami tidak cukup spesifik untuk memberikan diagnosis yang akurat. 
                        Silakan coba lagi dengan memilih gejala yang lebih detail atau konsultasi langsung dengan dokter.
                    </p>
                    <a href="{{ route('frontend.expertsystem.gejalaUmum') }}" 
                       class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-full font-semibold text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Coba Lagi
                    </a>
                </div>
            @endforelse

        </form>

    </div>

    <!-- Floating Action Button -->
    @if($diseases->count() > 0)
    <div class="fixed z-50 bottom-[30px] left-1/2 -translate-x-1/2 w-[calc(100dvw-32px)] max-w-[425px]">
        <button type="submit" 
                form="disease-form"
                id="submit-btn"
                class="w-full bg-primary text-white rounded-full py-4 font-bold text-base shadow-lg 
                       hover:bg-opacity-90 transition-all flex items-center justify-center gap-2">
            <span>Lihat Rekomendasi Obat</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
        </button>
    </div>
    @endif

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="{{asset('scripts/global.js')}}"></script>
    <script src="{{asset('scripts/fixedTopbar.js')}}"></script>
    <script src="{{asset('scripts/smartBack.js')}}"></script>
    <script>
        // Select disease and update hidden input
        function selectDisease(radio) {
            const cards = document.querySelectorAll('.disease-card-content');
            cards.forEach(card => {
                card.classList.remove('border-primary', 'bg-primary/5');
                card.classList.add('border-gray-200');
            });

            const selectedCard = radio.nextElementSibling;
            selectedCard.classList.remove('border-gray-200');
            selectedCard.classList.add('border-primary', 'bg-primary/5');

            // Update confidence hidden input
            const confidence = radio.dataset.confidence;
            let confidenceInput = document.querySelector('input[name="confidence"]');
            if (!confidenceInput) {
                confidenceInput = document.createElement('input');
                confidenceInput.type = 'hidden';
                confidenceInput.name = 'confidence';
                document.getElementById('disease-form').appendChild(confidenceInput);
            }
            confidenceInput.value = confidence;
        }

        // Initialize first selection
        document.addEventListener('DOMContentLoaded', function() {
            const firstRadio = document.querySelector('.disease-radio:checked');
            if (firstRadio) {
                selectDisease(firstRadio);
            }

            // Animate confidence bars
            setTimeout(() => {
                document.querySelectorAll('.confidence-bar').forEach(bar => {
                    bar.style.width = bar.style.width; // Trigger animation
                });
            }, 100);
        });

        // Save state sebelum submit
        document.getElementById('disease-form').addEventListener('submit', function(e) {
            saveFormState();
        });

        // Restore state saat page load
        document.addEventListener('DOMContentLoaded', function() {
            restoreFormState();
            
            const firstRadio = document.querySelector('.disease-radio:checked');
            if (firstRadio) {
                selectDisease(firstRadio);
            }

            // Animate confidence bars
            setTimeout(() => {
                document.querySelectorAll('.confidence-bar').forEach(bar => {
                    bar.style.width = bar.style.width;
                });
            }, 100);
        });
    </script>
</body>
</html>
