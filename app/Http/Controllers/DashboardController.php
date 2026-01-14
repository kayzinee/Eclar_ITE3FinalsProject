<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();

        // detect available columns
        $qtyCol = Schema::hasColumn('products', 'quantity')
            ? 'quantity'
            : 'stock';

        $lowStock = Product::where($qtyCol, '<', 5)->count();
        $inventoryValue = Product::sum(DB::raw('COALESCE(price,0) * COALESCE(' . $qtyCol . ',0)')) ?? 0;
        $reorderRate = $totalProducts ? round(($lowStock / $totalProducts) * 100, 0) : 0;

        // Load categories with products for overview table
        $categories = Category::withCount('products')
            ->with(['products' => function($q) use ($qtyCol) {
                $q->select('id', 'category_id', 'name', $qtyCol);
            }])
            ->get();

        // Load all products with categories for the products section
        $allProducts = Product::with('category')->get();

        return view('dashboard', compact('totalProducts', 'totalCategories', 'lowStock', 'inventoryValue', 'reorderRate', 'categories', 'qtyCol', 'allProducts'));
    }
}
