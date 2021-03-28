<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Image;
use App\Role;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $images = Image::orderBy('id', 'desc')->paginate(5);
        $user = Auth::user();
        $role = $user->role->name;
        return view('home', [
            'images' => $images,
            'role'   => $role,
            'user'   => $user
        ]);
    }
}
