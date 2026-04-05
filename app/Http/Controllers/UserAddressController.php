<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAddressController extends Controller
{
    // Tampilkan daftar alamat
    public function index()
    {
        $addresses = Auth::user()->addresses()->orderBy('is_primary', 'desc')->get();
        return view('frontend.addresses.index', compact('addresses')); 
    }

    // Form tambah alamat
    public function create()
    {
        return view('frontend.addresses.create');
    }

    // Simpan alamat baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'recipient_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'post_code' => 'required|string|max:10',
            'notes' => 'nullable|string',
            'is_primary' => 'boolean'
        ]);

        $validated['user_id'] = Auth::id();
        
        $address = UserAddress::create($validated);

        // Jika diset sebagai primary atau ini alamat pertama
        if ($request->is_primary || Auth::user()->addresses()->count() == 1) {
            $address->setAsPrimary();
        }

        return redirect()->route('frontend.addresses.index')->with('success', 'Alamat berhasil ditambahkan'); // ✅ Redirect ke index
    }

    // Form edit alamat
    public function edit(UserAddress $address)
    {
        // Pastikan user hanya bisa edit alamatnya sendiri
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        return view('frontend.addresses.edit', compact('address'));
    }

    // Update alamat
    public function update(Request $request, UserAddress $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'recipient_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'post_code' => 'required|string|max:10',
            'notes' => 'nullable|string',
            'is_primary' => 'boolean'
        ]);

        $address->update($validated);

        if ($request->is_primary) {
            $address->setAsPrimary();
        }

        return redirect()->route('frontend.addresses.index')->with('success', 'Alamat berhasil diupdate'); 
    }

    // Hapus alamat
    public function destroy(UserAddress $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        // Jika yang dihapus adalah primary dan masih ada alamat lain
        if ($address->is_primary && Auth::user()->addresses()->count() > 1) {
            // Set alamat pertama lainnya jadi primary
            Auth::user()->addresses()
                ->where('id', '!=', $address->id)
                ->first()
                ->setAsPrimary();
        }

        $address->delete();

        return redirect()->route('frontend.addresses.index')->with('success', 'Alamat berhasil dihapus'); // ✅ Fix route name
    }

    // Set alamat sebagai primary
    public function setPrimary(UserAddress $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $address->setAsPrimary();

        return redirect()->back()->with('success', 'Alamat utama berhasil diubah');
    }
}