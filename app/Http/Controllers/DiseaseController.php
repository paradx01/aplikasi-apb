<?php

namespace App\Http\Controllers;

use App\Models\Disease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class DiseaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $diseases = Disease::orderBy('id', 'DESC')->get();
        
        return view('admin.disease.index', [
            'diseases' => $diseases
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.disease.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'disease_name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            $newDisease = Disease::create($validated);

            DB::commit();

            return redirect()->route('admin.diseases.index')->with('success', 'Gejala berhasil ditambahkan');
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
    public function show(Disease $disease)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Disease $disease)
    {
        //
        return view('admin.disease.edit', [
            'disease' => $disease
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Disease $disease)
    {
        //
        $validated = $request->validate([
            'disease_name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            $disease->update($validated);

            DB::commit();

            return redirect()->route('admin.diseases.index')->with('success', 'Gejala berhasil diupdate');
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
    public function destroy(Disease $disease)
    {
        //
        try {
            $disease->delete();
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
