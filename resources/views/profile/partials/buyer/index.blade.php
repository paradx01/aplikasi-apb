<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | Parma</title>
    <link rel="shortcut icon" href="{{asset('assets/svgs/logo-mark.svg')}}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{asset('css/main.css')}}">
    <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css'">
    <style>
      html, body { 
        width: 100%; 
        overflow-x: hidden !important; 
        padding-top: 2.5rem;
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
        @include('components.flash-toast')

        <!-- Topbar -->
        <section  id="topbar" class="fixed top-0 z-20 w-full transition duration-300">
            <div class="flex items-center justify-between gap-2 wrapper">
                <a href="{{route('frontend.index')}}" class="p-2 bg-white rounded-full">
                  <img src="{{asset('assets/svgs/ic-arrow-left.svg')}}" class="size-5" alt="">
                </a>
                <p id="topbar-title" class="absolute text-base font-semibold top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 transition duration-100 text-gray-800">
                  Profiles
                </p>
            </div>
        </section>

        <section class="wrapper flex flex-col gap-2.5">
          <div class="p-6 bg-white rounded-3xl">
            <div class="flex flex-col gap-2">
              <div class="flex items-center gap-3 p-3 rounded-lg">
                  <div class="flex-1">
                    <p class="text-xl font-bold capitalize text-primary">
                      @auth
                        {{ Auth::user()->name }}
                      @endauth
                      @guest
                        Guest
                      @endguest
                    </p>
                    <div class="flex items-center gap-4 text-sm mt-2 text-gray-600">
                      <div>
                        <span>
                          @if(Auth::user()->gender == 'L') Laki-laki
                          @elseif(Auth::user()->gender == 'P') Perempuan
                          @else -
                          @endif
                        </span>
                      </div>
                      <p> | </p>
                      <div>
                        <span>{{ Auth::user()->age ?? '-' }} tahun</span>
                      </div>
                    </div>
                  </div>
              </div>
            </div>
        </div>
        </section>
        
        <!-- Profile Settings Menu -->
        <section class="wrapper flex flex-col gap-2.5">
          <div class="p-6 bg-white rounded-3xl">
            <div class="text-lg font-semibold mb-4 text-indigo-950">Settings</div>
            <div class="flex flex-col gap-2">
              <!-- Menu: Account Settings -->
              <a href="{{ route('profile.partials.buyer.edit') }}" class="flex items-center gap-3 p-3 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                <span class="flex-1">Pengaturan Profile</span>
                <img src="{{ asset('assets/svgs/ic-chevron.svg') }}" class="-rotate-90 size-4" alt="">
              </a>
              <!-- Menu: Address Settings -->
              <a href="{{ route('frontend.addresses.index') }}" class="flex items-center gap-3 p-3 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                </svg>
                <span class="flex-1">Pengaturan Alamat</span>
                <img src="{{ asset('assets/svgs/ic-chevron.svg') }}" class="-rotate-90 size-4" alt="">
              </a>
              <!-- Menu: Medication Reminder -->
              <a href="{{ route('frontend.reminders.index') }}" class="flex items-center gap-3 p-3 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                </svg>
                <span class="flex-1">Pengingat Medikasi</span>
                <img src="{{ asset('assets/svgs/ic-chevron.svg') }}" class="-rotate-90 size-4" alt="">
              </a>
              <!-- Menu: Recommendation History -->
              <a href="{{ route('frontend.expertsystem.listHistory') }}" class="flex items-center gap-3 p-3 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 0-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                </svg>
                <span class="flex-1">Riwayat Rekomendasi</span>
                <img src="{{ asset('assets/svgs/ic-chevron.svg') }}" class="-rotate-90 size-4" alt="">
              </a>                    
            </div>
          </div>
        </section>
        
        <section class="wrapper flex flex-col gap-2.5">
          <div class="p-6 bg-white rounded-3xl">
            <div class="flex flex-col gap-2">
              <form action="{{ route('logout') }}" method="POST" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100 transition">
                @csrf
                <!-- Heroicon Logout -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V5a2 2 0 00-2-2h-6a2 2 0 00-2 2v14a2 2 0 002 2h6a2 2 0 002-2v-1" />
                </svg>
                <button type="submit" class="flex-1 text-red-600 text-left bg-transparent border-0 outline-none">Logout</button>
              </form> 
            </div>
          </div>
        </section>

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="{{asset('scripts/global.js')}}"></script>
        <script src="{{asset('scripts/fixedTopbar.js')}}"></script>
  </body>

</html>
