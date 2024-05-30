<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::simplePaginate(10);
        $categories = Category::all();
        return view('Admin.product',compact('categories','products'));
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

        return redirect()->route('product.index')->with('success-alert', 'Product Added Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
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

        return redirect()->route('product.index')->with('success-alert','Product Deleted Successfully!');
    }
}