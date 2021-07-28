<?php

namespace App\Http\Controllers;

use App\Models\IviSignMap;
use Illuminate\Http\Request;

class IviSignMapController extends Controller
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
        
        $iviSign = new IviSign()
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\IviSignMap  $iviSignMap
     * @return \Illuminate\Http\Response
     */
    public function show(IviSignMap $iviSignMap)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\IviSignMap  $iviSignMap
     * @return \Illuminate\Http\Response
     */
    public function edit(IviSignMap $iviSignMap)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\IviSignMap  $iviSignMap
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IviSignMap $iviSignMap)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\IviSignMap  $iviSignMap
     * @return \Illuminate\Http\Response
     */
    public function destroy(IviSignMap $iviSignMap)
    {
        //
    }
}
