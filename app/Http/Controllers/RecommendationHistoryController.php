<?php

namespace App\Http\Controllers;

use App\Models\RecommendationHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecommendationHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $histories = RecommendationHistory::where('user_id', Auth::id())
            ->where('is_confirmed', true)
            ->with(['transaction'])
            ->latest()
            ->paginate(10);

        return view('frontend.expertsystem.experthistory.index', compact('histories'));
    }

    public function show($id)
    {
        $history = RecommendationHistory::where('user_id', Auth::id())
            ->where('is_confirmed', true)
            ->with(['transaction.transactionDetails.product'])
            ->findOrFail($id);

        return view('frontend.expertsystem.experthistory.details', compact('history'));
    }
}
