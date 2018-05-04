<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Post;
use DB;

class postsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth',['except'=>['index','show']]); //index and show page can be viewed by anyone
    }
    public function index()
    {
        // $posts = Post::orderBy('created','desc')->take(10)->get(); //->take(10) will show 10 results only
        //pagination
        $posts = Post::where('published',1)->orderBy('created_at','desc')->paginate(10);
        return view('posts.index')->with('posts',$posts);
    }

    public function create()
    {
        return view('posts.create');
    }
    
    public function store(Request $request)
    {
        $this->validate($request,[
            'ico_name'=>'required',
            'cover_pic'=>'image|max:1999' // image validation
        ]);
        //Handle file upload
        if($request->hasFile('cover_pic')){
            //Get file name with extension
            $filenameWithExt = $request->file('cover_pic')->getClientOriginalName();
            //Get just file name
            $filename = pathinfo($filenameWithExt,PATHINFO_FILENAME);
            //Get just extension
            $extension=$request->file('cover_pic')->getClientOriginalExtension();
            //file name to store
            $fileNameToStore=$filename.'_'.time().'.'.$extension;
            //upload image
            $path=$request->file('cover_pic')->storeAs('public/cover_images',$fileNameToStore);
        }else{
            $fileNameToStore='noimage.jpg'; 
        }
        //create Post
        $post=new Post;
        $post->application_type=$request->type;
        $post->title=$request->input('ico_name');
        $post->slogan=$request->input('slogan');
        $post->website_url=$request->input('web_url');
        $post->country_operate=$request->input('operation');
        $post->video_url=$request->input('video');
        $post->body=$request->input('intro_ico');
        $post->user_id = auth()->user()->id;
        $post->cover_pic=$fileNameToStore;

        $post->token_name=$request->input('token_name');
        $post->token_type=$request->input('token_type');
        $post->platform=$request->input('platform');
        $post->pre_price=$request->input('pre_price');
        $post->cur_price=$request->input('cur_price');
        $post->total_sale=$request->input('total_sale');
        $post->total_sold=$request->input('total_sold');
        $post->restricted = $request->input('restricted');
        $post->bounty_avail = $request->input('bounty_avail');
        $post->bounty_link = $request->input('bounty_link');
        $post->min_invest = $request->input('min_invest');

        $post->accepting = $request->input('accepting');
        $post->soft_cap = $request->input('soft_cap');
        $post->hard_cap = $request->input('hard_cap');
        $post->distributed = $request->input('distributed');
        $post->start_date= $request->input('start_date');
        $post->end_date= $request->input('end_date');
        $post->whitepaper=$request->input('whitepaper');
        $post->wlist=$request->input('wlist');
        $post->kyc=$request->input('kyc');

        $post->bonus_avail=$request->input('bonus_avail');
        $post->pre_bonus=$request->input('pre_bonus');
        $post->cur_bonus=$request->input('cur_bonus');
        // $post->category=$request->input('category');
        $post->fb_link=$request->input('fb_link');
        $post->btalk_link=$request->input('btalk_link');
        $post->twit_link=$request->input('twit_link');
        $post->git_link=$request->input('git_link');
        $post->med_link=$request->input('med_link');
        $post->tel_link=$request->input('tel_link');
        $post->red_link=$request->input('red_link');
        $post->contact_email=$request->input('contact_email');

        $post->save();

        return redirect ('/posts')->with('success','Post created');
}

    public function show($id)
    {
        $post = Post::find($id);
        return view('posts.show')->with('post',$post);
    }

    public function edit($id)
    {
        $post = Post::find($id);

        //Check for respective post creator
        if(auth()->user()->id!==$post->user_id){
            return redirect ('/posts')->with('error','You are not authorized');
        }
        return view ('posts.edit')->with('post',$post);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'cover_pic'=>'image|max:1999'
        ]);

        //create Post
        $post = Post::find($id);
        $post->application_type=$request->type;
        $post->title=$request->input('ico_name');
        $post->slogan=$request->input('slogan');
        $post->website_url=$request->input('web_url');
        $post->country_operate=$request->input('operation');
        $post->video_url=$request->input('video');
        $post->body=$request->input('intro_ico');
        $post->user_id = auth()->user()->id;

        $post->token_name=$request->input('token_name');
        $post->token_type=$request->input('token_type');
        $post->platform=$request->input('platform');
        $post->pre_price=$request->input('pre_price');
        $post->cur_price=$request->input('cur_price');
        $post->total_sale=$request->input('total_sale');
        $post->total_sold=$request->input('total_sold');
        $post->restricted = $request->input('restricted');
        $post->bounty_avail = $request->input('bounty_avail');
        $post->bounty_link = $request->input('bounty_link');
        $post->min_invest = $request->input('min_invest');

        $post->accepting = $request->input('accepting');
        $post->soft_cap = $request->input('soft_cap');
        $post->hard_cap = $request->input('hard_cap');
        $post->distributed = $request->input('distributed');
        $post->start_date= $request->input('start_date');
        $post->end_date= $request->input('end_date');
        $post->whitepaper=$request->input('whitepaper');
        $post->wlist=$request->input('wlist');
        $post->kyc=$request->input('kyc');

        $post->bonus_avail=$request->input('bonus_avail');
        $post->pre_bonus=$request->input('pre_bonus');
        $post->cur_bonus=$request->input('cur_bonus');
        // $post->category=$request->input('category');
        $post->fb_link=$request->input('fb_link');
        $post->btalk_link=$request->input('btalk_link');
        $post->twit_link=$request->input('twit_link');
        $post->git_link=$request->input('git_link');
        $post->med_link=$request->input('med_link');
        $post->tel_link=$request->input('tel_link');
        $post->red_link=$request->input('red_link');
        $post->contact_email=$request->input('contact_email');
        if($request->hasFile('cover_pic')){
            if($post->cover_pic!= 'noimage.jpg'){
                Storage::delete('public/cover_images'.$post->cover_pic);
            }
            $filenameWithExt = $request->file('cover_pic')->getClientOriginalName();
            //Get just file name
            $filename = pathinfo($filenameWithExt,PATHINFO_FILENAME);
            //Get just extension
            $extension=$request->file('cover_pic')->getClientOriginalExtension();
            //file name to store
            $fileNameToStore=$filename.'_'.time().'.'.$extension;
            //upload image
            $path=$request->file('cover_pic')->storeAs('public/cover_images',$fileNameToStore);
            $post->cover_pic = $fileNameToStore;
        }
        $post->save();

        return redirect('/posts')->with('success','Successfully updated');
    }

    public function destroy($id)
    {
        $post=Post::find($id);
        //Check for respective post creator
        if(auth()->user()->id!==$post->user_id){
            return redirect ('/posts')->with('error','You are not authorized');
        }

        if($post->cover_pic!='noimage.jpg'){
            //Delete image
            Storage::delete('public/cover_images/'.$post->cover_pic);
        }

        $post->delete();
        return redirect()->back()->with('success','Post deleted');
    }
    public function publish(Request $request, $id)
    {
        $post=Post::find($id);
        dd($request->publish);

        $post->published=$request->input('publish');
        $post->save();
        return redirect()->back();
    }

}
