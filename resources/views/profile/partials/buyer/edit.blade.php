<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profile | Parma</title>
  <link rel="shortcut icon" href="{{ asset('assets/svgs/logo-mark.svg') }}" type="image/x-icon">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="{{ asset('css/main.css') }}">
  <style>
    html, body { 
        width: 100%; 
        overflow-x: hidden !important;
        padding-top: 2rem; 
        scroll-behavior: smooth;
    }
    .wrapper { 
        max-width: 425px; 
        margin-left: auto; 
        margin-right: auto; 
        padding-left: 1.5rem; 
        padding-right: 1.5rem;
    }
    .scrolled { 
        background-color: #ffffff; 
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -2px rgba(0,0,0,0.06);
    }
  </style>
    @include('partials.pwa')
</head>
<body>
    <!-- Topbar -->
    <section id="topbar" class="fixed top-0 z-20 w-full transition duration-300">
        <div class="flex items-center justify-between gap-2 wrapper">
            <a href="{{route('profile.index')}}" class="p-2 bg-white rounded-full">
                <img src="{{asset('assets/svgs/ic-arrow-left.svg')}}" class="size-5" alt="">
            </a>
        <p id="topbar-title" class="absolute text-base font-semibold top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 transition duration-100 text-gray-800">
            Edit Profile
        </p>
        </div>
    </section>

    <!-- Flash/Error -->
    @if($errors->any())
    <div class="wrapper mt-4">
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-2xl">
            <p class="text-sm font-semibold mb-2">Please fix the following errors:</p>
            <ul class="text-sm list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
    </div>
    @endif

    <section class="wrapper pb-32">
        <form action="{{ route('profile.update.buyer') }}" method="POST" class="flex flex-col gap-8">
            @csrf
            @method('PUT')

            <!-- Section 1: Personal Information -->
            <div class="p-6 bg-white rounded-3xl">
            <div class="text-lg font-semibold mb-4 text-indigo-950">Personal Information</div>
            <div class="flex flex-col gap-4">
                <div>
                <label for="name" class="block text-sm font-semibold mb-2">Nama Lengkap *</label>
                <input type="text" name="name" id="name"
                    value="{{ old('name', $user->name) }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary outline-none text-base"
                    placeholder="Full Name" required>
                </div>
                <div>
                <label for="age" class="block text-sm font-semibold mb-2">Usia *</label>
                <input type="number" name="age" id="age"
                    value="{{ old('age', $user->age) }}"
                    min="0"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary outline-none text-base"
                    placeholder="e.g., 25" required>
                </div>
                <div>
                <label for="gender" class="block text-sm font-semibold mb-2">Jenis Kelamin *</label>
                <select name="gender" id="gender"
                    class="w-full px-4 py-3 pr-8 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary outline-none text-base appearance-none" required>
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="L" {{ old('gender', $user->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('gender', $user->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                </div>
            </div>
            </div>

            <!-- Section 2: Pengaturan Akun -->
            <div class="p-6 bg-white rounded-3xl">
            <div class="text-lg font-semibold mb-4 text-indigo-950">Pengaturan Akun</div>
            <div class="flex flex-col gap-4">
                <div>
                <label for="email" class="block text-sm font-semibold mb-2">Email *</label>
                <input type="email" name="email" id="email"
                    value="{{ old('email', $user->email) }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary outline-none text-base"
                    placeholder="Email Address" required>
                </div>
                <div>
                <label for="password" class="block text-sm font-semibold mb-2">Change Password</label>
                <input type="password" name="password" id="password"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary outline-none text-base"
                    placeholder="Isi jika ingin mengubah password">
                </div>
            </div>
            </div>

            <!-- Section 3: Status Kehamilan (hanya perempuan) -->
            <div id="pregnancy-section" class="p-6 bg-white rounded-3xl" style="display: {{ old('gender', $user->gender) == 'P' ? 'block' : 'none' }};">
                <div class="flex gap-2 items-start bg-pink-50 border border-pink-200 rounded-xl px-4 py-3">
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-pink-800">Apakah Anda sedang hamil?</p>
                        <p class="text-xs text-pink-600 mt-0.5">Penting untuk filter obat yang aman untuk ibu hamil.</p>
                    </div>
                    <div class="flex gap-3 items-center">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="is_pregnant" value="1"
                                {{ old('is_pregnant', $user->is_pregnant) == 1 ? 'checked' : '' }}
                                class="w-4 h-4 text-pink-600 border-gray-300">
                            <span class="ml-1.5 text-xs font-medium text-pink-700">Ya</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="is_pregnant" value="0"
                                {{ old('is_pregnant', $user->is_pregnant) == 0 ? 'checked' : '' }}
                                class="w-4 h-4 text-gray-600 border-gray-300">
                            <span class="ml-1.5 text-xs font-medium text-gray-600">Tidak</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Section 4: Riwayat Penyakit -->
            <div class="p-6 bg-white rounded-3xl">
                <div class="text-lg font-semibold mb-2 text-indigo-950">Riwayat Penyakit</div>
                <p class="text-xs text-gray-600 mb-4">Pilih kondisi yang pernah atau sedang Anda alami. Biarkan "Tidak" jika tidak ada.</p>

                <div class="flex flex-col gap-3">
                    <div class="flex gap-2 items-start bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 mb-1">
                        <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-xs text-blue-800">
                            Pilih <strong>semua kondisi kesehatan</strong> yang Anda alami untuk mendapatkan rekomendasi obat yang aman.
                        </p>
                    </div>

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
                                    <input type="radio" name="{{ $c['name'] }}" value="1"
                                        {{ old($c['name'], $user->{$c['name']}) == 1 ? 'checked' : '' }}
                                        class="w-4 h-4 text-primary border-gray-300">
                                    <span class="ml-1 text-xs text-green-600">Ya</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" name="{{ $c['name'] }}" value="0"
                                        {{ old($c['name'], $user->{$c['name']}) == 0 ? 'checked' : '' }}
                                        class="w-4 h-4 text-primary border-gray-300">
                                    <span class="ml-1 text-xs text-gray-500">Tidak</span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Section 5: Alergi Obat -->
            <div class="p-6 bg-white rounded-3xl">
                <div class="text-lg font-semibold mb-2 text-indigo-950">Alergi Obat</div>
                <p class="text-xs text-gray-600 mb-4">Pilih jenis obat yang pernah membuat Anda alergi.</p>

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
                                    <input type="radio" name="{{ $a['name'] }}" value="1"
                                        {{ old($a['name'], $user->{$a['name']}) == 1 ? 'checked' : '' }}
                                        class="w-4 h-4 text-primary border-gray-300">
                                    <span class="ml-1 text-xs text-green-600">Ya</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" name="{{ $a['name'] }}" value="0"
                                        {{ old($a['name'], $user->{$a['name']}) == 0 ? 'checked' : '' }}
                                        class="w-4 h-4 text-primary border-gray-300">
                                    <span class="ml-1 text-xs text-gray-500">Tidak</span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
                 
            <!-- Submit Button -->
            <div class="fixed z-50 bottom-[30px] left-1/2 -translate-x-1/2 w-[calc(100dvw-32px)] max-w-[425px]">
                <button type="submit" class="w-full bg-primary text-white rounded-full py-4 font-bold text-base shadow-lg hover:bg-opacity-90 transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </section>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="{{ asset('scripts/global.js') }}"></script>
  <script src="{{ asset('scripts/fixedTopbar.js') }}"></script>
  <script>
    // Toggle pregnancy section based on gender
    const genderSelect = document.getElementById('gender');
    const pregnancySection = document.getElementById('pregnancy-section');
    
    function togglePregnancy() {
      pregnancySection.style.display = genderSelect.value === 'P' ? 'block' : 'none';
    }
    
    genderSelect.addEventListener('change', togglePregnancy);
  </script>
</body>
</html>
