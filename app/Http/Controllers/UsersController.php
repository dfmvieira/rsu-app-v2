<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $you = auth()->user()->id;
        $users = DB::table('users')
        ->leftjoin('entities', 'users.IDEntity', '=', 'entities.id')
        ->select('users.id', 'users.name', 'users.email', 'users.phone', 'users.password','users.menuroles as roles', 'users.status', 'users.email_verified_at as registered','entities.name as entity')
        ->whereNull('deleted_at')
        ->get();


        return response()->json( compact('users', 'you') );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function username()
    {
        $user = auth()->user();

        $entityName = DB::table('entities')
            ->select('name')
            ->where('id', '=', $user['IDEntity'])
            ->get();

        $user['entityName'] = $entityName[0]->name;
        

        return response()->json($user, 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function usersbyentity()
    {
        $user = auth()->user();
        $entityId = isset($user->IDEntity) ? $user->IDEntity : $response['error'] = "Can't get user entity";

        if (isset($response['error'])) {
            return response()->json($response, 401);
        }

        if (!$user->hasRole('admin')) {
            $users = DB::table('users')
                ->where('IDEntity', '=', $entityId)
                ->wherenull('deleted_at')
                ->get();
        } else {
            $users = DB::table('users')
                ->wherenull('deleted_at')
                ->get();
        }

        return response()->json($users, 200);
    }

    /**
     * Display a listing of the techniciansOfEntity.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function techniciansOfEntity() {
        $user = auth()->user();
        $entityId = isset($user->IDEntity) ? $user->IDEntity : $response['error'] = "Can't get user entity";

        if (isset($response['error'])) {
            return response()->json($response, 401);
        }

        if (!$user->hasRole('admin')) {
            $users = DB::table('users')
                ->where('menuroles', 'LIKE', '%technician%')
                ->where('IDEntity', '=', $entityId)
                ->wherenull('deleted_at')
                ->get();
        } else {
            $users = DB::table('users')
                ->where('menuroles', 'LIKE', '%technician%')
                ->wherenull('deleted_at')
                ->get();
        }

        return response()->json($users, 200);
    }

    public function getRolesOfUser() {
        $user = auth()->user();

        return response()->json($user->menuroles, 201);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'integer', 'max:9'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'entity' => ['required']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\User
     */
    protected function create(Request $request)
    {
        return User::create([
            'name' => $request['name'],
            'phone' => $request['phone'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'IDEntity' => $request['entity'],
            'menuroles' => $request['role'],
            'status' => 'Active'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = DB::table('users')
        ->select('users.id', 'users.name', 'users.email', 'users.phone', 'users.password', 'users.menuroles as roles', 'users.IDEntity', 'users.status', 'users.email_verified_at as registered')
        ->where('users.id', '=', $id)
        ->first();
        return response()->json( $user );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $user = DB::table('users')
        ->select('users.id', 'users.name', 'users.email', 'users.phone', 'users.password', 'users.menuroles as roles', 'users.IDEntity', 'users.status')
        ->where('users.id', '=', $id)
        ->first();
        return response()->json( $user );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name'       => 'required|min:1|max:256',
            'email'      => 'required|email|max:256',
            'phone'      => 'required|max:9'
        ]);

        $user = User::find($id);
        $user->name       = $request->name;
        $user->email      = $request->email;
        $user->phone      = $request->phone;
        
        if (isset($request->password)) {
            $user->password = Hash::make($request->password);
        }

        if (isset($request->entity)) {
            $user->IDEntity = $request->entity;
        }

        if (isset($request->role)) {
            $user->menuroles = $request->role;
        }

        $user->save();
        //$request->session()->flash('message', 'Successfully updated user');
        return response()->json( ['status' => 'success'] );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if($user){
            $user->delete();
        }
        return response()->json( ['status' => 'success'] );
    }
}
