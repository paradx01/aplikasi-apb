<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lengkapi Profil | Parma</title>
  <link rel="shortcut icon" href="{{ asset('assets/svgs/logo-mark.svg') }}" type="image/x-icon">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="{{ asset('css/main.css') }}">
  <style>
    html, body { 
        width: 100%; 
        overflow-x: hidden !important;
        scroll-behavior: smooth;
    }
    .wrapper { 
        max-width: 425px; 
        margin-left: auto; 
        margin-right: auto; 
        padding-left: 1.5rem; 
        padding-right: 1.5rem;
    }
  </style>
  @include('partials.pwa')
</head>
<body>

  <section class="wrapper py-8 pb-32">
    
    <!-- Welcome Header -->
    <div class="text-center mb-6">
      <img src="{{ asset('assets/svgs/logo.svg') }}" class="h-10 mx-auto mb-4" alt="Logo">
      <h1 class="text-xl font-bold text-gray-900 mb-2">Selamat Datang, {{ $user->name }}! 👋</h1>
      <p class="text-sm text-gray-600 leading-relaxed">
        Sebelum mulai berbelanja, lengkapi data diri Anda agar kami dapat memberikan 
        <strong>rekomendasi obat yang aman</strong> sesuai kondisi kesehatan Anda.
      </p>
    </div>

    <!-- Info Box -->
    <div class="flex gap-2 items-start bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 mb-6">
      <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
      </svg>
      <p class="text-xs text-blue-800">
        Data ini digunakan oleh <strong>sistem pakar</strong> untuk memfilter obat yang kontraindikasi dengan kondisi Anda. 
        Data Anda <strong>tersimpan aman</strong> dan bisa diubah kapan saja di menu Profil.
      </p>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
      <div class="bg-red-50 border border-red-200 rounded-xl p-3 mb-6">
        <ul class="text-xs text-red-700 space-y-1">
          @foreach($errors->all() as $error)
            <li class="flex items-start gap-1.5">
              <svg class="w-3.5 h-3.5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
              </svg>
              <span>{{ $error }}</span>
            </li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('profile.complete.store') }}" method="POST" class="flex flex-col gap-6">
      @csrf

      <!-- Section 1: Data Dasar -->
      <div class="p-5 bg-white rounded-2xl shadow-sm">
        <div class="text-base font-bold mb-4 text-gray-900">📋 Data Pribadi</div>
        <div class="flex flex-col gap-4">
          <div>
            <label for="age" class="block text-sm font-semibold mb-2">Usia <span class="text-red-500">*</span></label>
            <input type="number" name="age" id="age" value="{{ old('age') }}" min="1" max="150"
              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary outline-none text-base"
              placeholder="Masukkan usia Anda" required>
          </div>
          <div>
            <label for="gender" class="block text-sm font-semibold mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
            <select name="gender" id="gender"
              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary outline-none text-base appearance-none" required>
              <option value="">Pilih Jenis Kelamin</option>
              <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
              <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Section 2: Status Kehamilan (hanya perempuan) -->
      <div id="pregnancy-section" class="p-5 bg-white rounded-2xl shadow-sm" style="display: none;">
        <div class="flex gap-2 items-start bg-pink-50 border border-pink-200 rounded-xl px-4 py-3">
          <div class="flex-1">
            <p class="text-sm font-semibold text-pink-800">Apakah Anda sedang hamil?</p>
            <p class="text-xs text-pink-600 mt-0.5">Penting untuk filter obat yang aman untuk ibu hamil.</p>
          </div>
          <div class="flex gap-3 items-center">
            <label class="inline-flex items-center cursor-pointer">
              <input type="radio" name="is_pregnant" value="1" {{ old('is_pregnant') == '1' ? 'checked' : '' }}
                class="w-4 h-4 text-pink-600 border-gray-300">
              <span class="ml-1.5 text-xs font-medium text-pink-700">Ya</span>
            </label>
            <label class="inline-flex items-center cursor-pointer">
              <input type="radio" name="is_pregnant" value="0" {{ old('is_pregnant', '0') == '0' ? 'checked' : '' }}
                class="w-4 h-4 text-gray-600 border-gray-300">
              <span class="ml-1.5 text-xs font-medium text-gray-600">Tidak</span>
            </label>
          </div>
        </div>
      </div>

      <!-- Section 3: Riwayat Penyakit -->
      <div class="p-5 bg-white rounded-2xl shadow-sm">
        <div class="text-base font-bold mb-2 text-gray-900">🏥 Riwayat Penyakit</div>
        <p class="text-xs text-gray-500 mb-4">Pilih kondisi yang pernah atau sedang Anda alami. Biarkan "Tidak" jika tidak ada.</p>
        
        <div class="flex flex-col gap-3">
          @php
            $conditions = [
              ['name' => 'has_hypertension', 'label' => 'Hipertensi (tekanan darah tinggi)'],
              ['name' => 'has_heart_disorder', 'label' => 'Gangguan Jantung'],
              ['name' => 'has_diabetes', 'label' => 'Diabetes (kencing manis)'],
              ['name' => 'has_kidney_disorder', 'label' => 'Gangguan Ginjal'],
              ['name' => 'has_stomach_ulcer', 'label' => 'Maag / Tukak Lambung'],
              ['name' => 'has_liver_disorder', 'label' => 'Gangguan Hati (Hepatitis, Sirosis)'],
              ['name' => 'has_asthma', 'label' => 'Asma'],
              ['name' => 'has_glaucoma', 'label' => 'Glaukoma'],
              ['name' => 'has_prostate_disorder', 'label' => 'Gangguan Prostat'],
              ['name' => 'has_hyperthyroidism', 'label' => 'Hipertiroidisme'],
              ['name' => 'has_g6pd_deficiency', 'label' => 'Defisiensi G6PD'],
            ];
          @endphp

          @foreach($conditions as $c)
            <div class="flex items-center justify-between bg-gray-50 rounded-xl px-4 py-2.5">
              <span class="text-xs font-medium text-gray-700 flex-1 pr-2">{{ $c['label'] }}</span>
              <div class="flex gap-2 shrink-0">
                <label class="inline-flex items-center cursor-pointer">
                  <input type="radio" name="{{ $c['name'] }}" value="1" {{ old($c['name']) == '1' ? 'checked' : '' }}
                    class="w-4 h-4 text-primary border-gray-300">
                  <span class="ml-1 text-xs text-green-600">Ya</span>
                </label>
                <label class="inline-flex items-center cursor-pointer">
                  <input type="radio" name="{{ $c['name'] }}" value="0" {{ old($c['name'], '0') == '0' ? 'checked' : '' }}
                    class="w-4 h-4 text-primary border-gray-300">
                  <span class="ml-1 text-xs text-gray-500">Tidak</span>
                </label>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <!-- Section 4: Alergi Obat -->
      <div class="p-5 bg-white rounded-2xl shadow-sm">
        <div class="text-base font-bold mb-2 text-gray-900">💊 Alergi Obat</div>
        <p class="text-xs text-gray-500 mb-4">Pilih jenis obat yang pernah membuat Anda alergi.</p>
        
        <div class="flex flex-col gap-3">
          @php
            $allergies = [
              ['name' => 'has_allergy_paracetamol', 'label' => 'Paracetamol (Sanmol, Panadol)'],
              ['name' => 'has_allergy_nsaid', 'label' => 'NSAID (Ibuprofen, Asam Mefenamat)'],
              ['name' => 'has_allergy_aspirin', 'label' => 'Aspirin'],
              ['name' => 'has_allergy_antihistamine', 'label' => 'Antihistamin (CTM, Cetirizine)'],
              ['name' => 'has_allergy_decongestant', 'label' => 'Dekongestan (Pseudoefedrin)'],
              ['name' => 'has_allergy_bromhexine', 'label' => 'Bromhexine (pengencer dahak)'],
              ['name' => 'has_allergy_guaifenesin', 'label' => 'Guaifenesin (ekspektoran)'],
              ['name' => 'has_allergy_antacid', 'label' => 'Antasida (Promag, Mylanta)'],
            ];
          @endphp

          @foreach($allergies as $a)
            <div class="flex items-center justify-between bg-gray-50 rounded-xl px-4 py-2.5">
              <span class="text-xs font-medium text-gray-700 flex-1 pr-2">{{ $a['label'] }}</span>
              <div class="flex gap-2 shrink-0">
                <label class="inline-flex items-center cursor-pointer">
                  <input type="radio" name="{{ $a['name'] }}" value="1" {{ old($a['name']) == '1' ? 'checked' : '' }}
                    class="w-4 h-4 text-primary border-gray-300">
                  <span class="ml-1 text-xs text-green-600">Ya</span>
                </label>
                <label class="inline-flex items-center cursor-pointer">
                  <input type="radio" name="{{ $a['name'] }}" value="0" {{ old($a['name'], '0') == '0' ? 'checked' : '' }}
                    class="w-4 h-4 text-primary border-gray-300">
                  <span class="ml-1 text-xs text-gray-500">Tidak</span>
                </label>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <!-- Submit Button -->
      <div class="fixed z-50 bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4">
        <div class="max-w-[425px] mx-auto">
          <button type="submit" class="w-full bg-primary text-white rounded-full py-4 font-bold text-base shadow-lg hover:bg-opacity-90 transition-all">
            Simpan & Mulai Belanja
          </button>
        </div>
      </div>

    </form>
  </section>

  <script>
    // Toggle pregnancy section based on gender
    const genderSelect = document.getElementById('gender');
    const pregnancySection = document.getElementById('pregnancy-section');
    
    function togglePregnancy() {
      pregnancySection.style.display = genderSelect.value === 'P' ? 'block' : 'none';
    }
    
    genderSelect.addEventListener('change', togglePregnancy);
    togglePregnancy(); // Run on load
  </script>

</body>
</html>
