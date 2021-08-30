<?php

namespace App\Http\Controllers;

use App\Models\DeployGroup;
use Illuminate\Http\Request;
use App\Models\UsersDeployGroups;
use App\Models\SignsDeployGroups;

class DeployGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $deploygroup = new DeployGroup();
        $deploygroup->name = $request['name'];
        $deploygroup->notes = $request['notes'];
        $deploygroup->save();
        
        foreach ($request['users'] as $user) {
            $usersDeployGroups = new UsersDeployGroups();
            $usersDeployGroups->IDUser = $user;
            $usersDeployGroups->IDDeployGroup = $deploygroup->id;
            $usersDeployGroups->save();
        }
        
        foreach($request['signs'] as $sign) {
            $signsDeployGroups = new SignsDeployGroups();
            $signsDeployGroups->IDIviSign = $sign;
            $signsDeployGroups->IDDeployGroup = $deploygroup->id;
            $signsDeployGroups->save();
        }


        return response()->json(['message' => 'Deploy group has been created'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DeployGroup  $deployGroup
     * @return \Illuminate\Http\Response
     */
    public function show(DeployGroup $deployGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DeployGroup  $deployGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(DeployGroup $deployGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DeployGroup  $deployGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DeployGroup $deployGroup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DeployGroup  $deployGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeployGroup $deployGroup)
    {
        //
    }
}
