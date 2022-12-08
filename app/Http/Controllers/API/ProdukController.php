<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Produk;
use App\Http\Resources\ProdukResource;

class ProdukController extends Controller
{
    public function index()
    {
        $data = Produk::latest()->get();
        return response()->json([ProdukResource::collection($data), 
        'Programs fetched.']);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
        'kode_produk' => 'required|string|max:255',
        'nama_produk' => 'required|string|max:255',
        'harga' => 'required'
        ]);
        if($validator->fails()){
        return response()->json($validator->errors()); 
        }

        $produk = Produk::create([
            'kode_produk' => $request->kode_produk,
            'nama_produk' => $request->nama_produk,
            'harga' => $request->harga
        ]);

        return response()->json(['Produk created successfully.', new
        ProdukResource($produk)]);
    }

    public function show($id)
    {
        $produk = Produk::find($id);
        if (is_null($produk)) {
        return response()->json('Data not found', 404); 
        }
        return response()->json([new ProdukResource($produk)]);
    }

    public function update(Request $request, Produk $produk)
    {
    $validator = Validator::make($request->all(),[
        'kode_produk' => 'required|string|max:255',
        'nama_produk' => 'required|string|max:255',
        'harga' => 'required'
    ]);
    if($validator->fails()){
    return response()->json($validator->errors()); 
    }
        $produk->kode_produk = $request->kode_produk;
        $produk->nama_produk = $request->nama_produk;
        $produk->harga = $request->harga;
        $produk->save();

    return response()->json(['Produk updated successfully.', new
    ProdukResource($produk)]);
    }
   
    public function destroy(Produk $produk)
    {
        $produk->delete();
        return response()->json('Produk deleted successfully');
    }
}