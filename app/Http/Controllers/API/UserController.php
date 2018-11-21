<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use File;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){
        $this->middleware('auth:api');
    }


    public function index()
    {
        // $this->authorize('isAdmin');
        if (\Gate::allows('isAdmin') || \Gate::allows('isAuthor')) {
            return User::latest()->paginate(1);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('isAdmin');
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

    //profile function to send current user data
    public function profile(Request $request)
    {
        return auth('api')->user();
    }



    public function updateProfile(Request $request){
        ///for api authenticated users
        $user = auth('api')->user();
        //if user didnot uploaded a new image
        if($request->photo != $user->photo){
                //code to change base64 code image name to timestamp name
                $name = time().'.' .explode('/',explode(':',substr($request->photo,0,strpos($request->photo,';')))[1])[1];
                //use image intervention to convert base64 image to proper image and save it to folder
                \Image::make($request->photo)->save(public_path('img/profile/').$name);
                //update the value in request photo
                $request->merge(['photo'=>$name]);
                //get the current photo with full dir
                $userPhoto = public_path('img/profile/').$user->photo;
                //check if the file exists
                if(file_exists($userPhoto)){
                    //delete the file
                    unlink($userPhoto);
                }

        }

        $this->validate($request,[
            'name' =>'required|string|max:191',
            'email' =>'required|string|max:191|unique:users,email,'.$user->id,
            'password' =>'sometimes|string|max:191|min:6',
        ]);

        //if the password is not empty
        if(!empty($request->password)){
            //change it to hashed password
            $request->merge(['password'=>
            Hash::make($request->password)
            ]);
        }
        $user->update($request->all());
        return ['message','Successfully Updated'];
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
        $this->authorize('isAdmin');
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
        $this->authorize('isAdmin');
        $user = User::findOrFail($id);
        $user->delete();
        return ['message','User Deleted'];
    }
}
