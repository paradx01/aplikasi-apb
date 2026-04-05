<?php

namespace App\Http\Controllers;

use App\Models\RecommendationRule;
use App\Models\Disease;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecommendationRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function index()
    {
        $rules = RecommendationRule::with(['disease', 'product'])
            ->orderBy('disease_id')
            ->orderBy('priority', 'asc')
            ->paginate(20);

        return view('admin.medicine_recommendation.index', [
            'rules' => $rules,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $diseases = Disease::orderBy('disease_name')->get();
        $products = Product::orderBy('name')->get();

        return view('admin.medicine_recommendation.create', [
            'diseases' => $diseases,
            'products' => $products,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'disease_id' => 'required|exists:diseases,id',
            'products'   => 'required|array|min:1',
            'products.*' => 'exists:products,id',
            'min_age'    => 'required|integer|min:0|max:150',
            'max_age'    => 'required|integer|min:0|max:150|gte:min_age',
            'priority'   => 'required|integer|min:1|max:100',
            'notes'      => 'nullable|string|max:1000',
        ], [
            'products.required' => 'Pilih minimal 1 produk obat',
            'products.min'      => 'Pilih minimal 1 produk obat',
            'max_age.gte'       => 'Umur maksimal harus lebih besar atau sama dengan umur minimal',
        ]);

        DB::beginTransaction();

        try {
            $diseaseId   = $validated['disease_id'];
            $products    = $validated['products'];
            $minAge      = $validated['min_age'];
            $maxAge      = $validated['max_age'];
            $basePriority= $validated['priority'];
            $notes       = $validated['notes'] ?? null;
            $inserted    = 0;

            foreach ($products as $index => $productId) {
                $exists = RecommendationRule::where('disease_id', $diseaseId)
                    ->where('product_id', $productId)
                    ->where('min_age', $minAge)
                    ->where('max_age', $maxAge)
                    ->exists();

                if ($exists) {
                    continue;
                }

                RecommendationRule::create([
                    'disease_id' => $diseaseId,
                    'product_id' => $productId,
                    'min_age'    => $minAge,
                    'max_age'    => $maxAge,
                    'priority'   => $basePriority + $index,
                    'notes'      => $notes,
                ]);

                $inserted++;
            }

            DB::commit();

            $disease = Disease::find($diseaseId);
            $message = "Berhasil menambahkan {$inserted} rekomendasi obat untuk penyakit '{$disease->disease_name}'";

            return redirect()
                ->route('admin.medicine-recommendation.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors([
                    'system_error' => 'System error: ' . $e->getMessage(),
                ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RecommendationRule $recommendation)
    {
        $diseases = Disease::orderBy('disease_name')->get();
        $products = Product::orderBy('name')->get();

        return view('admin.medicine_recommendation.edit', [
            'recommendation' => $recommendation,
            'diseases'       => $diseases,
            'products'       => $products,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RecommendationRule $recommendation)
    {
        $validated = $request->validate([
        'disease_id' => 'required|exists:diseases,id',
        'product_id' => 'required|exists:products,id',
        'min_age'    => 'required|integer|min:0|max:150',
        'max_age'    => 'required|integer|min:0|max:150|gte:min_age',
        'priority'   => 'required|integer|min:1|max:100',
        'notes'      => 'nullable|string|max:1000',
        ], [
            'max_age.gte' => 'Umur maksimal harus lebih besar atau sama dengan umur minimal',
        ]);

        DB::beginTransaction();

        try {
            // Cegah duplikasi kombinasi disease+product+age range
            $exists = RecommendationRule::where('disease_id', $validated['disease_id'])
                ->where('product_id', $validated['product_id'])
                ->where('min_age', $validated['min_age'])
                ->where('max_age', $validated['max_age'])
                ->where('id', '!=', $recommendation->id)
                ->exists();

            if ($exists) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'product_id' => 'Kombinasi penyakit, produk, dan rentang umur ini sudah ada.',
                    ]);
            }

            $recommendation->update($validated);

            DB::commit();

            return redirect()
                ->route('admin.medicine-recommendation.index')
                ->with('success', 'Rekomendasi obat berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors([
                    'system_error' => 'System error: ' . $e->getMessage(),
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RecommendationRule $recommendation)
    {
        try {
            $recommendation->delete();

            return redirect()
                ->back()
                ->with('success', 'Rekomendasi obat berhasil dihapus');

        } catch (\Exception $e) {
            return back()->withErrors([
                'system_error' => 'System error: ' . $e->getMessage(),
            ]);
        }
    }
}
