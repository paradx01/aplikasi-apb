<?php

namespace App\Http\Controllers;

use App\Models\Symptom;
use App\Models\Disease;
use App\Models\SymptomDisease;
use App\Models\RecommendationRule;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpertController extends Controller
{
    public function index()
    {
        return view('frontend.expertsystem.index');
    }

    // Step 1: Tampilkan gejala UMUM saja
    public function showGejalaUmum() 
    {
        $gejalaList = Symptom::where('type', '!=', 'kritis')->orderBy('symptom_name')->get();
        
        return view('frontend.expertsystem.gejala', [
            'gejalaList' => $gejalaList
        ]);
    }

    // Step 2: Proses gejala umum, tampilkan gejala KRITIS yang RELEVAN
    public function showGejalaKritis(Request $request) 
    {
        $request->validate([
            'symptoms' => 'required|array|min:1',
            'symptoms.*' => 'exists:symptoms,id'
        ], [
            'symptoms.required' => 'Pilih minimal 1 gejala umum terlebih dahulu',
            'symptoms.min' => 'Pilih minimal 1 gejala umum terlebih dahulu',
        ]);

        $selectedGeneralSymptoms = $request->input('symptoms', []);

        // 1. Cari penyakit yang berkaitan dengan gejala umum yang dipilih
        $potentialDiseaseIds = SymptomDisease::whereIn('symptom_id', $selectedGeneralSymptoms)
            ->groupBy('disease_id')
            ->pluck('disease_id');

        // 2. Cari SEMUA gejala (umum + kritis) yang terkait dengan penyakit-penyakit tersebut
        $relevantSymptomIds = SymptomDisease::whereIn('disease_id', $potentialDiseaseIds)
            ->pluck('symptom_id')
            ->unique();

        // 3. Filter hanya gejala KRITIS yang relevan
        $gejalaKritis = Symptom::where('type', 'kritis') // atau sesuaikan dengan enum kamu
            ->whereIn('id', $relevantSymptomIds)
            ->whereNotIn('id', $selectedGeneralSymptoms)
            ->orderBy('symptom_name')
            ->get();

        // 4. Ambil nama gejala umum yang dipilih
        $selectedSymptoms = Symptom::whereIn('id', $selectedGeneralSymptoms)->get();

        return view('frontend.expertsystem.kritis', [ // Pastikan nama view ini
            'gejalaKritis' => $gejalaKritis,
            'selectedGeneralSymptoms' => $selectedGeneralSymptoms,
            'selectedSymptoms' => $selectedSymptoms,
        ]);
    }


    // Step 3: ADVANCED DIAGNOSIS dengan SCORING SYSTEM
    public function diagnosa(Request $request) 
    {
        $request->validate([
            'general_symptoms' => 'required|array|min:1',
            'general_symptoms.*' => 'exists:symptoms,id',
            'specific_symptoms' => 'nullable|array',
            'specific_symptoms.*' => 'exists:symptoms,id'
        ]);

        // Gabungkan gejala umum dan kritis
        $allSymptoms = array_merge(
            $request->input('general_symptoms', []),
            $request->input('specific_symptoms', [])
        );

        // SCORING SYSTEM untuk setiap penyakit
        $diseaseScores = [];

        // Ambil semua relasi symptom-disease yang relevan
        $symptomDiseases = SymptomDisease::whereIn('symptom_id', $allSymptoms)
            ->with('disease')
            ->get();

        // Hitung score untuk setiap penyakit
        foreach ($symptomDiseases as $sd) {
            $diseaseId = $sd->disease_id;
            
            // Inisialisasi jika belum ada
            if (!isset($diseaseScores[$diseaseId])) {
                // Hitung total gejala untuk penyakit ini
                $totalSymptoms = SymptomDisease::where('disease_id', $diseaseId)->count();
                $totalCritical = SymptomDisease::where('disease_id', $diseaseId)
                    ->where('is_critical', true)->count();
                
                $diseaseScores[$diseaseId] = [
                    'disease' => $sd->disease,
                    'score' => 0,
                    'max_score' => ($totalCritical * 3) + ($totalSymptoms - $totalCritical),
                    'matched_symptoms' => 0,
                    'matched_critical' => 0,
                    'total_symptoms' => $totalSymptoms,
                    'total_critical' => $totalCritical,
                ];
            }

            // Tambahkan score berdasarkan gejala yang cocok
            $diseaseScores[$diseaseId]['matched_symptoms']++;
            
            if ($sd->is_critical) {
                $diseaseScores[$diseaseId]['score'] += 3; // Gejala kritis: bobot 3
                $diseaseScores[$diseaseId]['matched_critical']++;
            } else {
                $diseaseScores[$diseaseId]['score'] += 1; // Gejala umum: bobot 1
            }
        }

        // Hitung confidence percentage
        $diseases = collect($diseaseScores)->map(function($data) {
            $data['confidence'] = $data['max_score'] > 0 
                ? round(($data['score'] / $data['max_score']) * 100, 1)
                : 0;
            
            return $data;
        })
        ->sortByDesc(function($data) {
            // Prioritas: critical match dulu, baru confidence
            return ($data['matched_critical'] * 1000) + $data['confidence'];
        })
        ->filter(function($data) {
            // ADAPTIVE THRESHOLD:
            // Jika ada critical match → threshold 30%
            // Jika tidak ada critical match → threshold 50% (lebih ketat)
            $threshold = $data['matched_critical'] > 0 ? 30 : 50;
            
            return $data['confidence'] >= $threshold;
        })
        ->values();

        // Cek apakah ada penyakit dengan critical match
        $hasCriticalMatch = $diseases->where('matched_critical', '>', 0)->count() > 0;

        return view('frontend.expertsystem.diagnosa', [
            'diseases' => $diseases,
            'selectedSymptoms' => $allSymptoms,
            'allSymptoms' => Symptom::whereIn('id', $allSymptoms)->get(),
            'hasCriticalMatch' => $hasCriticalMatch, // Flag untuk warning
        ]);
    }

    // Step 4: Rekomendasi obat
    public function rekomendasi(Request $request) 
    {
        $request->validate([
            'disease_id' => 'required|exists:diseases,id',
            'confidence' => 'nullable|numeric',
            'has_critical' => 'nullable|boolean',
            'matched_symptoms' => 'nullable|integer',
            'matched_critical' => 'nullable|integer',
            'total_symptoms' => 'nullable|integer',
            'total_critical' => 'nullable|integer',
        ]);

        $selectedDisease = $request->input('disease_id');
        $confidence = $request->input('confidence', 0);
        $hasCritical = $request->input('has_critical', false);

        $user = Auth::user();
        $userAge = $user->age ?? 0;
        $isPregnant = $user->is_pregnant ?? false;
        $disease = Disease::find($selectedDisease);

        // Ambil rules + produk
        $rules = RecommendationRule::where('disease_id', $selectedDisease)
            ->with('product')
            ->orderBy('priority')
            ->get();

        $produkFinal = [];
        $produkWithWarning = [];

        $userConditions = $this->getUserActiveConditions($user);

        foreach ($rules as $rule) {
            $product = $rule->product;
            if (!$product) continue;

            // 1) Filter umur berdasarkan recommendation_rules (target age group)
            if (!is_null($rule->min_age) && $userAge < $rule->min_age) continue;
            if (!is_null($rule->max_age) && $userAge > $rule->max_age) continue;

            // 2) Filter kontraindikasi berdasarkan product->contraindications (string koma)
            $productContra = $product->contraindications
                ? explode(',', $product->contraindications)
                : [];

            $productContra = array_map('trim', $productContra);

            if (!empty(array_intersect($userConditions, $productContra))) {
                continue;
            }

            // 3) Filter kehamilan berdasarkan product->pregnancy_category
            if ($isPregnant && $user->gender == 'P') {
                $category = strtoupper($product->pregnancy_category ?? '');

                if ($category === 'X') {
                    continue;
                }

                if (in_array($category, ['A', 'B'])) {
                    $product->pregnancy_warning = null;
                    $product->pregnancy_category = $category;
                    $product->is_safe_for_pregnancy = true;
                    $produkFinal[] = $product;
                } elseif ($category === 'C') {
                    $product->pregnancy_warning = 'Kategori C: Risiko tidak dapat dikesampingkan. Gunakan hanya jika manfaat lebih besar dari risiko. Konsultasi dengan dokter sangat disarankan.';
                    $product->pregnancy_category = $category;
                    $product->is_safe_for_pregnancy = false;
                    $produkWithWarning[] = $product;
                } elseif ($category === 'D') {
                    $product->pregnancy_warning = 'Kategori D: Bukti positif risiko pada janin. Hindari penggunaan kecuali dalam kondisi darurat. WAJIB konsultasi dokter.';
                    $product->pregnancy_category = $category;
                    $product->is_safe_for_pregnancy = false;
                    $produkWithWarning[] = $product;
                } else {
                    $product->pregnancy_warning = 'Data keamanan untuk ibu hamil tidak tersedia. Konsultasi dengan dokter/apoteker sebelum menggunakan obat ini.';
                    $product->pregnancy_category = $category ?: 'Unknown';
                    $product->is_safe_for_pregnancy = false;
                    $produkWithWarning[] = $product;
                }
            } else {
                $product->pregnancy_warning = null;
                $product->pregnancy_category = $product->pregnancy_category ?? null;
                $product->is_safe_for_pregnancy = true;
                $produkFinal[] = $product;
            }
        }

        $isLowConfidence = ($confidence < 50) || !$hasCritical;

        $diseaseData = [
            'disease' => $disease,
            'confidence' => $confidence,
            'matched_symptoms' => $request->input('matched_symptoms', 0),
            'matched_critical' => $request->input('matched_critical', 0),
            'total_symptoms' => $request->input('total_symptoms', 0),
            'total_critical' => $request->input('total_critical', 0),
        ];

        $allSymptoms = $request->input('symptoms', session('selected_symptoms', []));

        // Ambil detail gejala
        $selectedSymptomsData = Symptom::whereIn('id', $allSymptoms)
            ->get(['id', 'symptom_name'])
            ->map(fn($s) => [
                'id' => $s->id,
                'name' => $s->symptom_name
            ])
            ->toArray();

        // Gabungkan semua produk yang direkomendasikan
        $allRecommendedProducts = collect($produkFinal)
            ->merge($produkWithWarning)
            ->unique('id')
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'priority' => null,
                    'has_warning' => !empty($product->pregnancy_warning),
                ];
            })
            ->values()
            ->toArray();

        // Simpan ke session
        session([
            'last_recommendation' => [
                'disease_id' => $disease->id,
                'disease_name' => $disease->disease_name,
                'confidence' => $confidence,
                'selected_symptoms' => $selectedSymptomsData,
                'recommended_products' => $allRecommendedProducts,
            ],
        ]);

        return view('frontend.expertsystem.medicine', [
            'diseaseData' => $diseaseData,
            'products' => collect($produkFinal),
            'productsWithWarning' => collect($produkWithWarning),
            'user' => $user,
            'confidence' => $confidence ?? 0,
            'isLowConfidence' => $isLowConfidence ?? false,
        ]);
    }


    /**
     * Mengambil semua kondisi klinis user yang AKTIF
     * dalam bentuk array string yang cocok dengan label di product->contraindications
     */
    private function getUserActiveConditions($user): array
    {
        $conditions = [];

        // Kondisi Medis
        if ($user->has_hypertension)        $conditions[] = 'Hipertensi';
        if ($user->has_heart_disorder)      $conditions[] = 'Gangguan Jantung';
        if ($user->has_diabetes)            $conditions[] = 'Diabetes';
        if ($user->has_kidney_disorder)     $conditions[] = 'Gangguan Ginjal';
        if ($user->has_stomach_ulcer)       $conditions[] = 'Tukak Lambung';
        if ($user->has_liver_disorder)      $conditions[] = 'Gangguan Hati';
        if ($user->has_asthma)              $conditions[] = 'Asma';
        if ($user->has_glaucoma)            $conditions[] = 'Glaukoma';
        if ($user->has_prostate_disorder)   $conditions[] = 'Gangguan Prostat';
        if ($user->has_hyperthyroidism)     $conditions[] = 'Hipertiroidisme';
        if ($user->has_g6pd_deficiency)     $conditions[] = 'Defisiensi G6PD';

        // Alergi Obat
        if ($user->has_allergy_paracetamol)  $conditions[] = 'Alergi Paracetamol';
        if ($user->has_allergy_nsaid)        $conditions[] = 'Alergi NSAID';
        if ($user->has_allergy_aspirin)      $conditions[] = 'Alergi Aspirin';
        if ($user->has_allergy_antihistamine)$conditions[] = 'Alergi Antihistamin';
        if ($user->has_allergy_decongestant) $conditions[] = 'Alergi Dekongestan';
        if ($user->has_allergy_bromhexine)   $conditions[] = 'Alergi Bromhexine';
        if ($user->has_allergy_guaifenesin)  $conditions[] = 'Alergi Guaifenesin';
        if ($user->has_allergy_antacid)      $conditions[] = 'Alergi Antasida';

        return $conditions;
    }

}
