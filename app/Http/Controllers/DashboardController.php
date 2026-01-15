<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $inventoryValue = Product::sum(
            DB::raw('COALESCE(price,0) * COALESCE(' . $qtyCol . ',0)')
        ) ?? 0;

        $reorderRate = $totalProducts
            ? round(($lowStock / $totalProducts) * 100, 0)
            : 0;

        $categories = Category::withCount('products')
            ->with(['products' => function ($q) use ($qtyCol) {
                $q->select('id', 'category_id', 'name', $qtyCol);
            }])
            ->get();

        $allProducts = Product::with('category')->get();

        return view(
            'dashboard',
            compact(
                'totalProducts',
                'totalCategories',
                'lowStock',
                'inventoryValue',
                'reorderRate',
                'categories',
                'qtyCol',
                'allProducts'
            )
        );
    }

    public function exportPdf(Request $request)
    {
        // detect stock column (IMPORTANT)
        $qtyCol = Schema::hasColumn('products', 'quantity')
            ? 'quantity'
            : 'stock';

        $search = $request->search;
        $category = $request->category;

        $products = Product::with('category')
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->when($category, function ($q) use ($category) {
                $q->whereHas('category', function ($c) use ($category) {
                    $c->where('name', $category);
                });
            })
            ->latest()
            ->get();

        $total_value = $products->sum(function ($product) use ($qtyCol) {
            return $product->price * $product->{$qtyCol};
        });

        $low_stock_count = $products
            ->where($qtyCol, '<', 5)
            ->count();

        $pdf = Pdf::loadView(
            'pdfs.products',
            compact('products', 'total_value', 'low_stock_count', 'qtyCol')
        )
            ->setPaper('a4', 'landscape')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isPhpEnabled', true);

        $filename = 'products_' . now()->format('Y-m-d_H-i-s') . '.pdf';

        return $pdf->download($filename);
    }
}
