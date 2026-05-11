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
    //
    public function index(){
        $newProducts = Product::with('category')->orderBy('id', 'DESC')->take(6)->get();
        $allProducts = Product::with('category')->orderBy('id', 'ASC')->get();
        $categories = Category::all();

        // Ambil data cart user yang sedang login
        $my_carts = [];
        if (Auth::check()) {
            $my_carts = Auth::user()->carts()->with('product')->get();
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
        ]);
    }

    public function details(Product $product){
        $product = Product::with('medicationRules', 'category')->findOrFail($product->id);

        // Safety Check: cocokkan profil medis pasien dengan kontraindikasi produk
        $safetyWarnings = [];
        $pregnancyWarning = null;
        $ageWarning = null;

        if (Auth::check()) {
            $user = Auth::user();

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
        }

        return view('frontend.details', [
            'product' => $product,
            'medication_rules' => $product->medicationRules,
            'safetyWarnings' => $safetyWarnings,
            'pregnancyWarning' => $pregnancyWarning,
            'ageWarning' => $ageWarning,
        ]);
    }
    
    public function category(Category $category){
        $products = Product::where('category_id', $category->id)->with('category')->get();
        return view('frontend.category', [
            'products' => $products,
            'category' => $category,
        ]);
    }

    public function search(Request $request){
        $keyword = $request->input('keyword');

        $products = Product::where('name', 'LIKE', '%' . $keyword . '%')->get();

        return view('frontend.search', [
            'products' => $products,
            'keyword' => $keyword,
        ]);
    }
}
