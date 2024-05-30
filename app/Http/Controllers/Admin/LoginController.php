<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF ;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.login');
    }

   
    public function create()
    {
        $categories = Category::all();
        return view('admin.dashboard',compact('categories'));
    }

    public function store(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            if (auth()->user()->role == 1) {
                return redirect()->route('login.create');
            } else {
                return redirect()->route('welcome');
            }
        } else {
            return back()->with('error', 'Invalid credentials');
        }
    }

  
    public function show(string $id)
    {
        //
    }

 
    public function edit(string $id)
    {
        //
    }

  
    public function update(Request $request, string $id)
    {
        //
    }

  
    public function destroy(string $id)
    {
        //
    }
}