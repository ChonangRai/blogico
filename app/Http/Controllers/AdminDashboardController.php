<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class AdminDashboardController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        // $posts = Post::orderBy('created_at','desc')->paginate(10);
        return view('admin-dashboard')->with('posts',$user->posts);
    }
}
