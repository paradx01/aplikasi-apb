<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\MedicationRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $products = Product::with('category')->orderBy('id', 'DESC')->get();
        return view('admin.products.index', [
            'products' => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categories = Category::all();
        return view('admin.products.create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'price' => 'required|integer',
            'stock' => 'required|integer|min:0',
            'photo' => 'required|image|mimes:jpeg,png,jpg,svg,webp',
            'active_ingredients' => 'required|string',
            'composition' => 'required|string',
            'indications' => 'required|string',
            'side_effects' => 'required|string',
            'dosage_form' => 'required|string',
            'unit' => 'required|string',
            'pregnancy_category' => 'nullable|string|in:A,B,C,D,X',

            // Validation contraindications dengan nilai yang benar
            'contraindications' => 'nullable|array',
            'contraindications.*' => 'in:Hipertensi,Gangguan Jantung,Diabetes,Tukak Lambung,Gangguan Ginjal,Gangguan Hati,Asma,Alergi NSAID,Alergi Aspirin,Glaucoma,Epilepsi',
            
            // Medication rules
            'rules' => 'required|array',
            'rules.*.special_condition' => 'required|string',
            'rules.*.min_age' => 'nullable|integer',
            'rules.*.max_age' => 'nullable|integer',
            'rules.*.dosage' => 'nullable|string',
            'rules.*.usage_instruction' => 'nullable|string',
            'rules.*.frequency' => 'nullable|integer',
            'rules.*.duration' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $validated['slug'] = Str::slug($request->name);
            
            if($request->hasFile('photo')){
                $photoPath = $request->file('photo')->store('product_photo', 'public');
                $validated['photo'] = $photoPath;
            }

            $validated['active_ingredients'] = strtolower(
                preg_replace('/\s*,\s*/', ',', $validated['active_ingredients'])
            );
            
            // Memproses Kontraindikasi (dari Checkbox Array)
            if ($request->has('contraindications') && is_array($request->contraindications)) {
            // Menggabungkan array checkbox menjadi string yang dipisahkan koma
                $validated['contraindications'] = implode(',', $request->contraindications);
            } else {
                $validated['contraindications'] = null;
            }

            // 1. Simpan produk
            $product = Product::create($validated);

            // 2. Simpan medication_rules jika ada
            if ($request->has('rules') && is_array($request->rules)) {
                foreach ($request->rules as $rule) {
                    MedicationRule::create([
                        'product_id' => $product->id,
                        'special_condition' => $rule['special_condition'],
                        'min_age' => $rule['min_age'],
                        'max_age' => $rule['max_age'],
                        'default_dosage' => $rule['dosage'],
                        'usage_instruction' => $rule['usage_instruction'],
                        'default_frequency' => $rule['frequency'],
                        'duration' => $rule['duration'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.products.index');
        }catch(\Exception $e){
            DB::rollback();
            throw ValidationException::withMessages([
                'system_error' => ['System error' . $e->getMessage()],
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
        $categories = Category::all();

        // Definisikan array risiko di Controller
        $risk_options = [
            // Kondisi medis
            'Hipertensi' => 'Hipertensi (Tekanan Darah Tinggi)',
            'Gangguan Jantung' => 'Gangguan Jantung / Riwayat Penyakit Jantung',
            'Diabetes' => 'Diabetes (Kencing Manis)',
            'Gangguan Ginjal' => 'Gangguan Ginjal',
            'Tukak Lambung' => 'Maag Berat / Tukak Lambung / Riwayat Perdarahan Lambung',
            'Gangguan Hati' => 'Gangguan Hati / Liver',
            'Asma' => 'Asma',
            'Glaukoma' => 'Glaukoma (Tekanan Bola Mata Tinggi)',
            'Gangguan Prostat' => 'Gangguan Prostat / Pembesaran Prostat (BPH)',
            'Hipertiroidisme' => 'Hipertiroidisme',
            'Defisiensi G6PD' => 'Defisiensi G6PD',

            // Alergi / hipersensitivitas obat
            'Alergi Paracetamol' => 'Alergi Paracetamol (Sanmol, Panadol, Bodrex, dll)',
            'Alergi NSAID' => 'Alergi NSAID (Ibuprofen, Asam Mefenamat, dll)',
            'Alergi Aspirin' => 'Alergi Aspirin',
            'Alergi Antihistamin' => 'Alergi Antihistamin (CTM, Cetirizine, Loratadine, dll)',
            'Alergi Dekongestan' => 'Alergi Dekongestan (Pseudoefedrin)',
            'Alergi Bromhexine' => 'Alergi Bromhexine',
            'Alergi Guaifenesin' => 'Alergi Guaifenesin',
            'Alergi Antasida' => 'Alergi Antasida',
        ];

        // LOGIKA KRITIS: Memecah string kontraindikasi dari database menjadi array.
        // Jika data NULL, gunakan array kosong agar explode tidak error.
        $current_risks = $product->contraindications 
            ? explode(',', $product->contraindications) 
            : [];

        $default_kategori = ['Anak-anak', 'Dewasa', 'Lansia'];

        $medication_rules = \App\Models\MedicationRule::where('product_id', $product->id)->get();

        if ($medication_rules->isEmpty()) {
            $medication_rules = collect(array_map(function($cat) {
                return (object)[
                    'special_condition' => $cat,
                    'min_age' => null,
                    'max_age' => null,
                    'dosage' => null,
                    'usage_instruction' => null,
                    'frequency' => null,
                    'duration' => null,
                ];
            }, $default_kategori));
        }
        // Kirim semua variabel ke view
        return view('admin.products.edit', [
            'product' => $product,
            'categories' => $categories,
            'risk_options' => $risk_options,
            'current_risks' => $current_risks,
            'medication_rules' => $medication_rules, // <-- pastikan dikirim ke view!
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            // field produk utama
            'name' => 'sometimes|string|max:255',
            'category_id' => 'sometimes|integer|exists:categories,id',
            'price' => 'sometimes|integer',
            'stok' => 'sometimes|integer|min:0',
            'photo' => 'sometimes|image|mimes:jpeg,png,jpg,svg,webp',
            'active_ingredients' => 'sometimes|string',
            'composition' => 'sometimes|string',
            'indications' => 'sometimes|string',
            'side_effects' => 'sometimes|string',
            'dosage_form' => 'sometimes|string',
            'unit' => 'sometimes|string',
            'pregnancy_category' => 'nullable|string|in:A,B,C,D,X',
            
            // Validation contraindications dengan nilai yang benar
            'contraindications' => 'nullable|array',
            'contraindications.*' => 'in:Hipertensi,Gangguan Jantung,Diabetes,Gangguan Ginjal,Tukak Lambung,Gangguan Hati,Asma,Glaukoma,Gangguan Prostat,Hipertiroidisme,Defisiensi G6PD,Alergi Paracetamol,Alergi NSAID,Alergi Aspirin,Alergi Antihistamin,Alergi Dekongestan,Alergi Bromhexine,Alergi Guaifenesin,Alergi Antasida',
            
            // medication_rules
            'rules' => 'nullable|array',
            'rules.*.special_condition' => 'required|string',
            'rules.*.min_age' => 'nullable|integer',
            'rules.*.max_age' => 'nullable|integer',
            'rules.*.dosage' => 'nullable|string',
            'rules.*.usage_instruction' => 'nullable|string',
            'rules.*.frequency' => 'nullable|integer',
            'rules.*.duration' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Transformasi produk seperti sebelumnya
            $validated['slug'] = Str::slug($request->name);
            if($request->hasFile('photo')){
                $photoPath = $request->file('photo')->store('product_photo', 'public');
                $validated['photo'] = $photoPath;
            }
            $validated['active_ingredients'] = strtolower(
                preg_replace('/\s*,\s*/', ',', $validated['active_ingredients'])
            );
            if ($request->has('contraindications') && is_array($request->contraindications)) {
                $validated['contraindications'] = implode(',', $request->contraindications);
            } else {
                $validated['contraindications'] = null;
            }
            $product->update($validated);

            // Update medication_rules
            if ($request->has('rules') && is_array($request->rules)) {
                // Hapus seluruh aturan lama, insert baru (atau pakai update by id jika ingin granular)
                MedicationRule::where('product_id', $product->id)->delete();

                foreach ($request->rules as $rule) {
                    if (!empty($rule['dosage'])) {
                        MedicationRule::create([
                            'product_id' => $product->id,
                            'special_condition' => $rule['special_condition'],
                            'min_age' => $rule['min_age'] ?? null,
                            'max_age' => $rule['max_age'] ?? null,
                            'default_dosage' => $rule['dosage'] ?? null,
                            'usage_instruction' => $rule['usage_instruction'] ?? null,
                            'default_frequency' => $rule['frequency'] ?? null,
                            'duration' => $rule['duration'] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.products.index');
        }catch(\Exception $e){
            DB::rollback();
            throw ValidationException::withMessages([
                'system_error' => ['System error' . $e->getMessage()],
            ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
        try {
            $product->delete();
            return redirect()->back();
        } catch(\Exception $e){
            DB::rollback();
            $error = ValidationException::withMessages([
                'system_error' => ['System error' . $e->getMessage()],
            ]);
            throw $error;
        }
    }
}
