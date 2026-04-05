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
            padding-top: 2rem;
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
    </style>
</head>
<body>
    <!-- Topbar -->
      <section  id="topbar" class="fixed top-0 z-20 w-full transition duration-300">
          <div class="flex items-center justify-between gap-2 wrapper">
            <a href="{{route('frontend.index')}}" class="p-2 bg-white rounded-full">
              <img src="{{asset('assets/svgs/ic-arrow-left.svg')}}" class="size-5" alt="">
            </a>
            <p id="topbar-title" class="absolute text-base font-semibold top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 transition duration-100 text-gray-800">
                Rekomendasi Obat
            </p>
          </div>
      </section>


    <!-- Success Message -->
    @if(session('success'))
    <div class="wrapper mt-4">
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-2xl">
            <p class="text-sm font-semibold">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <section class="wrapper">
        <!-- Content -->
        <div class="container mx-auto px-4 py-8 max-w-md">
            <!-- Illustration Card -->
            <div>
                <img src="{{ asset('assets/images/splashscreen.png') }}" 
                    alt="Doctor Illustration" 
                    class="w-full h-auto rounded-2xl mb-8">
            </div>

            <!-- Title -->
            <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">
                Solusi medikasi anda!
            </h2>

            <!-- Description -->
            <p class="text-gray-600 text-center mb-8 px-4">
                Dapatkan rekomendasi obat berdasarkan penyakit yang anda alami secara instant.
            </p>

            <!-- CTA Button -->
            <div class="px-4">
                <a href="{{ route('frontend.expertsystem.gejalaUmum') }}" 
                onclick="event.preventDefault(); clearAndStart();"
                class="block w-full bg-primary text-white font-semibold text-center py-4 rounded-full">
                    Mulai Rekomendasi
                </a>
            </div>

            <!-- Disclaimer -->
            <div class="mt-6 px-4">
                <div class="bg-yellow-50 border-l-4 border-yellow-400 rounded-lg p-4">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-xs text-yellow-800">
                            <span class="font-semibold">Perhatian:</span> Sistem ini untuk keluhan ringan. Kondisi serius segera konsultasi ke dokter.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
      integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="{{asset('scripts/global.js')}}"></script>
    <script src="{{asset('scripts/fixedTopbar.js')}}"></script>
    <script>
        // Clear state dan mulai fresh
        function clearAndStart() {
            // Clear visited pages
            sessionStorage.removeItem('visitedPages');
            
            // Clear all form states
            const keys = Object.keys(sessionStorage);
            keys.forEach(key => {
                if (key.startsWith('formState_')) {
                    sessionStorage.removeItem(key);
                }
            });
            
            // Redirect ke gejala umum
            window.location.href = "{{ route('frontend.expertsystem.gejalaUmum') }}";
        }
    </script>
</body>
</html>