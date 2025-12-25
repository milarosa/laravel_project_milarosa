<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('kategori')->when(request('search'), function ($query) {
            $query->where('nama', 'like', '%' . request('search') . '%');
        })->paginate(10);
        return view('product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('product.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric',
            'deskripsi' => 'required|string',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'kategori_id' => 'required|exists:categories,id',
        ]);
        $fotoPath = $request->file('foto')->store('foto', 'public');
        Product::create([
            'nama' => $request->nama,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            'kategori_id' => $request->kategori_id,
            'foto' => $fotoPath,
        ]);
        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('product.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'stok' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'kategori_id' => 'required|exists:categories,id',
        ]);
        $data = $request->except('foto');
        if ($request->hasFile('foto')) {
            if ($product->foto && file_exists(public_path('storage/' . $product->foto))) {
                unlink(public_path('storage/' . $product->foto));
            }
            $data['foto'] = $request->file('foto')->store('produk', 'public');
        }
        $product->update($data);
        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
