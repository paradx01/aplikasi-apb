<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request): View
    {
        return view('profile.partials.buyer.index');
    }

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }
    
    public function editBuyer(): View
    {
        $user = Auth::user();
        return view('profile.partials.buyer.edit', compact('user'));
    }

    /**
     * Tampilkan form lengkapi profil (onboarding user baru)
     */
    public function completeProfile(): View|RedirectResponse
    {
        $user = Auth::user();
        
        // Jika profil sudah lengkap, redirect ke home
        if (!is_null($user->age) && !is_null($user->gender)) {
            return redirect()->route('frontend.index');
        }
        
        return view('profile.partials.buyer.complete', compact('user'));
    }

    /**
     * Simpan data profil onboarding
     */
    public function storeCompleteProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'age' => 'required|integer|min:1|max:150',
            'gender' => 'required|in:L,P',
            'is_pregnant' => 'nullable|boolean',
            'has_hypertension' => 'nullable|boolean',
            'has_heart_disorder' => 'nullable|boolean',
            'has_diabetes' => 'nullable|boolean',
            'has_kidney_disorder' => 'nullable|boolean',
            'has_stomach_ulcer' => 'nullable|boolean',
            'has_liver_disorder' => 'nullable|boolean',
            'has_asthma' => 'nullable|boolean',
            'has_glaucoma' => 'nullable|boolean',
            'has_prostate_disorder' => 'nullable|boolean',
            'has_hyperthyroidism' => 'nullable|boolean',
            'has_g6pd_deficiency' => 'nullable|boolean',
            'has_allergy_paracetamol' => 'nullable|boolean',
            'has_allergy_nsaid' => 'nullable|boolean',
            'has_allergy_aspirin' => 'nullable|boolean',
            'has_allergy_antihistamine' => 'nullable|boolean',
            'has_allergy_decongestant' => 'nullable|boolean',
            'has_allergy_bromhexine' => 'nullable|boolean',
            'has_allergy_guaifenesin' => 'nullable|boolean',
            'has_allergy_antacid' => 'nullable|boolean',
        ], [
            'age.required' => 'Usia wajib diisi',
            'age.min' => 'Usia minimal 1 tahun',
            'gender.required' => 'Jenis kelamin wajib dipilih',
        ]);

        $user->age = $validated['age'];
        $user->gender = $validated['gender'];
        
        // Boolean fields
        $boolFields = [
            'is_pregnant', 'has_hypertension', 'has_heart_disorder', 'has_diabetes',
            'has_kidney_disorder', 'has_stomach_ulcer', 'has_liver_disorder', 'has_asthma',
            'has_glaucoma', 'has_prostate_disorder', 'has_hyperthyroidism', 'has_g6pd_deficiency',
            'has_allergy_paracetamol', 'has_allergy_nsaid', 'has_allergy_aspirin',
            'has_allergy_antihistamine', 'has_allergy_decongestant', 'has_allergy_bromhexine',
            'has_allergy_guaifenesin', 'has_allergy_antacid',
        ];

        foreach ($boolFields as $field) {
            $user->$field = $request->$field ? 1 : 0;
        }

        $user->save();

        return redirect()->route('frontend.index')->with('success', 'Profil berhasil dilengkapi! Selamat berbelanja.');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function updateBuyer(Request $request) {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'age' => 'required|integer|min:0|max:255',
            'gender' => 'required|in:L,P',
            // Kontraindikasi & profil klinis
            'is_pregnant' => 'nullable|boolean',

            'has_hypertension' => 'nullable|boolean',
            'has_heart_disorder' => 'nullable|boolean',
            'has_diabetes' => 'nullable|boolean',
            'has_kidney_disorder' => 'nullable|boolean',
            'has_stomach_ulcer' => 'nullable|boolean',
            'has_liver_disorder' => 'nullable|boolean',
            'has_asthma' => 'nullable|boolean',
            'has_glaucoma' => 'nullable|boolean',
            'has_prostate_disorder' => 'nullable|boolean',
            'has_hyperthyroidism' => 'nullable|boolean',
            'has_g6pd_deficiency' => 'nullable|boolean',

            'has_allergy_paracetamol' => 'nullable|boolean',
            'has_allergy_nsaid' => 'nullable|boolean',
            'has_allergy_aspirin' => 'nullable|boolean',
            'has_allergy_antihistamine' => 'nullable|boolean',
            'has_allergy_decongestant' => 'nullable|boolean',
            'has_allergy_bromhexine' => 'nullable|boolean',
            'has_allergy_guaifenesin' => 'nullable|boolean',
            'has_allergy_antacid' => 'nullable|boolean',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->age = $validated['age'];
        $user->gender = $validated['gender'];

        $user->is_pregnant = $request->is_pregnant ? 1 : 0;
        $user->has_hypertension = $request->has_hypertension ? 1 : 0;
        $user->has_heart_disorder = $request->has_heart_disorder ? 1 : 0;
        $user->has_diabetes = $request->has_diabetes ? 1 : 0;
        $user->has_stomach_ulcer = $request->has_stomach_ulcer ? 1 : 0;
        $user->has_kidney_disorder = $request->has_kidney_disorder ? 1 : 0;
        $user->has_liver_disorder = $request->has_liver_disorder ? 1 : 0;
        $user->has_asthma = $request->has_asthma ? 1 : 0;
        $user->has_glaucoma = $request->has_glaucoma ? 1 : 0;
        $user->has_prostate_disorder = $request->has_prostate_disorder ? 1 : 0;
        $user->has_hyperthyroidism = $request->has_hyperthyroidism ? 1 : 0;
        $user->has_g6pd_deficiency = $request->has_g6pd_deficiency ? 1 : 0;
        $user->has_allergy_paracetamol = $request->has_allergy_paracetamol ? 1 : 0;
        $user->has_allergy_nsaid = $request->has_allergy_nsaid ? 1 : 0;
        $user->has_allergy_aspirin = $request->has_allergy_aspirin ? 1 : 0;
        $user->has_allergy_antihistamine = $request->has_allergy_antihistamine ? 1 : 0;
        $user->has_allergy_decongestant = $request->has_allergy_decongestant ? 1 : 0;
        $user->has_allergy_bromhexine = $request->has_allergy_bromhexine ? 1 : 0;
        $user->has_allergy_guaifenesin = $request->has_allergy_guaifenesin ? 1 : 0;
        $user->has_allergy_antacid = $request->has_allergy_antacid ? 1 : 0;

        if($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui.');
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
