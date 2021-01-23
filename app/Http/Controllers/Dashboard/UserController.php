<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;



class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:users_read'])->only('index');
        $this->middleware(['permission:users_update'])->only('edit');
        $this->middleware(['permission:users_create'])->only('create');
        $this->middleware(['permission:users_delete'])->only('destroy');
    } 
    // end of constructor
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       
        $users = User::whereRoleIs('admin')->where( function($q) use ($request) {
            return $q->when($request->search,function($query) use($request){

                return $query->where('first_name','like','%'.$request->search.'%')
                ->orWhere('last_name','like','%'.$request->search.'%');
            });
        })->latest()->paginate(8);

        return view('dashboard.users.index',compact('users'));
        
    }//end of index

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.users.create');
    }//end of create

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'required|unique:users',
            'image'=>'image',
            'password'=>'required|confirmed',
            'permissions'=>'required|min:1',
        ]);

        $request_data=$request->except(['password','password_confirmation','permissions','image']);
        $request_data['password']=bcrypt($request->password);

        if ($request->image) {
            Image::make($request->image)
                ->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save(public_path('uploads/user_images/' . $request->image->hashName()));
            $request_data['image']=$request->image->hashName();
        }

        $user=User::create($request_data);
        $user->attachRole('admin');
        $user->syncPermissions($request->permissions);

        session()->flash('success',__('site.added_successfuly'));
        return redirect()->route('dashboard.users.index');
    }

 
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('dashboard.users.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>['required',Rule::unique('users')->ignore($user->id),],
            'image'=>'image',
            'permissions'=>'required|min:1',
        ]);

        $request_data=$request->except(['permissions','image']);

        if ($request->image) {
            
            if ($user->image != 'default.png') {
                
                Storage::disk('public_uploads')->delete('/user_images/' . $user->image);

            } // end of inner if
           
            Image::make($request->image)
                ->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save(public_path('uploads/user_images/' . $request->image->hashName()));
            $request_data['image']=$request->image->hashName();
            
        } // end of if
 

        $user->update($request_data);
        $user->syncPermissions($request->permissions);
        session()->flash('success',__('site.updated_successfuly'));
        return redirect()->route('dashboard.users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->image != 'default.png') {
            Storage::disk('public_uploads')->delete('/user_images/' . $user->image);
        }

        $user->delete();
        session()->flash('success',__('site.deleted_successfuly'));
        return redirect()->route('dashboard.users.index');
    }
}
