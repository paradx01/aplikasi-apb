<?php

namespace App\Http\Controllers;

use App\Models\Symptom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SymptomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $symptoms = Symptom::orderBy('id', 'DESC')->get();

        return view('admin.symptom.index', [
            'symptoms' => $symptoms
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.symptom.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'symptom_name' => 'required|string|max:255',
            'type' => 'required|in:umum,kritis',
            'description' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            $newGejala = Symptom::create($validated);

            DB::commit();

            return redirect()->route('admin.symptoms.index')->with('success', 'Gejala berhasil ditambahkan');
        }catch(\Exception $e){
            DB::rollback();
            $error = ValidationException::withMessages([
                'system_error' => ['System error' . $e->getMessage()],
            ]);
            throw $error;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Symptom $symptom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Symptom $symptom)
    {
        //
        return view('admin.symptom.edit', [
            'symptom' => $symptom
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Symptom $symptom)
    {
        //
        $validated = $request->validate([
            'symptom_name' => 'required|string|max:255',
            'type' => 'required|in:umum,kritis',
            'description' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            $symptom->update($validated);

            DB::commit();

            return redirect()->route('admin.symptoms.index')->with('success', 'Gejala berhasil diupdate');
        }catch(\Exception $e){
            DB::rollback();
            $error = ValidationException::withMessages([
                'system_error' => ['System error' . $e->getMessage()],
            ]);
            throw $error;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Symptom $symptom)
    {
        //
        try {
            $symptom->delete();
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
