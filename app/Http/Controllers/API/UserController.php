<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::latest()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
        'name' =>'required|string|max:191',
        'email' =>'required|string|max:191|unique:users',
        'password' =>'required|string|max:191|min:6',
        ]);
        return User::create([
            'name' =>$request->input('name'),
            'email' =>$request->input('email'),
            'type' =>$request->input('type'),
            'password' =>Hash::make($request->input('password')),
            'bio' =>$request->input('bio'),
            'photo' =>$request->input('photo'),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $this->validate($request,[
            'name' =>'required|string|max:191',
            'email' =>'required|string|max:191|unique:users,email,'.$user->id,
            'password' =>'sometimes|string|max:191|min:6',
        ]);
        $user->update([
            'name' =>$request->input('name'),
            'email' =>$request->input('email'),
            'type' =>$request->input('type'),
            'password' =>Hash::make($request->input('password')),
            'bio' =>$request->input('bio'),
            'photo' =>$request->input('photo'),
        ]);
        return ['message','Successfully Updated'];

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return ['message','User Deleted'];
    }
}
