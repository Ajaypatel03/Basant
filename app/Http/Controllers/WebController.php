<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class WebController extends Controller
{
    public function index(){

        $products = Product::all();
        $categories = Category::all();
        return view('welcome',compact('categories','products'));

    }
}
