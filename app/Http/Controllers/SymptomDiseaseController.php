<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SymptomDisease;
use App\Models\Symptom;
use App\Models\Disease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SymptomDiseaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua penyakit dengan gejala-gejalanya
        $diseases = Disease::with(['symptoms' => function($query) {
            // Urutkan gejala: Kritis dulu (is_critical=1), baru Umum (is_critical=0)
            $query->orderBy('symptom_diseases.is_critical', 'desc')
                  ->orderBy('symptom_name', 'asc');
        }])
        ->orderBy('disease_name', 'asc')
        ->get();
        
        // Hitung statistik
        $totalCommonSymptoms = DB::table('symptom_diseases')
            ->where('is_critical', 0)
            ->count();
        
        $totalCriticalSymptoms = DB::table('symptom_diseases')
            ->where('is_critical', 1)
            ->count();

        return view('admin.symptomdisease.index', compact(
            'diseases',
            'totalCommonSymptoms',
            'totalCriticalSymptoms'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $diseases = Disease::orderBy('disease_name')->get();
        $symptoms = Symptom::orderBy('symptom_name')->get();
        
        return view('admin.symptomdisease.create', [
            'symptoms' => $symptoms,
            'diseases' => $diseases
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'disease_id' => 'required|exists:diseases,id',
            'symptoms' => 'required|array|min:1',
            'symptoms.*' => 'exists:symptoms,id',
            'is_critical' => 'required|array',
            'is_critical.*' => 'in:0,1',
        ], [
            'symptoms.required' => 'Pilih minimal 1 gejala untuk penyakit ini',
            'symptoms.min' => 'Pilih minimal 1 gejala untuk penyakit ini',
        ]);

        $diseaseId = $validated['disease_id'];
        $symptoms = $validated['symptoms'];
        $criticalityMap = $validated['is_critical'];

        $inserted = 0;
        $skipped = 0;
        $errors = [];

        DB::beginTransaction();

        try {
            foreach ($symptoms as $symptomId) {
                // Cek apakah relasi sudah ada
                $exists = DB::table('symptom_diseases')
                    ->where('disease_id', $diseaseId)
                    ->where('symptom_id', $symptomId)
                    ->exists();

                if ($exists) {
                    $symptom = Symptom::find($symptomId);
                    $skipped++;
                    $errors[] = "Gejala '{$symptom->symptom_name}' sudah terhubung dengan penyakit ini";
                    continue;
                }

                // Insert relasi baru
                DB::table('symptom_diseases')->insert([
                    'disease_id' => $diseaseId,
                    'symptom_id' => $symptomId,
                    'is_critical' => $criticalityMap[$symptomId] ?? 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $inserted++;
            }

            DB::commit();

            $disease = Disease::find($diseaseId);
            $message = "Berhasil menambahkan {$inserted} gejala untuk penyakit '{$disease->disease_name}'";
            
            if ($skipped > 0) {
                $message .= ". {$skipped} gejala dilewati karena sudah ada.";
            }

            return redirect()->route('admin.symptom-diseases.index')
                ->with('success', $message);
                
        } catch(\Exception $e) {
            DB::rollback();
            $error = ValidationException::withMessages([
                'system_error' => ['System error: ' . $e->getMessage()],
            ]);
            throw $error;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SymptomDisease $symptomDisease)
    {
        //
    }

    /**
     * Show the form for editing (BATCH EDIT per disease)
     * 
     * PERUBAHAN: Parameter bukan lagi SymptomDisease, tapi $diseaseId
     */
    public function edit($diseaseId)
    {
        // Ambil data penyakit
        $disease = Disease::findOrFail($diseaseId);
        
        // Ambil semua gejala (untuk pilihan checkbox)
        $symptoms = Symptom::orderBy('symptom_name', 'asc')->get();
        
        // Ambil gejala yang sudah terhubung dengan penyakit ini
        $existingSymptoms = $disease->symptoms;
        
        return view('admin.symptomdisease.edit', compact(
            'disease',
            'symptoms',
            'existingSymptoms'
        ));
    }

    /**
     * Update (BATCH UPDATE - delete all lama, insert yang baru)
     * 
     * PERUBAHAN: Parameter bukan lagi SymptomDisease, tapi $diseaseId
     */
    public function update(Request $request, $diseaseId)
    {
        $validated = $request->validate([
            'symptoms' => 'nullable|array',
            'symptoms.*' => 'exists:symptoms,id',
            'is_critical' => 'nullable|array',
            'is_critical.*' => 'in:0,1',
        ]);

        // Validasi penyakit exists
        $disease = Disease::findOrFail($diseaseId);
        
        $selectedSymptoms = $validated['symptoms'] ?? [];
        $criticalityMap = $validated['is_critical'] ?? [];

        DB::beginTransaction();

        try {
            // STRATEGI: Hapus semua relasi lama untuk penyakit ini
            DB::table('symptom_diseases')
                ->where('disease_id', $diseaseId)
                ->delete();

            // Insert relasi baru (jika ada yang dipilih)
            if (count($selectedSymptoms) > 0) {
                foreach ($selectedSymptoms as $symptomId) {
                    DB::table('symptom_diseases')->insert([
                        'disease_id' => $diseaseId,
                        'symptom_id' => $symptomId,
                        'is_critical' => $criticalityMap[$symptomId] ?? 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::commit();

            // Pesan sukses
            $message = count($selectedSymptoms) > 0 
                ? "Berhasil memperbarui " . count($selectedSymptoms) . " gejala untuk penyakit '{$disease->disease_name}'"
                : "Semua gejala untuk penyakit '{$disease->disease_name}' telah dihapus";

            return redirect()->route('admin.symptom-diseases.index')
                ->with('success', $message);

        } catch(\Exception $e) {
            DB::rollback();
            $error = ValidationException::withMessages([
                'system_error' => ['System error: ' . $e->getMessage()],
            ]);
            throw $error;
        }
    }

    /**
     * Remove the specified resource from storage.
     * 
     * Ini untuk delete individual relation (jika masih diperlukan)
     */
    public function destroy(SymptomDisease $symptomDisease)
    {
        try {
            $symptomDisease->delete();
            
            return redirect()->back()
                ->with('success', 'Relasi gejala-penyakit berhasil dihapus');
                
        } catch(\Exception $e) {
            DB::rollback();
            $error = ValidationException::withMessages([
                'system_error' => ['System error: ' . $e->getMessage()],
            ]);
            throw $error;
        }
    }
    
    /**
     * OPTIONAL: Hapus semua gejala untuk satu penyakit
     */
    public function destroyAllByDisease($diseaseId)
    {
        $disease = Disease::findOrFail($diseaseId);
        
        DB::beginTransaction();
        
        try {
            $count = DB::table('symptom_diseases')
                ->where('disease_id', $diseaseId)
                ->count();
            
            DB::table('symptom_diseases')
                ->where('disease_id', $diseaseId)
                ->delete();
            
            DB::commit();
            
            return redirect()->back()
                ->with('success', "Berhasil menghapus {$count} gejala dari penyakit '{$disease->disease_name}'");
                
        } catch(\Exception $e) {
            DB::rollback();
            $error = ValidationException::withMessages([
                'system_error' => ['System error: ' . $e->getMessage()],
            ]);
            throw $error;
        }
    }
}
