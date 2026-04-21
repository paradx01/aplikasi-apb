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

            <!-- Section 3: Riwayat Penyakit -->
            <div class="p-6 bg-white rounded-3xl">
                <div class="text-lg font-semibold mb-2 text-indigo-950">
                    Riwayat Penyakit & Kondisi Kesehatan
                </div>
                <p class="text-xs text-gray-600 mb-4">
                    Informasi ini penting untuk keamanan rekomendasi obat yang sesuai dengan kondisi Anda.
                </p>
                
                <div class="flex flex-col gap-3">
                    <!-- Info Box -->
                    <div class="flex gap-2 items-start bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 mb-3">
                        <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-xs text-blue-800">
                            Pilih <strong>semua kondisi kesehatan</strong> yang Anda alami untuk mendapatkan rekomendasi obat yang aman.
                        </p>
                    </div>

                    <!-- Hamil (Hanya untuk Perempuan) -->
                    <div class="flex gap-2 items-center justify-between bg-pink-50 border border-pink-200 rounded-xl px-4 py-3"
                        style="display: {{ old('gender', $user->gender) == 'P' ? 'flex' : 'none' }};">
                        <div class="flex items-start gap-2 flex-1">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-pink-800">Apakah Anda sedang hamil?</p>
                                <p class="text-xs text-pink-600 mt-0.5">Penting untuk filter obat yang aman.</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="is_pregnant" value="1"
                                    {{ old('is_pregnant', $user->is_pregnant) == 1 ? 'checked' : '' }}
                                    class="w-4 h-4 text-pink-600 border-gray-300 rounded focus:ring-pink-500">
                                <span class="ml-1.5 text-xs font-medium text-pink-700">Ya</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer ml-2">
                                <input type="radio" name="is_pregnant" value="0"
                                    {{ old('is_pregnant', $user->is_pregnant) == 0 ? 'checked' : '' }}
                                    class="w-4 h-4 text-gray-600 border-gray-300 rounded focus:ring-gray-500">
                                <span class="ml-1.5 text-xs font-medium text-gray-600">Tidak</span>
                            </label>
                        </div>
                    </div>

                    <!-- Hipertensi -->
                    <div class="flex items-center justify-between bg-gray-100 rounded-xl px-4 py-2">
                        <div class="mb-1 text-xs font-medium">
                            Apakah Anda pernah atau sedang mengalami hipertensi (tekanan darah tinggi)?
                        </div>
                        <div class="flex gap-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="has_hypertension" value="1"
                                    {{ old('has_hypertension', $user->has_hypertension) == 1 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-green-600 text-xs">Ya</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer ml-2">
                                <input type="radio" name="has_hypertension" value="0"
                                    {{ old('has_hypertension', $user->has_hypertension) == 0 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-gray-600 text-xs">Tidak</span>
                            </label>
                        </div>
                    </div>

                    <!-- Gangguan Jantung -->
                    <div class="flex items-center justify-between bg-gray-100 rounded-xl px-4 py-2">
                        <div class="mb-1 text-xs font-medium">
                            Apakah Anda pernah atau sedang mengalami gangguan jantung (misalnya riwayat serangan jantung, aritmia, gagal jantung)?
                        </div>
                        <div class="flex gap-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="has_heart_disorder" value="1"
                                    {{ old('has_heart_disorder', $user->has_heart_disorder) == 1 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-green-600 text-xs">Ya</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer ml-2">
                                <input type="radio" name="has_heart_disorder" value="0"
                                    {{ old('has_heart_disorder', $user->has_heart_disorder) == 0 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-gray-600 text-xs">Tidak</span>
                            </label>
                        </div>
                    </div>

                    <!-- Diabetes -->
                    <div class="flex items-center justify-between bg-gray-100 rounded-xl px-4 py-2">
                        <div class="mb-1 text-xs font-medium">
                            Apakah Anda pernah atau sedang mengalami diabetes (kencing manis)?
                        </div>
                        <div class="flex gap-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="has_diabetes" value="1"
                                    {{ old('has_diabetes', $user->has_diabetes) == 1 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-green-600 text-xs">Ya</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer ml-2">
                                <input type="radio" name="has_diabetes" value="0"
                                    {{ old('has_diabetes', $user->has_diabetes) == 0 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-gray-600 text-xs">Tidak</span>
                            </label>
                        </div>
                    </div>

                    <!-- Gangguan Ginjal -->
                    <div class="flex items-center justify-between bg-gray-100 rounded-xl px-4 py-2">
                        <div class="mb-1 text-xs font-medium">
                            Apakah Anda pernah atau sedang mengalami gangguan ginjal?
                        </div>
                        <div class="flex gap-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="has_kidney_disorder" value="1"
                                    {{ old('has_kidney_disorder', $user->has_kidney_disorder) == 1 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-green-600 text-xs">Ya</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer ml-2">
                                <input type="radio" name="has_kidney_disorder" value="0"
                                    {{ old('has_kidney_disorder', $user->has_kidney_disorder) == 0 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-gray-600 text-xs">Tidak</span>
                            </label>
                        </div>
                    </div>

                    <!-- Maag/Tukak Lambung -->
                    <div class="flex items-center justify-between bg-gray-100 rounded-xl px-4 py-2">
                        <div class="mb-1 text-xs font-medium">
                            Apakah Anda pernah atau sedang mengalami maag berat, tukak lambung, atau riwayat perdarahan lambung?
                        </div>
                        <div class="flex gap-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="has_stomach_ulcer" value="1"
                                    {{ old('has_stomach_ulcer', $user->has_stomach_ulcer) == 1 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-green-600 text-xs">Ya</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer ml-2">
                                <input type="radio" name="has_stomach_ulcer" value="0"
                                    {{ old('has_stomach_ulcer', $user->has_stomach_ulcer) == 0 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-gray-600 text-xs">Tidak</span>
                            </label>
                        </div>
                    </div>

                    <!-- Gangguan Hati -->
                    <div class="flex items-center justify-between bg-gray-100 rounded-xl px-4 py-2">
                        <div class="mb-1 text-xs font-medium">
                            Apakah Anda pernah atau sedang mengalami gangguan hati (misalnya hepatitis berat, sirosis, gagal hati)?
                        </div>
                        <div class="flex gap-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="has_liver_disorder" value="1"
                                    {{ old('has_liver_disorder', $user->has_liver_disorder) == 1 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-green-600 text-xs">Ya</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer ml-2">
                                <input type="radio" name="has_liver_disorder" value="0"
                                    {{ old('has_liver_disorder', $user->has_liver_disorder) == 0 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-gray-600 text-xs">Tidak</span>
                            </label>
                        </div>
                    </div>

                    <!-- Asma -->
                    <div class="flex items-center justify-between bg-gray-100 rounded-xl px-4 py-2">
                        <div class="mb-1 text-xs font-medium">
                            Apakah Anda pernah atau sedang mengalami asma?
                        </div>
                        <div class="flex gap-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="has_asthma" value="1"
                                    {{ old('has_asthma', $user->has_asthma) == 1 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-green-600 text-xs">Ya</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer ml-2">
                                <input type="radio" name="has_asthma" value="0"
                                    {{ old('has_asthma', $user->has_asthma) == 0 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-gray-600 text-xs">Tidak</span>
                            </label>
                        </div>
                    </div>

                    <!-- Glaukoma -->
                    <div class="flex items-center justify-between bg-gray-100 rounded-xl px-4 py-2">
                        <div class="mb-1 text-xs font-medium">
                            Apakah Anda pernah atau sedang mengalami glaukoma (tekanan bola mata tinggi)?
                        </div>
                        <div class="flex gap-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="has_glaucoma" value="1"
                                    {{ old('has_glaucoma', $user->has_glaucoma) == 1 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-green-600 text-xs">Ya</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer ml-2">
                                <input type="radio" name="has_glaucoma" value="0"
                                    {{ old('has_glaucoma', $user->has_glaucoma) == 0 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-gray-600 text-xs">Tidak</span>
                            </label>
                        </div>
                    </div>

                    <!-- Gangguan Prostat -->
                    <div class="flex items-center justify-between bg-gray-100 rounded-xl px-4 py-2">
                        <div class="mb-1 text-xs font-medium">
                            Apakah Anda pernah atau sedang mengalami pembesaran prostat / sulit berkemih (BPH)?
                        </div>
                        <div class="flex gap-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="has_prostate_disorder" value="1"
                                    {{ old('has_prostate_disorder', $user->has_prostate_disorder) == 1 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-green-600 text-xs">Ya</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer ml-2">
                                <input type="radio" name="has_prostate_disorder" value="0"
                                    {{ old('has_prostate_disorder', $user->has_prostate_disorder) == 0 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-gray-600 text-xs">Tidak</span>
                            </label>
                        </div>
                    </div>

                    <!-- Hipertiroidisme -->
                    <div class="flex items-center justify-between bg-gray-100 rounded-xl px-4 py-2">
                        <div class="mb-1 text-xs font-medium">
                            Apakah Anda pernah didiagnosis hipertiroidisme (kelenjar tiroid terlalu aktif)?
                        </div>
                        <div class="flex gap-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="has_hyperthyroidism" value="1"
                                    {{ old('has_hyperthyroidism', $user->has_hyperthyroidism) == 1 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-green-600 text-xs">Ya</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer ml-2">
                                <input type="radio" name="has_hyperthyroidism" value="0"
                                    {{ old('has_hyperthyroidism', $user->has_hyperthyroidism) == 0 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-gray-600 text-xs">Tidak</span>
                            </label>
                        </div>
                    </div>

                    <!-- Defisiensi G6PD -->
                    <div class="flex items-center justify-between bg-gray-100 rounded-xl px-4 py-2">
                        <div class="mb-1 text-xs font-medium">
                            Apakah Anda pernah diberitahu dokter memiliki defisiensi G6PD?
                        </div>
                        <div class="flex gap-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="has_g6pd_deficiency" value="1"
                                    {{ old('has_g6pd_deficiency', $user->has_g6pd_deficiency) == 1 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-green-600 text-xs">Ya</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer ml-2">
                                <input type="radio" name="has_g6pd_deficiency" value="0"
                                    {{ old('has_g6pd_deficiency', $user->has_g6pd_deficiency) == 0 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-gray-600 text-xs">Tidak</span>
                            </label>
                        </div>
                    </div>

                    <!-- Alergi Paracetamol -->
                    <div class="flex items-center justify-between bg-gray-100 rounded-xl px-4 py-2">
                        <div class="mb-1 text-xs font-medium">
                            Apakah Anda memiliki alergi terhadap Paracetamol (misal: Sanmol, Panadol, Bodrex)?
                        </div>
                        <div class="flex gap-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="has_allergy_paracetamol" value="1"
                                    {{ old('has_allergy_paracetamol', $user->has_allergy_paracetamol) == 1 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-green-600 text-xs">Ya</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer ml-2">
                                <input type="radio" name="has_allergy_paracetamol" value="0"
                                    {{ old('has_allergy_paracetamol', $user->has_allergy_paracetamol) == 0 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-gray-600 text-xs">Tidak</span>
                            </label>
                        </div>
                    </div>

                    <!-- Alergi NSAID -->
                    <div class="flex items-center justify-between bg-gray-100 rounded-xl px-4 py-2">
                        <div class="mb-1 text-xs font-medium">
                            Apakah Anda memiliki alergi terhadap obat antiinflamasi (NSAID seperti Ibuprofen, Asam Mefenamat)?
                        </div>
                        <div class="flex gap-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="has_allergy_nsaid" value="1"
                                    {{ old('has_allergy_nsaid', $user->has_allergy_nsaid) == 1 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-green-600 text-xs">Ya</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer ml-2">
                                <input type="radio" name="has_allergy_nsaid" value="0"
                                    {{ old('has_allergy_nsaid', $user->has_allergy_nsaid) == 0 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-gray-600 text-xs">Tidak</span>
                            </label>
                        </div>
                    </div>

                    <!-- Alergi Aspirin -->
                    <div class="flex items-center justify-between bg-gray-100 rounded-xl px-4 py-2">
                        <div class="mb-1 text-xs font-medium">
                            Apakah Anda memiliki alergi terhadap Aspirin?
                        </div>
                        <div class="flex gap-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="has_allergy_aspirin" value="1"
                                    {{ old('has_allergy_aspirin', $user->has_allergy_aspirin) == 1 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-green-600 text-xs">Ya</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer ml-2">
                                <input type="radio" name="has_allergy_aspirin" value="0"
                                    {{ old('has_allergy_aspirin', $user->has_allergy_aspirin) == 0 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-gray-600 text-xs">Tidak</span>
                            </label>
                        </div>
                    </div>

                    <!-- Alergi Antihistamin -->
                    <div class="flex items-center justify-between bg-gray-100 rounded-xl px-4 py-2">
                        <div class="mb-1 text-xs font-medium">
                            Apakah Anda memiliki alergi terhadap obat antihistamin (misal CTM, Cetirizine, Loratadine)?
                        </div>
                        <div class="flex gap-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="has_allergy_antihistamine" value="1"
                                    {{ old('has_allergy_antihistamine', $user->has_allergy_antihistamine) == 1 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-green-600 text-xs">Ya</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer ml-2">
                                <input type="radio" name="has_allergy_antihistamine" value="0"
                                    {{ old('has_allergy_antihistamine', $user->has_allergy_antihistamine) == 0 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-gray-600 text-xs">Tidak</span>
                            </label>
                        </div>
                    </div>

                    <!-- Alergi Dekongestan -->
                    <div class="flex items-center justify-between bg-gray-100 rounded-xl px-4 py-2">
                        <div class="mb-1 text-xs font-medium">
                            Apakah Anda memiliki alergi terhadap dekongestan hidung (misal Pseudoefedrin)?
                        </div>
                        <div class="flex gap-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="has_allergy_decongestant" value="1"
                                    {{ old('has_allergy_decongestant', $user->has_allergy_decongestant) == 1 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-green-600 text-xs">Ya</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer ml-2">
                                <input type="radio" name="has_allergy_decongestant" value="0"
                                    {{ old('has_allergy_decongestant', $user->has_allergy_decongestant) == 0 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-gray-600 text-xs">Tidak</span>
                            </label>
                        </div>
                    </div>

                    <!-- Alergi Bromhexine -->
                    <div class="flex items-center justify-between bg-gray-100 rounded-xl px-4 py-2">
                        <div class="mb-1 text-xs font-medium">
                            Apakah Anda memiliki alergi terhadap Bromhexine (obat pengencer dahak)?
                        </div>
                        <div class="flex gap-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="has_allergy_bromhexine" value="1"
                                    {{ old('has_allergy_bromhexine', $user->has_allergy_bromhexine) == 1 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-green-600 text-xs">Ya</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer ml-2">
                                <input type="radio" name="has_allergy_bromhexine" value="0"
                                    {{ old('has_allergy_bromhexine', $user->has_allergy_bromhexine) == 0 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-gray-600 text-xs">Tidak</span>
                            </label>
                        </div>
                    </div>

                    <!-- Alergi Guaifenesin -->
                    <div class="flex items-center justify-between bg-gray-100 rounded-xl px-4 py-2">
                        <div class="mb-1 text-xs font-medium">
                            Apakah Anda memiliki alergi terhadap Guaifenesin (obat ekspektoran pengencer dahak)?
                        </div>
                        <div class="flex gap-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="has_allergy_guaifenesin" value="1"
                                    {{ old('has_allergy_guaifenesin', $user->has_allergy_guaifenesin) == 1 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-green-600 text-xs">Ya</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer ml-2">
                                <input type="radio" name="has_allergy_guaifenesin" value="0"
                                    {{ old('has_allergy_guaifenesin', $user->has_allergy_guaifenesin) == 0 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-gray-600 text-xs">Tidak</span>
                            </label>
                        </div>
                    </div>

                    <!-- Alergi Antasida -->
                    <div class="flex items-center justify-between bg-gray-100 rounded-xl px-4 py-2">
                        <div class="mb-1 text-xs font-medium">
                            Apakah Anda memiliki alergi terhadap obat antasida (misalnya Promag, Mylanta)?
                        </div>
                        <div class="flex gap-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="has_allergy_antacid" value="1"
                                    {{ old('has_allergy_antacid', $user->has_allergy_antacid) == 1 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-green-600 text-xs">Ya</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer ml-2">
                                <input type="radio" name="has_allergy_antacid" value="0"
                                    {{ old('has_allergy_antacid', $user->has_allergy_antacid) == 0 ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-gray-600 text-xs">Tidak</span>
                            </label>
                        </div>
                    </div>

                </div>
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
</body>
</html>
