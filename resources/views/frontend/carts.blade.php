<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart | Parma</title>
    <link rel="shortcut icon" href="{{asset('assets/svgs/logo-mark.svg')}}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{asset('css/main.css')}}">
    <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css'">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
              Shopping Carts
            </p>
          </div>
      </section>

      <form action="{{ route('product_transactions.store') }}" method="POST" enctype="multipart/form-data">
          <!-- CSRF Token -->
          @csrf
          <!-- Delivery Address -->
          <section class="wrapper flex flex-col gap-2.5">
            <div class="flex items-center justify-between">
              <p class="text-base font-bold">
                Delivery Address
              </p>
              @if($user_addresses && $user_addresses->count() > 0)
                <a href="{{ route('frontend.addresses.index') }}" class="text-sm font-semibold text-primary">
                  Manage
                </a>
              @endif
            </div>

            @if($user_addresses && $user_addresses->count() > 0)
              <!-- Selected Address Display -->
              <div class="bg-white rounded-2xl p-4">
                <div class="flex items-start gap-3">
                  <svg class="w-5 h-5 text-primary mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                  </svg>
                  <div class="flex-1" id="selected-address-display">
                    @php
                      $selectedAddress = $user_addresses->where('is_primary', true)->first() ?? $user_addresses->first();
                    @endphp
                    <div class="flex items-center gap-2 mb-1">
                      <h3 class="text-base font-bold">{{ $selectedAddress->label }}</h3>
                      @if($selectedAddress->is_primary)
                        <span class="bg-primary text-white text-xs font-semibold px-2 py-0.5 rounded-full">Primary</span>
                      @endif
                    </div>
                    <p class="text-sm font-semibold text-gray-800">{{ $selectedAddress->recipient_name }}</p>
                    <p class="text-sm text-gray-600 mb-2">{{ $selectedAddress->phone_number }}</p>
                    <p class="text-sm text-gray-700">
                      {{ $selectedAddress->address }}, {{ $selectedAddress->city }}, {{ $selectedAddress->post_code }}
                    </p>
                    @if($selectedAddress->notes)
                      <p class="text-xs text-gray-500 mt-2">Note: {{ $selectedAddress->notes }}</p>
                    @endif
                  </div>
                </div>

                <!-- Change Address Button -->
                <button type="button" 
                        class="w-full mt-3 text-sm font-semibold text-primary border border-primary rounded-full py-2 hover:bg-purple-50 transition-all"
                        onclick="toggleAddressSelector()">
                  Change Address
                </button>
              </div>

              <!-- Address Selector (Hidden by default) -->
              <div id="address-selector" class="hidden">
                <div class="bg-white rounded-2xl p-4">
                  <p class="text-sm font-semibold mb-3">Select Delivery Address:</p>
                  
                  <div class="flex flex-col gap-3 max-h-[350px] overflow-y-auto">
                    @foreach($user_addresses as $address)
                      <label class="relative flex items-start gap-3 p-3 border-2 rounded-xl cursor-pointer transition-all has-[:checked]:border-primary has-[:checked]:bg-purple-50">
                        <input type="radio" 
                              name="selected_address_id" 
                              value="{{ $address->id }}" 
                              class="mt-1 address-selector-radio"
                              {{ $address->id == $selectedAddress->id ? 'checked' : '' }}
                              data-label="{{ $address->label }}"
                              data-name="{{ $address->recipient_name }}"
                              data-phone="{{ $address->phone_number }}"
                              data-address="{{ $address->address }}"
                              data-city="{{ $address->city }}"
                              data-postcode="{{ $address->post_code }}"
                              data-notes="{{ $address->notes ?? '' }}"
                              data-primary="{{ $address->is_primary ? 'true' : 'false' }}">
                        
                        <div class="flex-1">
                          <div class="flex items-center gap-2 mb-1">
                            <h4 class="text-sm font-bold">{{ $address->label }}</h4>
                            @if($address->is_primary)
                              <span class="bg-primary text-white text-xs font-semibold px-2 py-0.5 rounded-full">Primary</span>
                            @endif
                          </div>
                          <p class="text-xs font-semibold text-gray-800">{{ $address->recipient_name }}</p>
                          <p class="text-xs text-gray-600">{{ $address->phone_number }}</p>
                          <p class="text-xs text-gray-700 mt-1">
                            {{ $address->address }}, {{ $address->city }}, {{ $address->post_code }}
                          </p>
                        </div>
                      </label>
                    @endforeach
                  </div>

                  <div class="flex gap-2 mt-3">
                    <button type="button" 
                            class="flex-1 text-sm font-semibold text-gray-600 border border-gray-300 rounded-full py-2 hover:bg-gray-50 transition-all"
                            onclick="toggleAddressSelector()">
                      Cancel
                    </button>
                    <button type="button" 
                            class="flex-1 text-sm font-semibold text-white bg-primary rounded-full py-2 hover:bg-opacity-90 transition-all"
                            onclick="confirmAddressSelection()">
                      Confirm
                    </button>
                  </div>
                </div>
              </div>

            @else
              <!-- No Address - Show add button -->
              <div class="bg-white rounded-2xl p-6 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <p class="text-sm text-gray-600 mb-4">You don't have any saved address yet</p>
                <a href="{{ route('frontend.addresses.create') }}" 
                  class="inline-block text-sm font-semibold text-white bg-primary rounded-full px-6 py-2 hover:bg-opacity-90 transition-all">
                  Add New Address
                </a>
              </div>
            @endif
          </section>

          <!-- Items -->
          <section class="wrapper flex flex-col gap-2.5">
            <div class="flex items-center justify-between">
              <p class="text-base font-bold">
                Items
              </p>
              <button type="button" class="p-2 bg-white rounded-full" data-expand="itemsList">
                <img src="{{asset('assets/svgs/ic-chevron.svg')}}" class="transition-all duration-300 -rotate-180 size-5" alt="">
              </button>
            </div>
            <div class="flex flex-col gap-4" id="itemsList">
              @forelse ($my_carts as $cart)
              <div class="py-3.5 pl-4 pr-[22px] bg-white rounded-2xl flex gap-2 items-center relative">
                <img src="{{Storage::url($cart->product->photo)}}" class="w-full max-w-[70px] max-h-[70px] object-contain" alt="">
                
                <div class="flex flex-col w-full">
                  <!-- Product Name & Price (Baris Atas) -->
                  <div class="flex flex-col gap-1">
                    <h3 class="text-base font-semibold whitespace-nowrap w-[180px] truncate">
                      {{$cart->product->name}}                
                    </h3>
                    <p class="text-sm text-primary font-semibold item-subtotal" data-cart-id="{{$cart->id}}" data-price="{{$cart->product->price}}">
                      Rp {{number_format($cart->product->price, 0, ',', '.')}}
                    </p>
                  </div>

                  <!-- Quantity Controls & Delete (Baris Bawah) -->
                  <div class="flex items-center justify-end w-full gap-2">
                    <!-- Delete Button -->
                    <div class="m-0">
                    
                      <button type="button" class="m-0 flex items-center justify-center delete-cart-trigger" data-cart-id="{{ $cart->id }}">
                        <img src="{{asset('assets/svgs/ic-trash-can-filled.svg')}}" class="size-[24px]" alt="">
                      </button>
                    </div>

                    <!-- Quantity Controls -->
                    <div class="flex items-center gap-1.5">
                      <button type="button" class="quantity-btn-minus w-6 h-6 flex items-center justify-center bg-gray-100 rounded-full hover:bg-gray-200 transition-all" data-cart-id="{{$cart->id}}">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                        </svg>
                      </button>
                      
                      <input type="number" 
                            class="quantity-input w-9 text-center border border-gray-200 rounded-lg py-0.5 text-xs font-semibold focus:outline-none focus:ring-1 focus:ring-primary" 
                            value="{{$cart->quantity}}" 
                            min="1" 
                            max="99"
                            data-cart-id="{{$cart->id}}"
                            data-price="{{$cart->product->price}}">
                      
                      <button type="button" class="quantity-btn-plus w-6 h-6 flex items-center justify-center bg-primary rounded-full hover:bg-opacity-90 transition-all" data-cart-id="{{$cart->id}}">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              @empty
              <p>Belum ada transaksi tersedia</p>
              @endforelse
            </div>
      </section>

      <!-- Details Payment -->
      <section class="wrapper flex flex-col gap-2.5">
        <div class="flex items-center justify-between">
          <p class="text-base font-bold">
            Details Payment
          </p>
          <button type="button" class="p-2 bg-white rounded-full" data-expand="__detailsPayment">
            <img src="{{asset('assets/svgs/ic-chevron.svg')}}" class="transition-all duration-300 size-5" alt="">
          </button>
        </div>
        <div class="p-6 bg-white rounded-3xl" id="__detailsPayment" style="display: none;">
          <ul class="flex flex-col gap-5">
            <li class="flex items-center justify-between">
              <p class="text-base font-semibold first:font-normal">
                Sub Total
              </p>
              <p class="text-base font-semibold first:font-normal" id="checkout-sub-total">
                
              </p>
            </li>
            <li class="flex items-center justify-between">
              <p class="text-base font-semibold first:font-normal">
                Delivery Fee
              </p>
              <p class="text-base font-semibold first:font-normal" id="checkout-delivery-fee">
                
              </p>
            </li>
            {{-- <li class="flex items-center justify-between">
              <p class="text-base font-semibold first:font-normal">
                PPN 11%
              </p>
              <p class="text-base font-semibold first:font-normal" id="checkout-ppn">
                
              </p>
            </li>
            <li class="flex items-center justify-between">
              <p class="text-base font-semibold first:font-normal">
                Insurance 23%
              </p>
              <p class="text-base font-semibold first:font-normal" id="checkout-insurance">
                
              </p>
            </li> --}}
          </ul>
        </div>
      </section>

      <!-- Payment Method -->
      <section class="wrapper flex flex-col gap-2.5 pb-40">
        <div class="flex items-center justify-between">
          <p class="text-base font-bold">
            Payment Method
          </p>
        </div>
        <div class="grid items-center grid-cols-2 gap-4">
          <label
            class="relative rounded-2xl bg-white flex gap-2.5 px-3.5 py-3 items-center justify-start has-[:checked]:ring-2 has-[:checked]:ring-primary transition-all">
            <input type="radio" name="payment_method" id="manualMethod" class="absolute opacity-0">
            <img src="{{asset('assets/svgs/ic-receipt-text-filled.svg')}}" alt="">
            <p class="text-base font-semibold">
              Transfer
            </p>
          </label>
          <label
            class="relative rounded-2xl bg-white flex gap-2.5 px-3.5 py-3 items-center justify-start has-[:checked]:ring-2 has-[:checked]:ring-primary transition-all">
            <input type="radio" name="payment_method" id="creditMethod" class="absolute opacity-0">
            <img src="{{asset('assets/svgs/ic-card-filled.svg')}}" alt="">
            <p class="text-base font-semibold">
              QRIS
            </p>
            </lab>
        </div>
        <div class="p-4 mt-0.5 bg-white rounded-3xl hidden" id="manualPaymentDetail">
          <div class="flex flex-col gap-5">
            <p class="text-base font-bold">
              Send Payment to
            </p>
            <div class="inline-flex items-center gap-2.5">
              <img src="{{asset('assets/svgs/ic-bank.svg')}}" class="size-5" alt="">
              <p class="text-base font-semibold">
                BANK BCA - PT Parma Indonesia
              </p>
            </div>
            <div class="inline-flex items-center gap-2.5">
              <img src="{{asset('assets/svgs/ic-security-card.svg')}}" class="size-5" alt="">
              <p class="text-base font-semibold">
                083902093092
              </p>
            </div>
          </div>
        </div>
        <!-- Proof of Payment (tetap ada) -->
            <div class="bg-white rounded-2xl p-4 mt-2">
              <label for="proof_of_payment" class="block text-base font-semibold mb-3">Proof of Payment</label>
              <input type="file" 
                    name="proof" 
                    id="proof_of_payment__"
                    class="form-input bg-[url('{{asset('assets/svgs/ic-folder-add.svg')}}')]"
                    required>
            </div>
      </section>

      <!-- Floating grand total -->
      <div class="fixed z-50 bottom-[30px] bg-black rounded-3xl p-5 left-1/2 -translate-x-1/2 w-[calc(100dvw-32px)] max-w-[425px]">
        <section class="flex items-center justify-between gap-5">
          <div>
            <p class="text-sm text-grey mb-0.5">
              Total Pembayaran 
            </p>
            <p class="text-lg min-[350px]:text-2xl font-bold text-white" id="checkout-grand-total">
              Rp 
            </p>
          </div>
          <button type="submit" class="inline-flex items-center justify-center px-5 py-3 text-base font-bold text-white rounded-full w-max bg-primary whitespace-nowrap">
            Checkout
          </button>
        </section>
      </div>
    </form>
    @foreach ($my_carts as $cart)
      <form id="deleteCartForm_{{ $cart->id }}" 
            action="{{ route('carts.destroy', $cart)}}" 
            method="POST" 
            style="display: none;">
          @csrf
          @method('DELETE')
      </form>
    @endforeach

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
      integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="{{asset('scripts/global.js')}}"></script>
    <script src="{{asset('scripts/fixedTopbar.js')}}"></script>
    <script src="{{asset('scripts/addressSelector.js')}}"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
      
      function updateCartQuantityToServer(cartId, quantity) {
        fetch(`/carts/${cartId}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({ quantity })
        });
      }

      // Handle minus button
      document.querySelectorAll('.quantity-btn-minus').forEach(btn => {
        btn.addEventListener('click', function() {
          const cartId = this.getAttribute('data-cart-id');
          const input = document.querySelector(`.quantity-input[data-cart-id="${cartId}"]`);
          let value = parseInt(input.value);
          
          if (value > 1) {
            value--;
            input.value = value;
            updateItemSubtotal(cartId, value);
            calculatePrice();
            updateCartQuantityToServer(cartId, value);
          }
        });
      });
      
      // Handle plus button
      document.querySelectorAll('.quantity-btn-plus').forEach(btn => {
        btn.addEventListener('click', function() {
          const cartId = this.getAttribute('data-cart-id');
          const input = document.querySelector(`.quantity-input[data-cart-id="${cartId}"]`);
          let value = parseInt(input.value);
          const max = parseInt(input.getAttribute('max'));
          
          if (value < max) {
            value++;
            input.value = value;
            updateItemSubtotal(cartId, value);
            calculatePrice();
            updateCartQuantityToServer(cartId, value);
          }
        });
      });
      
      // Handle manual input change
      document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
          const cartId = this.getAttribute('data-cart-id');
          let value = parseInt(this.value);
          const min = parseInt(this.getAttribute('min'));
          const max = parseInt(this.getAttribute('max'));
          
          // Validate input
          if (isNaN(value) || value < min) {
            value = min;
          } else if (value > max) {
            value = max;
          }
          
          this.value = value;
          updateItemSubtotal(cartId, value);
          calculatePrice();
          updateCartQuantityToServer(cartId, value);
        });
      });

    function confirmAddressSelection() {
    const selectedRadio = document.querySelector('.address-selector-radio:checked');
  
    if (selectedRadio) {
      // Update tampilan saja
      const displayElement = document.getElementById('selected-address-display');
      displayElement.innerHTML = `
        <div class="flex items-center gap-2 mb-1">
          <h3 class="text-base font-bold">${selectedRadio.getAttribute('data-label')}</h3>
          ${selectedRadio.getAttribute('data-primary') === 'true' ? 
            '<span class="bg-primary text-white text-xs font-semibold px-2 py-0.5 rounded-full">Primary</span>' : ''}
        </div>
        <p class="text-sm font-semibold text-gray-800">${selectedRadio.getAttribute('data-name')}</p>
        <p class="text-sm text-gray-600 mb-2">${selectedRadio.getAttribute('data-phone')}</p>
        <p class="text-sm text-gray-700">
          ${selectedRadio.getAttribute('data-address')}, ${selectedRadio.getAttribute('data-city')}, ${selectedRadio.getAttribute('data-postcode')}
        </p>
        ${selectedRadio.getAttribute('data-notes') ? 
          `<p class="text-xs text-gray-500 mt-2">Note: ${selectedRadio.getAttribute('data-notes')}</p>` : ''}
      `;
      
      // Tutup selector
      toggleAddressSelector();
    }
  }

      
  // Update subtotal untuk item tertentu
      function updateItemSubtotal(cartId, quantity) {
        const subtotalElement = document.querySelector(`.item-subtotal[data-cart-id="${cartId}"]`);
        const price = parseFloat(subtotalElement.getAttribute('data-price'));
        const subtotal = price * quantity;
        
        subtotalElement.textContent = `Rp ${subtotal.toLocaleString('id', {minimumFractionDigits: 0, maximumFractionDigits: 0})}`;
      }
      
      // Update fungsi calculatePrice untuk grand total
      function calculatePrice(){
        let subTotal = 0;
        let deliveryFee = 10000;

        // Loop semua quantity input untuk hitung subtotal
        document.querySelectorAll('.quantity-input').forEach(input => {
          const price = parseFloat(input.getAttribute('data-price'));
          const quantity = parseInt(input.value);
          subTotal += price * quantity;
        });

        document.getElementById('checkout-delivery-fee').textContent = `Rp ${deliveryFee.toLocaleString('id', {minimumFractionDigits: 0, maximumFractionDigits: 0})}`;
        
        document.getElementById('checkout-sub-total').textContent = `Rp ${subTotal.toLocaleString('id', {minimumFractionDigits: 0, maximumFractionDigits: 0})}`;

        // const tax = 11 * subTotal / 100;
        // document.getElementById('checkout-ppn').textContent = `Rp ${tax.toLocaleString('id', {minimumFractionDigits: 0, maximumFractionDigits: 0})}`;
        
        // const insurance = 23 * subTotal / 100;
        // document.getElementById('checkout-insurance').textContent = `Rp ${insurance.toLocaleString('id', {minimumFractionDigits: 0, maximumFractionDigits: 0})}`;
        
        const grandTotalPrice = subTotal +  deliveryFee;
        document.getElementById('checkout-grand-total').textContent = `Rp ${grandTotalPrice.toLocaleString('id', {minimumFractionDigits: 0, maximumFractionDigits: 0})}`;
      }
      
      // Initial calculation
      calculatePrice();

      const deleteButtons = document.querySelectorAll('.delete-cart-trigger');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const cartId = this.getAttribute('data-cart-id');
                const deleteForm = document.getElementById('deleteCartForm_' + cartId);
                
                if (deleteForm) {
                    // Hanya submit form DELETE tersembunyi
                    deleteForm.submit();
                }
            });
        });
    });
    </script>
  </body>

</html>
