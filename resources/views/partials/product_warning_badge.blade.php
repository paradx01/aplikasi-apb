{{-- Badge warning kontraindikasi pada kartu produk --}}
@auth
  @if(isset($userConditions) && count($userConditions) > 0 && $item->contraindications)
    @php
      $productContra = array_map('trim', explode(',', $item->contraindications));
      $hasConflict = !empty(array_intersect($userConditions, $productContra));
    @endphp
    @if($hasConflict)
      <div class="absolute top-2 right-2 z-10 bg-red-500 rounded-full p-1" title="Kontraindikasi dengan kondisi medis Anda">
        <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
        </svg>
      </div>
    @endif
  @endif
@endauth
