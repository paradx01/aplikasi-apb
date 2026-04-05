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

        return view('frontend.details', [
            'product' => $product,
            'medication_rules' => $product->medicationRules
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
