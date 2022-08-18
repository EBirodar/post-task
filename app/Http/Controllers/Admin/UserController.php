<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(){
//        $d=Carbon::now();
//        $d=\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', auth()->user()->created_at)->diffInMinutes(\Carbon\Carbon::now());
//        dd($d);
//        dd(Carbon::now()->diffInMinutes(auth()->user()->created_at));
        if(auth()->user()->type=='admin'){
            $data = User::with('posts')->paginate(10);
            return view('admin.users.index',['datas'=>$data]);
        }else{

            $data = User::where('name',auth()->user()->name)->with('posts')->paginate(10);
//
            return view("admin.users.index", ['datas' => $data]);
        }
    }

    public function create()
    {
        return view('admin.User.create');
    }

    /**
     * Store a newly created resource in storage.
     *
    //     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

//        dd($request->user_id);
        $post=new Post();
        $post->name=$request->name;
        $post->user_id=$request->user_id;
        $post->save();

//        Post::create($this->validateData());
        return redirect()->route('admin.users.index');



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $User
    //     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $comp=User::find($id);
        return view('admin.User.show',['product'=>$comp]);
    }

    /**
     * Show the form for editing the specified resource.
     *
    //     * @param  \App\Models\User  $User
    //     * @return \Illuminate\Http\Response
     */
    public function edit( $post)
    {
//        dd($post);
        $post=Post::find($post);
//        dd($post);

        if(auth()->user()->type=='admin'){
            $data = User::with('posts')->paginate(10);

            return view('admin.users.index',['datas'=>$data,'post'=>$post]);
        }else{
            $data = User::where('name',auth()->user()->name)->with('posts')->paginate(10);
            return view("admin.users.index", ['datas' => $data,'post'=>$post]);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserRequest  $request
     * @param  \App\Models\User  $User
    //     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $post)
    {

        $name=$this->validateData()['name'];
//        dd($name);
        $this->updateData($post,$name);

        return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $User
    //     * @return \Illuminate\Http\Response
     */
    public function destroy($post)
    {
//        dd($post);
        $deleted = DB::table('posts')->where('id',  $post)->delete();
        return redirect()->route('admin.users.index');
    }

    public function validateData()
    {
        return request()->validate([
            'name'=>'required'
        ]);

    }

    public function updateData($post,$name)
    {
        $affected = DB::table('posts')
            ->where('id', $post)
            ->update(['name'=>$name]);
    }
}
