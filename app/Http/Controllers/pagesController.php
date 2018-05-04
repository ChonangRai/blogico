<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Post;

class pagesController extends Controller
{
    public function index(){
    	$posts=Post::where('published',1)->latest()->take(8)->get();
    	// return $post;
    	return view('pages.index')->with('posts',$posts);

    }
    public function about(){
    	return view('pages.about');
    }
    public function services(){
    	return view('pages.services');
    }
}
