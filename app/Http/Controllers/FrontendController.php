<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductTransaction;
use App\Models\MedicationReminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontendController extends Controller
{
    /**
     * Mapping field kondisi medis user ke label kontraindikasi produk
     */
    private const CONDITION_MAP = [
        'has_hypertension'        => 'Hipertensi',
        'has_heart_disorder'      => 'Gangguan Jantung',
        'has_diabetes'            => 'Diabetes',
        'has_kidney_disorder'     => 'Gangguan Ginjal',
        'has_stomach_ulcer'       => 'Tukak Lambung',
        'has_liver_disorder'      => 'Gangguan Hati',
        'has_asthma'              => 'Asma',
        'has_glaucoma'            => 'Glaukoma',
        'has_prostate_disorder'   => 'Gangguan Prostat',
        'has_hyperthyroidism'     => 'Hipertiroidisme',
        'has_g6pd_deficiency'     => 'Defisiensi G6PD',
        'has_allergy_paracetamol' => 'Alergi Paracetamol',
        'has_allergy_nsaid'       => 'Alergi NSAID',
        'has_allergy_aspirin'     => 'Alergi Aspirin',
        'has_allergy_antihistamine'=> 'Alergi Antihistamin',
        'has_allergy_decongestant'=> 'Alergi Dekongestan',
        'has_allergy_bromhexine'  => 'Alergi Bromhexine',
        'has_allergy_guaifenesin' => 'Alergi Guaifenesin',
        'has_allergy_antacid'     => 'Alergi Antasida',
    ];

    /**
     * Ambil daftar label kondisi medis aktif milik user
     */
    private function getUserActiveConditions($user): array
    {
        $conditions = [];
        foreach (self::CONDITION_MAP as $field => $label) {
            if ($user->$field) {
                $conditions[] = $label;
            }
        }
        return $conditions;
    }

    /**
     * Cek apakah produk kontraindikasi dengan kondisi user
     */
    private function isProductContraindicated(Product $product, array $userConditions): bool
    {
        if (!$product->contraindications) return false;
        
        $productContra = array_map('trim', explode(',', $product->contraindications));
        return !empty(array_intersect($userConditions, $productContra));
    }

    //
    public function index(){
        $newProducts = Product::with('category')->orderBy('id', 'DESC')->take(6)->get();
        $allProducts = Product::with('category')->orderBy('id', 'ASC')->get();
        $categories = Category::all();

        // Ambil data cart user yang sedang login
        $my_carts = [];
        $userConditions = [];
        if (Auth::check()) {
            $my_carts = Auth::user()->carts()->with('product')->get();
            $userConditions = $this->getUserActiveConditions(Auth::user());
        }

        $hasActiveReminder = false;
        if (Auth::check()) {
            $hasActiveReminder = MedicationReminder::where('user_id', Auth::id())
                ->where('status', 'active')
                ->exists();
        }
        
        return view('frontend.index', [
            'product' => $newProducts,
            'products' => $allProducts,
            'categories' => $categories,
            'my_carts' => $my_carts,
            'hasActiveReminder' => $hasActiveReminder,
            'userConditions' => $userConditions,
        ]);
    }

    public function details(Product $product){
        $product = Product::with('medicationRules', 'category')->findOrFail($product->id);

        // Safety Check: cocokkan profil medis pasien dengan kontraindikasi produk
        $safetyWarnings = [];
        $pregnancyWarning = null;
        $ageWarning = null;
        $alternativeProducts = collect();

        if (Auth::check()) {
            $user = Auth::user();
            $userConditions = $this->getUserActiveConditions($user);

            // 1. Cek kontraindikasi vs kondisi medis user
            if ($product->contraindications) {
                $productContra = array_map('trim', explode(',', $product->contraindications));
                
                $conditionMap = [
                    'has_hypertension'        => 'Hipertensi',
                    'has_heart_disorder'      => 'Gangguan Jantung',
                    'has_diabetes'            => 'Diabetes',
                    'has_kidney_disorder'     => 'Gangguan Ginjal',
                    'has_stomach_ulcer'       => 'Tukak Lambung',
                    'has_liver_disorder'      => 'Gangguan Hati',
                    'has_asthma'              => 'Asma',
                    'has_glaucoma'            => 'Glaukoma',
                    'has_prostate_disorder'   => 'Gangguan Prostat',
                    'has_hyperthyroidism'     => 'Hipertiroidisme',
                    'has_g6pd_deficiency'     => 'Defisiensi G6PD',
                    'has_allergy_paracetamol' => 'Alergi Paracetamol',
                    'has_allergy_nsaid'       => 'Alergi NSAID',
                    'has_allergy_aspirin'     => 'Alergi Aspirin',
                    'has_allergy_antihistamine'=> 'Alergi Antihistamin',
                    'has_allergy_decongestant'=> 'Alergi Dekongestan',
                    'has_allergy_bromhexine'  => 'Alergi Bromhexine',
                    'has_allergy_guaifenesin' => 'Alergi Guaifenesin',
                    'has_allergy_antacid'     => 'Alergi Antasida',
                ];

                foreach ($conditionMap as $field => $label) {
                    if ($user->$field && in_array($label, $productContra)) {
                        $safetyWarnings[] = $label;
                    }
                }
            }

            // 2. Cek kategori kehamilan
            if ($user->is_pregnant && $user->gender == 'P') {
                $cat = strtoupper($product->pregnancy_category ?? '');
                if ($cat === 'X') {
                    $pregnancyWarning = [
                        'level' => 'danger',
                        'message' => 'Obat ini DILARANG untuk ibu hamil (Kategori X). Tidak boleh digunakan dalam kondisi apapun selama kehamilan.'
                    ];
                } elseif ($cat === 'D') {
                    $pregnancyWarning = [
                        'level' => 'danger',
                        'message' => 'Obat ini berisiko nyata pada janin (Kategori D). WAJIB konsultasi dengan dokter/apoteker sebelum menggunakan.'
                    ];
                } elseif ($cat === 'C') {
                    $pregnancyWarning = [
                        'level' => 'warning',
                        'message' => 'Obat ini memiliki risiko sedang untuk kehamilan (Kategori C). Konsultasikan dengan dokter/apoteker sebelum menggunakan.'
                    ];
                }
            }

            // 3. Cek kesesuaian umur dengan aturan pakai
            $userAge = $user->age ?? 0;
            if ($userAge > 0 && $product->medicationRules->count() > 0) {
                $hasMatchingRule = $product->medicationRules->contains(function ($rule) use ($userAge) {
                    $min = $rule->min_age ?? 0;
                    $max = $rule->max_age ?? 999;
                    return $userAge >= $min && $userAge <= $max;
                });

                if (!$hasMatchingRule) {
                    $ageWarning = "Usia Anda ({$userAge} tahun) tidak termasuk dalam rentang usia yang direkomendasikan untuk obat ini. Konsultasikan dengan apoteker.";
                }
            }

            // 4. Cari obat alternatif yang AMAN jika ada warning
            if (count($safetyWarnings) > 0 || $pregnancyWarning) {
                $alternativeProducts = Product::where('category_id', $product->category_id)
                    ->where('id', '!=', $product->id)
                    ->with('category')
                    ->get()
                    ->filter(function ($altProduct) use ($userConditions, $user) {
                        // Filter: tidak kontraindikasi dengan user
                        if ($this->isProductContraindicated($altProduct, $userConditions)) {
                            return false;
                        }
                        // Filter: aman untuk ibu hamil jika user hamil
                        if ($user->is_pregnant && $user->gender == 'P') {
                            $cat = strtoupper($altProduct->pregnancy_category ?? '');
                            if (in_array($cat, ['X', 'D'])) return false;
                        }
                        return true;
                    })
                    ->take(4)
                    ->values();
            }
        }

        // Cek apakah produk aman untuk anak
        // Rule harus: kategori "Anak" + dosis terisi + rentang umur valid (max_age <= 17)
        $isChildSafe = $product->medicationRules->contains(function ($rule) {
            $condition = strtolower(trim($rule->special_condition ?? ''));
            $dosage = trim($rule->default_dosage ?? '');
            $hasValidAge = !is_null($rule->min_age) && !is_null($rule->max_age) 
                           && $rule->max_age > 0 && $rule->max_age <= 17;
            
            return str_contains($condition, 'anak') && $dosage !== '' && $hasValidAge;
        });

        return view('frontend.details', [
            'product' => $product,
            'medication_rules' => $product->medicationRules,
            'safetyWarnings' => $safetyWarnings,
            'pregnancyWarning' => $pregnancyWarning,
            'ageWarning' => $ageWarning,
            'alternativeProducts' => $alternativeProducts,
            'isChildSafe' => $isChildSafe,
        ]);
    }
    
    public function category(Category $category){
        $userConditions = [];
        if (Auth::check()) {
            $userConditions = $this->getUserActiveConditions(Auth::user());
        }

        $products = Product::where('category_id', $category->id)->with('category')->get();
        return view('frontend.category', [
            'products' => $products,
            'category' => $category,
            'userConditions' => $userConditions,
        ]);
    }

    public function search(Request $request){
        $keyword = $request->input('keyword');
        
        $userConditions = [];
        if (Auth::check()) {
            $userConditions = $this->getUserActiveConditions(Auth::user());
        }

        $products = Product::where('name', 'LIKE', '%' . $keyword . '%')->get();

        return view('frontend.search', [
            'products' => $products,
            'keyword' => $keyword,
            'userConditions' => $userConditions,
        ]);
    }
}
