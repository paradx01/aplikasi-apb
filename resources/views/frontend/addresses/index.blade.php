<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Addresses | Parma</title>
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
    @include('components.flash-toast')

    <!-- Topbar -->
    <section  id="topbar" class="fixed top-0 z-20 w-full transition duration-300">
        <div class="flex items-center justify-between gap-2 wrapper">
            <button type="button" onclick="window.history.back()" class="p-2 bg-white rounded-full">
                <img src="{{asset('assets/svgs/ic-arrow-left.svg')}}" class="size-5" alt="">
            </button>
            <p id="topbar-title" class="absolute text-base font-semibold top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 transition duration-100 text-gray-800">
                Add New Address
            </p>
        </div>
    </section>

    <!-- Address List -->
    <section class="wrapper flex flex-col gap-3 pb-32">
        @forelse($addresses as $address)
        <div class="bg-white rounded-2xl p-4 relative {{ $address->is_primary ? 'border-2 border-primary' : 'border border-gray-200' }}">
            <!-- Primary Badge -->
            @if($address->is_primary)
            <div class="absolute top-3 right-3">
                <span class="bg-primary text-white text-xs font-semibold px-2.5 py-1 rounded-full">
                    Primary
                </span>
            </div>
            @endif

            <!-- Label & Name -->
            <div class="flex items-start gap-2 mb-3">
                <svg class="w-5 h-5 {{ $address->is_primary ? 'text-primary' : 'text-gray-400' }} mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                </svg>
                <div class="flex-1">
                    <h3 class="text-base font-bold mb-1">{{ $address->label }}</h3>
                    <p class="text-sm font-semibold text-gray-800">{{ $address->recipient_name }}</p>
                    <p class="text-sm text-gray-600">{{ $address->phone_number }}</p>
                </div>
            </div>

            <!-- Address Details -->
            <div class="ml-7 mb-4">
                <p class="text-sm text-gray-700 mb-1">
                    {{ $address->address }}
                </p>
                <p class="text-sm text-gray-700">
                    {{ $address->city }}, {{ $address->post_code }}
                </p>
                @if($address->notes)
                <p class="text-xs text-gray-500 mt-2">
                    Note: {{ $address->notes }}
                </p>
                @endif
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-2 ml-7">
                @if(!$address->is_primary)
                <form action="{{ route('frontend.addresses.set-primary', $address) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full text-sm font-semibold text-primary border border-primary rounded-full py-2 hover:bg-purple-50 transition-all">
                        Set as Primary
                    </button>
                </form>
                @endif
                
                <a href="{{ route('frontend.addresses.edit', $address) }}" class="flex-1 text-center text-sm font-semibold text-primary border border-primary rounded-full py-2 hover:bg-purple-50 transition-all">
                    Edit
                </a>
                
                @if(!$address->is_primary)
                <form action="{{ route('frontend.addresses.destroy', $address) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this address?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-10 h-10 flex items-center justify-center text-red-500 border border-red-500 rounded-full hover:bg-red-50 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <!-- Empty State -->
        <div class="bg-white rounded-2xl p-8 text-center mt-8">
            <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <h3 class="text-lg font-bold text-gray-800 mb-2">No Saved Address</h3>
            <p class="text-sm text-gray-600 mb-6">Add your first delivery address to make checkout faster</p>
            <a href="{{ route('frontend.addresses.create') }}" class="inline-block text-sm font-semibold text-white bg-primary rounded-full px-8 py-3 hover:bg-opacity-90 transition-all">
                Add New Address
            </a>
        </div>
        @endforelse
    </section>

    <!-- Floating Add Button -->
    @if($addresses->count() > 0)
    <div class="fixed z-50 bottom-[30px] left-1/2 -translate-x-1/2 w-[calc(100dvw-32px)] max-w-[425px]">
        <a href="{{ route('frontend.addresses.create') }}" class="w-full bg-primary text-white rounded-full py-4 font-bold text-base shadow-lg hover:bg-opacity-90 transition-all flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add New Address
        </a>
    </div>
    @endif

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
