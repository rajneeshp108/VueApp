<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
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
        $this->validate($request, [
            'name'     => 'required|string|max:191',
            'email'    => 'required|string|max:191|email|unique:users',
            'password' => 'required|string|min:6|max:20',
        ]);

        $data['name']     = $request->name;
        $data['email']    = $request->email;
        $data['password'] = \Hash::make($request->password);
        $data['type']     = $request->type;
        $data['bio']      = $request->bio;
        $data['photo']    = $request->photo;

        return User::create($data);
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

        $this->validate($request, [
            'name'     => 'required|string|max:191',
            'email'    => 'required|string|max:191|email|unique:users,email,'.$user->id,
            'password' => 'sometimes|min:6|max:20',
        ]);


        User::where('id', $id)->update($request->all());

        // dd($request->all());
        return ['message' => 'User updated successfully'];
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

        // delete user
        $user->delete($id);

        return ['message' => 'User deleted successfully.'];
    }

    /* ***************************** */
    /* function to show user profile */
    /* ***************************** */
    public function profile()
    {
        return auth('api')->user();
    }
    
    /* ******************************* */
    /* function to update user profile */
    /* ******************************* */
    public function updateProfile(Request $request)
    {
        $user = auth('api')->user();

        $this->validate($request, [
            'name'     => 'required|string|max:191',
            'email'    => 'required|string|max:191|email|unique:users,email,'.$user->id,
            'password' => 'sometimes|min:6|max:20',
        ]);

        $currentPhoto = $user->photo;

        if($request->photo != $currentPhoto){
            $name = time() . mt_rand(1,time()) . '.' . explode('/', explode(':', substr($request->photo, 0, strpos( $request->photo, ';' )))[1])[1] ;
            
            \Image::make($request->photo)->save(public_path('img/profile/').$name);

            $request->merge(['photo' => $name]);

            $prevPhoto = public_path('img/profile/').$currentPhoto;

            if(file_exists($prevPhoto)){
                @unlink($prevPhoto);
            }
        }

        if(!empty($request->password)){
            $request->merge(['password' => \Hash::make($request['password'])]);
        }

        User::where('id', $request->id)->update($request->all());

        return ['message' => 'User Updated Successfully.'];
    }
}
