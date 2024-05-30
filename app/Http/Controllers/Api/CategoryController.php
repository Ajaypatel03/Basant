<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class CategoryController extends Controller
{
    
    public function index()
    {
        return CategoryResource::collection(Category::all());
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
            "category_name" => 'required|string',
            "image" => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Save the image to the storage/app/public directory
        // $imageName = time() . '.' . $request->image->extension();
        // $request->image->storeAs('public', $imageName);

        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('images'), $imageName);
        // Create the category$category entry
        $category = Category::create([
            "category_name" => $request->category_name,
            "image" => $imageName,
        ]);

        return response()->json([
        "status" => true,
        "message" => "Category Created Successfully !",
        "data" => $category
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return CategoryResource::make($category);
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
    public function update(Request $request, Category $category)
    {
        // Check if the blog exists
        if (!$category) {
            abort(404);
        }

        if ($request->hasFile('image')) {
            $imagePath = public_path('images/' . $category->image);
            // Check if the image exists and delete it
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
            // Storage::delete('public/' . $category->image);
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $category->image = $imageName;
        }
        // Update the category fields
        $category->category_name = $request->category_name;

        // Call the update method without parentheses
        $category->update();

        return response()->json([
        'status' => true,
        'message' => 'Category Updated successfully!',
        'post' => $category
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Construct the full path to the image
        $imagePath = public_path('images/' . $category->image);

        // Check if the image exists and delete it
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }

        // Delete the category$category entry
        $category->delete();

        return response()->json([
            'status' => true,
            'message' => "Category deleted successfully!",
        ], 200);
    }
}