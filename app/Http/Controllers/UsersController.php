<?php

namespace App\Http\Controllers;

use Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:users.index')->only('index'); 
        $this->middleware('can:users.create')->only('create', 'store');
        $this->middleware('can:users.edit')->only('edit', 'update');
        $this->middleware('can:users.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::All();

        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = array();
        $email = array();
        $message = array();

        $name_exist = User::where('name', $request->user_name)->exists();
        $email_exist = User::where('email', $request->user_email)->exists();

        if ($name_exist) {
            $name = [
                'name' => 'User already exist'
            ];
        } 
        if ($email_exist) {
            $emil = [
                'email' => 'Email already exist'
            ];
        }
        
        $message = array_merge($name, $email);
        if (count($message)) {
            return back()->withErrors($message)->withInput();
        }
        
        $user = new User;
        $user->name = $request->user_name;
        $user->email = $request->user_email;
        $user->password = bcrypt($request->user_pass);

        if($request->user_files){
            if($request->user_files[0] != null){
                $directory = "img/users/";
                if(!file_exists($directory)){
                    mkdir($directory, 0777);
                }
        
                foreach ($request->user_files as $key => $tmp_name) {
                    $filename = $request->user_files[$key]->getClientOriginalName();      
                    $dir = opendir($directory);    
                    if($request->user_files[$key]->move($directory, $filename)){
                            $user->profile_photo_path = $directory.$filename;
                    }
        
                    closedir($dir);
        
                }
            }
        }

        $user->save();
        $user->roles()->sync($request->user_role);

        return redirect()->route('users.index')->with('info', 'A new user has been added')->send();
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
        $role = Role::where('name', $request->user_role)->value('id');
        $user = User::find($id);
        $user->name = $request->user_name;
        $user->email = $request->user_email;
        if($request->user_pass){
            $user->password = bcrypt($request->user_pass);
        }    

        if($request->user_files){
            if($user->profile_photo_path){
                unlink($user->profile_photo_path);
            }   

            if($request->user_files[0] != null){
                $directory = "img/users/";
        
                foreach ($request->user_files as $key => $tmp_name) {
                    $filename = $request->user_files[$key]->getClientOriginalName();      
                    $dir = opendir($directory);    
                    if($request->user_files[$key]->move($directory, $filename)){
                            $user->profile_photo_path = $directory.$filename;
                    }
        
                    closedir($dir);        
                }
            }
        }

        $user->save();
        $user->roles()->sync($role);

        return redirect()->route('users.index')->with('info', 'The user has been edited')->send();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect()->route('users.index')->with('info', 'The user has been deleted')->send();
    }
}
