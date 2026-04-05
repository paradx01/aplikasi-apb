function setupScrollHeader() {
    const topbar = document.getElementById('topbar');
    // const title = document.getElementById('topbar-title'); // Tidak perlu diakses lagi
    
    // Listener untuk mendeteksi scroll
    window.addEventListener('scroll', function() {
        const scrollPosition = window.scrollY;
        const scrollThreshold = 100; // Ambang batas 100px

        if (scrollPosition > scrollThreshold) {
            // SCROLL DOWN: Tambahkan background solid dan shadow
            topbar.classList.add('scrolled');
        } else {
            // SCROLL UP (Kembali ke atas): Hapus background
            topbar.classList.remove('scrolled');
        }
    });
}

function setupFlickity() {
    const sliderElement = document.querySelector('.main-carousel');
    if (sliderElement) {
        new Flickity( sliderElement, {
            cellAlign: 'left',
            contain: true,
            prevNextButtons: false,
            pageDots: false,
            wrapAround: false
        });
    }
}

// Panggil fungsi setelah semua aset dimuat
window.onload = function() {
    setupScrollHeader();
    setupFlickity();
};