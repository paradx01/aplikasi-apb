<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#eb812aff"/>
    <title>Sign In | Parma</title>
    <link rel="shortcut icon" href="{{asset('assets/svgs/logo-mark.svg')}}" type="image/x-icon">
    <link rel="manifest" href="/manifest.json">
    <script src="https://cdn.tailwindcss.com"></script>
	<link rel="stylesheet" href="{{asset('css/main.css')}}">
	<link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css'">
  </head>

  <body>

    <div class="flex flex-col items-center px-6 py-10 min-h-dvh">
      <img src="{{asset('assets/svgs/logo.svg')}}" class="mb-[53px]" alt="">
      <form action="{{ route('login') }}" method="POST" class="mx-auto max-w-[345px] w-full p-6 bg-white rounded-3xl mt-auto" id="deliveryForm">
      @csrf  
      <div class="flex flex-col gap-5">
          <p class="text-[22px] font-bold">
            Sign In
          </p>

          <!-- Error Messages -->
          @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-3">
              <ul class="text-xs text-red-700 space-y-1">
                @foreach ($errors->all() as $error)
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

          <!-- Email Address -->
          <div class="flex flex-col gap-2.5">
            <label for="email" class="text-base font-semibold">Email Address</label>
            <input style="background-image: url('{{asset('assets/svgs/ic-email.svg')}}')" type="email" name="email" id="email__"
              class="form-input @error('email') !border-red-400 @enderror" placeholder="Your email address" value="{{ old('email') }}">
            @error('email')
              <span class="text-xs text-red-600 -mt-1">{{ $message }}</span>
            @enderror
          </div>
          <!-- Password -->
          <div class="flex flex-col gap-2.5">
            <label for="password" class="text-base font-semibold">Password</label>
            <input style="background-image: url('{{asset('assets/svgs/ic-lock.svg')}}')" type="password" name="password" id="password__"
              class="form-input @error('password') !border-red-400 @enderror" placeholder="Protect your password">
            @error('password')
              <span class="text-xs text-red-600 -mt-1">{{ $message }}</span>
            @enderror
          </div>
          <button type="submit" class="inline-flex text-white font-bold text-base bg-primary rounded-full whitespace-nowrap px-[30px] py-3 justify-center items-center">
            Sign In
          </button>
        </div>
      </form>
      <a href="{{route('register')}}" class="font-semibold text-base mt-[30px] underline">
        Create New Account
      </a>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
      integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
      if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js');
      }
    </script>
  </body>

</html>