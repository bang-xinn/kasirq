<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('sku', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('sort')) {
            if ($request->sort === 'name_asc') {
                $query->orderBy('name', 'asc');
            } elseif ($request->sort === 'sku_asc') {
                $query->orderBy('sku', 'asc');
            } elseif ($request->sort === 'stock_desc') {
                $query->orderBy('stock', 'desc');
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::all();

        return view('products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'unique:products,sku'],
            'price' => ['required', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $product): View
    {
        $categories = Category::all();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', "unique:products,sku,{$product->id}"],
            'price' => ['required', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', false);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function generateSku(Request $request): \Illuminate\Http\JsonResponse
    {
        $category = Category::find($request->category_id);
        if (!$category) {
            return response()->json(['sku' => '']);
        }

        $prefixMap = [
            'makanan' => 'MKN',
            'minuman' => 'MNM',
            'snack & camilan' => 'SNK',
        ];

        $catName = strtolower($category->name);
        $prefix = $prefixMap[$catName] ?? strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $category->name), 0, 3));
        
        if (strlen($prefix) < 3) {
            $prefix = str_pad($prefix, 3, 'X');
        }

        // Find last SKU with this prefix
        $lastProduct = Product::where('sku', 'like', "{$prefix}-%")->orderBy('sku', 'desc')->first();

        if ($lastProduct) {
            $lastNumber = intval(substr($lastProduct->sku, 4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $newSku = $prefix . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        return response()->json(['sku' => $newSku]);
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }
}
