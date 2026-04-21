<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reminders | Parma</title>
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
      @include('partials.pwa')
</head>

  <body>
      <!-- Topbar -->
      <section  id="topbar" class="fixed top-0 z-20 w-full transition duration-300">
          <div class="flex items-center justify-between gap-2 wrapper">
            <button type="button" onclick="window.history.back()" class="p-2 bg-white rounded-full">
                <img src="{{asset('assets/svgs/ic-arrow-left.svg')}}" class="size-5" alt="">
            </button>
            <p id="topbar-title" class="absolute text-base font-semibold top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 transition duration-100 text-gray-800">
              Reminders
            </p>
          </div>
      </section>

      <div class="wrapper py-2 px-4">
        <div class="flex flex-col gap-4">
            @forelse($reminders as $reminder)
                <div class="rounded-xl bg-white shadow-md p-5 flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-lg font-bold text-primary mb-1">
                            {{ $reminder->product->name ?? 'Obat tidak ditemukan' }}
                        </p>
                        <p class="text-sm text-gray-600 mb-2">
                            Dosis: <span class="font-semibold text-gray-800">{{ $reminder->dosage }}</span>
                        </p>
                        <p class="text-sm text-gray-600 mb-1">
                            Jadwal: <span class="font-semibold">{{ $reminder->schedule_time }}</span>
                            @if($reminder->frequency)
                                - <span>{{ $reminder->frequency }}x sehari</span>
                            @endif
                        </p>
                        <p class="text-sm text-gray-600 mb-1">
                            Periode: {{ date('d M Y', strtotime($reminder->start_date)) }} - {{ date('d M Y', strtotime($reminder->end_date)) }}
                        </p>
                    </div>
                    <div class="flex flex-col items-end mt-2 md:mt-0">
                        <span class="px-3 py-1 rounded-full 
                            {{ $reminder->status == 'active' ? 'bg-green-100 text-green-800 font-medium' : 'bg-gray-200 text-gray-600 font-medium' }}">
                            {{ ucfirst($reminder->status) }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="px-5 py-3 text-center text-gray-600">
                    Belum ada pengingat minum obat aktif.<br>
                    Reminder otomatis muncul setelah kamu beli obat.
                </div>
            @endforelse
        </div>
    </div>

      

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
      integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="{{asset('scripts/global.js')}}"></script>
    <script src="{{asset('scripts/fixedTopbar.js')}}"></script>
    <script>
        function customBack(fallbackUrl) {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                window.location.href = fallbackUrl;
            }
        }
    </script>
  </body>

</html>