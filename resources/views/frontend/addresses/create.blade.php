<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Address | Parma</title>
    <link rel="shortcut icon" href="{{asset('assets/svgs/logo-mark.svg')}}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{asset('css/main.css')}}">
    <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css'">
    <style>
      html, body { 
        width: 100%; 
        overflow-x: hidden !important; 
        padding-top: 2rem;
        scroll-behavior: smooth;
      }
      wrapper {
          max-width: 425px; 
          margin-left: auto;
          margin-right: auto;
          padding-left: 1.5rem; /* px-6 equivalent */
          padding-right: 1.5rem; /* px-6 equivalent */
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
          <button type="button" onclick="window.location.replace('{{ route('frontend.addresses.index') }}')" class="p-2 bg-white rounded-full">
            <img src="{{asset('assets/svgs/ic-arrow-left.svg')}}" class="size-5" alt="">
          </button>
          <p id="topbar-title" class="absolute text-base font-semibold top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 transition duration-100 text-gray-800">
            Add New Address
          </p>
        </div>
    </section>

    <!-- Error Messages -->
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

    <!-- Form -->
    <section class="wrapper pb-32">
        <form action="{{ route('frontend.addresses.store') }}" method="POST" class="flex flex-col gap-4">
            @csrf

            <!-- Label (Rumah, Kantor, etc) -->
            <div class="bg-white rounded-2xl p-4">
                <label for="label" class="block text-base font-semibold mb-3">Address Label *</label>
                <input type="text" 
                       name="label" 
                       id="label" 
                       value="{{ old('label') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent outline-none text-base"
                       placeholder="e.g., Home, Office, Apartment"
                       required>
                <p class="text-xs text-gray-500 mt-2">Give this address a memorable name</p>
            </div>

            <!-- Recipient Name -->
            <div class="bg-white rounded-2xl p-4">
                <label for="recipient_name" class="block text-base font-semibold mb-3">Recipient Name *</label>
                <input type="text" 
                       name="recipient_name" 
                       id="recipient_name" 
                       value="{{ old('recipient_name') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent outline-none text-base"
                       placeholder="Full name"
                       required>
            </div>

            <!-- Phone Number -->
            <div class="bg-white rounded-2xl p-4">
                <label for="phone_number" class="block text-base font-semibold mb-3">Phone Number *</label>
                <input type="tel" 
                       name="phone_number" 
                       id="phone_number" 
                       value="{{ old('phone_number') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent outline-none text-base"
                       placeholder="e.g., 081234567890"
                       required>
            </div>

            <!-- Address -->
            <div class="bg-white rounded-2xl p-4">
                <label for="address" class="block text-base font-semibold mb-3">Complete Address *</label>
                <textarea name="address" 
                          id="address" 
                          rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent outline-none text-base resize-none"
                          placeholder="Street name, building number, floor, etc."
                          required>{{ old('address') }}</textarea>
            </div>

            <!-- City -->
            <div class="bg-white rounded-2xl p-4">
                <label for="city" class="block text-base font-semibold mb-3">City *</label>
                <input type="text" 
                       name="city" 
                       id="city" 
                       value="{{ old('city') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent outline-none text-base"
                       placeholder="e.g., Jakarta Selatan"
                       required>
            </div>

            <!-- Post Code -->
            <div class="bg-white rounded-2xl p-4">
                <label for="post_code" class="block text-base font-semibold mb-3">Post Code *</label>
                <input type="text" 
                       name="post_code" 
                       id="post_code" 
                       value="{{ old('post_code') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent outline-none text-base"
                       placeholder="e.g., 12190"
                       required>
            </div>

            <!-- Notes (Optional) -->
            <div class="bg-white rounded-2xl p-4">
                <label for="notes" class="block text-base font-semibold mb-3">Additional Notes (Optional)</label>
                <textarea name="notes" 
                          id="notes" 
                          rows="2"
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent outline-none text-base resize-none"
                          placeholder="e.g., White house, black gate, near minimarket">{{ old('notes') }}</textarea>
                <p class="text-xs text-gray-500 mt-2">Help courier find your location</p>
            </div>

            <!-- Set as Primary -->
            <div class="bg-white rounded-2xl p-4">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" 
                           name="is_primary" 
                           id="is_primary" 
                           value="1"
                           {{ old('is_primary') ? 'checked' : '' }}
                           class="w-5 h-5 text-primary border-gray-300 rounded focus:ring-primary">
                    <div>
                        <p class="text-base font-semibold">Set as primary address</p>
                        <p class="text-xs text-gray-500">This will be your default delivery address</p>
                    </div>
                </label>
            </div>

            <!-- Submit Button -->
            <div class="fixed z-50 bottom-[30px] left-1/2 -translate-x-1/2 w-[calc(100dvw-32px)] max-w-[425px]">
                <button type="submit" class="w-full bg-primary text-white rounded-full py-4 font-bold text-base shadow-lg hover:bg-opacity-90 transition-all">
                    Save Address
                </button>
            </div>
        </form>
    </section>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
      integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="{{asset('scripts/global.js')}}"></script>
    <script src="{{asset('scripts/fixedTopbar.js')}}"></script>
  </body>

</html>