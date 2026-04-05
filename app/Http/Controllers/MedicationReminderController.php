<?php

namespace App\Http\Controllers;

use App\Models\MedicationReminder;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class MedicationReminderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $reminders = MedicationReminder::where('user_id', Auth::id())
                  ->with('product')
                  ->orderBy('schedule_time')
                  ->get();

        return view('frontend.reminders.index', [
            'reminders' => $reminders
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        // $products = Product::all();
        // return view('reminders.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->validate([
            'product_id'   => 'required|exists:products,id',
            'schedule_time'=> 'required',
            'frequency'    => 'required|string',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'dosage'       => 'required|string',
        ]);

        $data['user_id'] = Auth::id();
        $data['status'] = 'active';

        MedicationReminder::create($data);

        return redirect()->route('frontend.reminders.index')->with('success', 'Reminder berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(MedicationReminder $medicationReminder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MedicationReminder $medicationReminder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MedicationReminder $medicationReminder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MedicationReminder $medicationReminder)
    {
        //
    }

    public function saveSubscription(Request $request)
    {
        Log::info('Push payload:', $request->all());

        $user = Auth::user();
        
        Log::info('User:', ['user' => $user]);
        if (!$user) return response()->json(['error'=>'not login'], 403);

        $data = $request->input('subscription');
        $user->updatePushSubscription(
            $data['endpoint'],
            $data['keys']['p256dh'],
            $data['keys']['auth'],
            $data['contentEncoding'] ?? 'aesgcm'
        );
        return response()->json(['ok']);
    }

}
