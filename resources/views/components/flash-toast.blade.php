@php
    $flashType = null;
    $flashMessage = null;

    if (session('success')) {
        $flashType = 'success';
        $flashMessage = session('success');
    } elseif (session('error')) {
        $flashType = 'error';
        $flashMessage = session('error');
    } elseif (session('info')) {
        $flashType = 'info';
        $flashMessage = session('info');
    } elseif ($errors->any()) {
        $flashType = 'error';
        $flashMessage = $errors->first();
    }

    $flashStyles = [
        'success' => 'bg-green-50 border-green-200 text-green-800',
        'error' => 'bg-red-50 border-red-200 text-red-800',
        'info' => 'bg-blue-50 border-blue-200 text-blue-800',
    ];
@endphp

@if($flashMessage)
    <div id="flash-toast-container" class="fixed top-14 left-1/2 -translate-x-1/2 z-[70] w-[calc(100dvw-32px)] max-w-[425px]">
        <div id="flash-toast" class="border rounded-2xl px-4 py-3 shadow {{ $flashStyles[$flashType] ?? $flashStyles['info'] }}">
            <p class="text-sm font-semibold">{{ $flashMessage }}</p>
        </div>
    </div>

    <script>
        setTimeout(() => {
            const toastContainer = document.getElementById('flash-toast-container');
            if (!toastContainer) return;
            toastContainer.style.transition = 'opacity 0.2s ease';
            toastContainer.style.opacity = '0';
            setTimeout(() => toastContainer.remove(), 220);
        }, 2600);
    </script>
@endif
