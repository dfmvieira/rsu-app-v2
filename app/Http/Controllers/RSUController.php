<?php

namespace App\Http\Controllers;

use App\Models\RSU;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class RSUController extends Controller
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
        $rsu = new RSU();
        $rsu->name = $request->name;
        $rsu->serialNumber = $request->serialNumber;
        $rsu->range = $request->range;
        $rsu->hardwareDetails = $request->hardwareDetails;

        $rsu->save();


        DB::table('ivi_signs_map')
            ->where('id', '=', $request->signId)
            ->update(['IDRSU' => $rsu->id]);

        $rsu['message'] = 'Rsu has been Created and Assigned to a Ivi Sign';

        return response()->json($rsu, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  integer $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $rsu = DB::table('rsu')
            ->where('id', '=', $id)
            ->get();

        return response()->json($rsu, 201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RSU  $rSU
     * @return \Illuminate\Http\Response
     */
    public function edit(RSU $rSU)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RSU  $rSU
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RSU $rSU)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RSU  $rSU
     * @return \Illuminate\Http\Response
     */
    public function destroy(RSU $rSU)
    {
        //
    }
}
