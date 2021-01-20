<?php

namespace App\Http\Controllers;

use App\Models\Product;
use http\Env\Response;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return response()->json(Product::all());

    }

    public function show($id)
    {

        return response()->json(Product::find($id));
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required|string',
            'harga' => 'required|integer',
            'warna' => 'required|string',
            'kondisi' => 'required|in:baru,lama',
            'deskripsi' => 'string'
        ]);
        $data = $request->all();
        $product = Product::create($data);
        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $produk = Product::find($id);

        if(!$produk){
            return response()->json(['message'=>'Product Not Found'],404);
        }
        $this->validate($request, [
            'nama' => 'string',
            'harga' => 'integer',
            'warna' => 'string',
            'kondisi' => 'in:baru,lama',
            'deskripsi' => 'string'
        ]);


        $data = $request->all();
        $produk->fill($data);
        $produk->save();
        return response()->json($produk);

    }

    public function delete($id)
    {
        return response()->json(Product::destroy($id));

    }
}
