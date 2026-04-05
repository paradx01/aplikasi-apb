/**
 * Smart Back Navigation dengan Loop Prevention
 * Mencegah loop navigation dan mempertahankan form state
 */

(function() {
    // Track halaman yang sudah dikunjungi dalam session ini
    let visitedPages = JSON.parse(sessionStorage.getItem('visitedPages') || '[]');
    let currentPage = window.location.pathname;

    // Tambahkan halaman saat ini ke history
    if (!visitedPages.includes(currentPage)) {
        visitedPages.push(currentPage);
        sessionStorage.setItem('visitedPages', JSON.stringify(visitedPages));
    }

    // Simpan form state sebelum navigate
    window.addEventListener('beforeunload', function() {
        saveFormState();
    });

    // Restore form state saat page load
    window.addEventListener('DOMContentLoaded', function() {
        restoreFormState();
    });
})();

/**
 * Smart Back Function
 * Kembali ke halaman sebelumnya dengan validasi
 */
function smartBack(fallbackUrl = '/') {
    const visitedPages = JSON.parse(sessionStorage.getItem('visitedPages') || '[]');
    const currentPage = window.location.pathname;
    
    // Cek apakah ada history sebelumnya
    if (window.history.length > 1 && document.referrer) {
        const referrerPath = new URL(document.referrer).pathname;
        
        // LOOP PREVENTION: Cek apakah referrer adalah halaman yang lebih "advanced"
        // Urutan flow: index → gejala_umum → gejala_kritis → diagnosa → rekomendasi
        const flowOrder = [
            '/expert-system',
            '/expert-system/gejala-umum',
            '/expert-system/gejala-kritis',
            '/expert-system/diagnosa',
            '/expert-system/medicine-recommendation'
        ];
        
        const currentIndex = flowOrder.findIndex(path => currentPage.includes(path));
        const referrerIndex = flowOrder.findIndex(path => referrerPath.includes(path));
        
        // Jika referrer ada di flow dan indexnya lebih kecil (sebelumnya), boleh back
        if (referrerIndex !== -1 && referrerIndex < currentIndex) {
            window.history.back();
            return;
        }
        
        // Jika referrer bukan dari flow expert system, boleh back
        if (referrerIndex === -1 && !referrerPath.includes('/expert-system/')) {
            window.history.back();
            return;
        }
    }
    
    // Fallback: redirect ke URL yang ditentukan
    window.location.href = fallbackUrl;
}

/**
 * Save form state ke sessionStorage
 */
function saveFormState() {
    const forms = document.querySelectorAll('form');
    forms.forEach((form, index) => {
        const formData = new FormData(form);
        const data = {};
        
        // Simpan semua input values
        for (let [key, value] of formData.entries()) {
            if (!data[key]) {
                data[key] = [];
            }
            data[key].push(value);
        }
        
        // Simpan checkbox states
        const checkboxes = form.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            const name = checkbox.name || checkbox.id;
            if (name) {
                if (!data[name + '_checked']) {
                    data[name + '_checked'] = [];
                }
                data[name + '_checked'].push({
                    value: checkbox.value,
                    checked: checkbox.checked
                });
            }
        });
        
        sessionStorage.setItem('formState_' + window.location.pathname + '_' + index, JSON.stringify(data));
    });
}

/**
 * Restore form state dari sessionStorage
 */
function restoreFormState() {
    const forms = document.querySelectorAll('form');
    forms.forEach((form, index) => {
        const savedData = sessionStorage.getItem('formState_' + window.location.pathname + '_' + index);
        
        if (savedData) {
            const data = JSON.parse(savedData);
            
            // Restore checkbox states
            Object.keys(data).forEach(key => {
                if (key.endsWith('_checked')) {
                    const checkboxStates = data[key];
                    checkboxStates.forEach(state => {
                        const checkbox = form.querySelector(`input[type="checkbox"][value="${state.value}"]`);
                        if (checkbox && state.checked) {
                            checkbox.checked = true;
                            // Trigger change event untuk update UI
                            checkbox.dispatchEvent(new Event('change'));
                        }
                    });
                }
            });
        }
    });
}

/**
 * Clear form state (panggil saat submit berhasil)
 */
function clearFormState() {
    const keys = Object.keys(sessionStorage);
    keys.forEach(key => {
        if (key.startsWith('formState_')) {
            sessionStorage.removeItem(key);
        }
    });
}

/**
 * Reset expert system flow (panggil saat mulai dari awal)
 */
function resetExpertFlow() {
    sessionStorage.removeItem('visitedPages');
    clearFormState();
}
