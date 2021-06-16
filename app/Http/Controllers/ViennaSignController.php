<?php

namespace App\Http\Controllers;

use App\Models\ViennaSign;
use App\Services\RolesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;


class ViennaSignController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        /* $this->middleware('auth:api'); */
        /* $this->middleware('auth:admin'); */
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       
        $signs = DB::table('vienna_signs')
        ->leftjoin('vienna_sign_categories', 'vienna_signs.IDCategory', '=', 'vienna_sign_categories.id')
        ->select('vienna_signs.id', 'vienna_signs.name', 'vienna_sign_categories.category', 'vienna_signs.image')
        ->get();

        return response()->json($signs);
        
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
        $request->validate([
            

        ]);

        $sign = new sign();
        $sign->fill($request->all());
        $sign->save();

        return response()->json($sign, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ViennaSign  $viennaSign
     * @return \Illuminate\Http\Response
     */
    public function show(ViennaSign $viennaSign)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ViennaSign  $viennaSign
     * @return \Illuminate\Http\Response
     */
    public function edit(ViennaSign $viennaSign)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ViennaSign  $viennaSign
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ViennaSign $viennaSign)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ViennaSign  $viennaSign
     * @return \Illuminate\Http\Response
     */
    public function destroy(ViennaSign $viennaSign)
    {
        //
    }

    /**
     * Display a listing of the signs categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSignsCategories() {
        $categories = DB::table('vienna_sign_categories')
        ->select('vienna_sign_categories.id', 'vienna_sign_categories.name')
        ->get();

        return response()->json($categories);
    }
}
