// Toggle address selector
function toggleAddressSelector() {
  const selector = document.getElementById('address-selector');
  selector.classList.toggle('hidden');
}

// Confirm address selection
function confirmAddressSelection() {
  const selectedRadio = document.querySelector('input[name="address_radio"]:checked');
  
  if (selectedRadio) {
    const data = {
      label: selectedRadio.dataset.label,
      name: selectedRadio.dataset.name,
      phone: selectedRadio.dataset.phone,
      address: selectedRadio.dataset.address,
      city: selectedRadio.dataset.city,
      postcode: selectedRadio.dataset.postcode,
      notes: selectedRadio.dataset.notes,
      isPrimary: selectedRadio.dataset.primary === 'true'
    };
    
    // Update tampilan
    const displayDiv = document.getElementById('selected-address-display');
    const primaryBadge = data.isPrimary ? '<span class="bg-primary text-white text-xs font-semibold px-2 py-0.5 rounded-full">Primary</span>' : '';
    const noteText = data.notes ? `<p class="text-xs text-gray-500 mt-2">Note: ${data.notes}</p>` : '';
    
    displayDiv.innerHTML = `
      <div class="flex items-center gap-2 mb-1">
        <h3 class="text-base font-bold">${data.label}</h3>
        ${primaryBadge}
      </div>
      <p class="text-sm font-semibold text-gray-800">${data.name}</p>
      <p class="text-sm text-gray-600 mb-2">${data.phone}</p>
      <p class="text-sm text-gray-700">
        ${data.address}, ${data.city}, ${data.postcode}
      </p>
      ${noteText}
    `;
    
    // Update hidden inputs
    document.getElementById('hidden-address').value = data.address;
    document.getElementById('hidden-city').value = data.city;
    document.getElementById('hidden-post-code').value = data.postcode;
    document.getElementById('hidden-phone-number').value = data.phone;
    document.getElementById('hidden-notes').value = data.notes || 'No additional notes';
    
    toggleAddressSelector();
  }
}