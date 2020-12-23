<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function index()
    {
        //$authId = Auth::user()->id;
        $authId = 2;
        $products = Product::where('user_id', '!=', $authId)->get();

        $response = [
            'message' => 'successfully get products',
            'status' => true,
            'data' => ProductResource::collection($products)
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    public function me()
    {
        $authId = 2;
        $products = Product::where('user_id', $authId)->get();

        $response = [
            'message' => 'successfully get products',
            'status' => true,
            'data' => ProductResource::collection($products)
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    public function show($slug)
    {   
        $product = Product::where('slug', $slug)->first();
        if (!$product) {
            $response = [
                'message' => 'product not found',
                'status' => false,
                'data' => (object)[]
            ];
    
            return response()->json($response, Response::HTTP_NOT_FOUND);
        }
        $response = [
            'message' => 'successfully one product',
            'status' => true,
            'data' => ProductResource::make($product)
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    public function search($name)
    {
        $products = Product::where('name', 'like', '%'.$name.'%')->get();

        $response = [
            'message' => 'successfully get products',
            'status' => true,
            'data' => ProductResource::collection($products)
        ];

        return response()->json($response, Response::HTTP_OK);
        
    }

    public function store()
    {
        $rules = [
            'name'          => ['required', 'min:5'],
            'description'   => ['required', 'string'],
            'price'         => ['required', 'numeric'],
            'qty'           => ['required', 'numeric'],
            'image'         => ['image', 'mimes:png,jpg,peg']
        ];

        $message = [
            'required'  => ':attribute tidak boleh kosong',
            'min'       => ':attribute minimal :min',
            'numeric'   => ':attribute harus berupa angka',
            'mimes'     => ':attribute harus bertype :mimes',
            'image'     => ':attribute harus gambar'
        ];

        $customAttributes = [
            'name'          => 'nama produk',
            'description'   => 'deskripsi produk',
            'price'         => 'harga produk',
            'qty'           => 'kuantitas produk',
            'image'         => 'foto atau gambar'
        ];

        $validator = Validator::make(request()->all(), $rules, $message, $customAttributes);
        if ($validator->fails()) {
            $response = [
                'message' => $validator->errors(),
                'status' => false,
                'data' => (object)[]
            ];
    
            return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
        }


        $authId = 3;
        Product::create([
            'user_id'       => $authId,
            'name'          => request('name'),
            'slug'          => Product::createSlug(request('name')),
            'description'   => request('description'),
            'price'         => request('price'),
            'qty'           => request('qty'),
            'image'         => request('image')->store('images/products')
        ]);

        $response = [
            'message' => 'create product successfully',
            'status' => true,
            'data' => (object)[]
        ];

        return response()->json($response, Response::HTTP_CREATED);
    }

    public function update(Product $product)
    {
        $authId = 3;
        $product->update([
            'user_id'       => $authId,
            'name'          => request('name') ?? $product->name,
            'slug'          => request('name') ? $product->createSlug(request('name')) : $product->slug, 
            'description'   => request('description') ?? $product->description,
            'price'         => request('price') ?? $product->price,
            'qty'           => request('qty') ?? $product->qty,
            'image'         => request('image') ? request('image')->store('images/products') : $product->image
        ]);

        $response = [
            'message' => 'update product successfully',
            'status' => true,
            'data' => (object)[]
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    public function delete(Product $product)
    {
        $product->delete();
        $response = [
            'message' => 'delete product successfully',
            'status' => true,
            'data' => (object)[]
        ];

        return response()->json($response, Response::HTTP_OK);
    }
    
}
