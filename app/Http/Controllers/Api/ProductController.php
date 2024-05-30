<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ProductResource::collection(Product::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "product_name" => 'required|string',
            "category_id" => 'required',
            "total_unit" => 'required',
            "image" => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            "price" => 'required',
        ]);

        // Save the image to the storage/app/public directory
        // $imageName = time() . '.' . $request->image->extension();
        // $request->image->storeAs('public', $imageName);

        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('images'), $imageName);
        // Create the category$category entry
        $product = Product::create([
            "product_name" => $request->product_name,
            "image" => $imageName,
            "category_id" => $request->category_id,
            "total_unit" => $request->total_unit,
            "sold_unit" => $request->sold_unit,
            "available_unit" => $request->available_unit,
            "price" => $request->price,
        ]);

        return response()->json([
            "status" => true,
            "message" => "Product Added Successfully !",
            "data" => $product
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return ProductResource::make($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Check if the blog exists
        if (!$product) {
        abort(404);
        }

        if ($request->hasFile('image')) {
            $imagePath = public_path('images/' . $product->image);
            // Check if the image exists and delete it
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
            // Storage::delete('public/' . $product->image);
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $product->image = $imageName;
        }
        // Update the product fields
        $product->product_name = $request->product_name;
        $product->category_id = $request->category_id;
        $product->total_unit = $request->total_unit;
        $product->sold_unit = $request->sold_unit;
        $product->available_unit = $request->available_unit;
        $product->price = $request->price;

        // Call the update method without parentheses
        $product->update();

        return response()->json([
            'status' => true,
            'message' => 'Product Updated successfully!',
            'post' => $product
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Construct the full path to the image
        $imagePath = public_path('images/' . $product->image);

        // Check if the image exists and delete it
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }

        // Delete the product entry
        $product->delete();

        return response()->json([
            'status' => true,
            'message' => "Product deleted successfully!",
        ], 200);
    }
}