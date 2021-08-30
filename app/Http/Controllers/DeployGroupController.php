<?php

namespace App\Http\Controllers;

use App\Models\DeployGroup;
use Illuminate\Http\Request;

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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request);
        $deploygroup = new DeployGroup();
        $deploygroup->fill($request->all());
        $deploygroup->save();

        return response()->json($deploygroup, 201);

        
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
