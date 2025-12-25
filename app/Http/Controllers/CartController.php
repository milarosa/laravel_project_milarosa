<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Menampilkan isi keranjang
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }
    /**
     * Menambah produk ke keranjang
     */
    public function add(Product $product)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$product->id])) {
            $cart[$product->id] = [
                "nama" => $product->nama,
                "quantity" => 1,
                "harga" => $product->harga,
                "foto" => $product->foto
            ];
        }
        session()->put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Produk ditambahkan kekeranjang!');
    }
    /**
     * Menghapus produk dari keranjang
     */
    public function remove(Product $product)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            session()->put('cart', $cart);
        }
        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang!');
    }
    /**
     * Update jumlah produk di keranjang
     */
    public function update(Request $request, Product $product)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }
        return redirect()->route('cart.index')->with('success', 'Jumlah produk diperbarui!');
    }
}
