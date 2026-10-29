<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        $products   = $query->orderBy('code')->paginate(10);
        $categories = Category::orderBy('name')->get();
        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $units      = ['kg','gram','liter','ml','pack','pcs','botol','karton','sachet'];
        return view('products.create', compact('categories', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'          => 'required|string|unique:products,code',
            'category_id'   => 'required|exists:categories,id',
            'name'          => 'required|string|max:255',
            'unit'          => 'required|string',
            'stock'         => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'description'   => 'nullable|string',
        ]);

        Product::create($request->only(
            'code','category_id','name','unit','stock','minimum_stock','description'
        ));

        return redirect()->route('products.index')
                         ->with('success', 'Bahan baku berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        $product->load('category');
        $recentTx = $product->stockTransactions()
                    ->with('user')->orderByDesc('transaction_date')->take(5)->get();
        return view('products.show', compact('product', 'recentTx'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $units      = ['kg','gram','liter','ml','pack','pcs','botol','karton','sachet'];
        return view('products.edit', compact('product', 'categories', 'units'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'code'          => 'required|string|unique:products,code,' . $product->id,
            'category_id'   => 'required|exists:categories,id',
            'name'          => 'required|string|max:255',
            'unit'          => 'required|string',
            'stock'         => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'description'   => 'nullable|string',
        ]);

        $product->update($request->only(
            'code','category_id','name','unit','stock','minimum_stock','description'
        ));

        return redirect()->route('products.index')
                         ->with('success', 'Bahan baku berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->stockTransactions()->count() > 0) {
            return redirect()->route('products.index')
                             ->with('error', 'Bahan tidak bisa dihapus karena memiliki riwayat transaksi.');
        }
        $product->delete();
        return redirect()->route('products.index')
                         ->with('success', 'Bahan baku berhasil dihapus.');
    }
}