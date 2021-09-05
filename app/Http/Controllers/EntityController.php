<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;

class EntityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entities = DB::table('entities')
        ->get();

        return response()->json($entities);
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

        if($request->logo['base64']) {

            $image = $request->logo;

            $base64_string = explode(',', $image['base64']);
            $imageBin = base64_decode($base64_string[1]);

            if (!Storage::disk('public')->exists('img/Entities/' . $image['name'])) {
                Storage::disk('public')->put('img/Entities/' . $image['name'], $imageBin);
            }
        }

        $entity = new Entity();
        $entity->name = $request['name'];
        $entity->phone = $request['phone'];
        $entity->address = $request['address'];
        $entity->logo = $request->logo['base64'] ? 'img/Entites/' . $request->logo['name'] : '';
        $entity->save();

        return response()->json($entity, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Entity  $entity
     * @return \Illuminate\Http\Response
     */
    public function show(Entity $entity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Entity  $entity
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $entity = Entity::findOrFail($id);

        $entity->update($request->all());
        return response()->json([
            'message'=>'Entity Updated Successfully!!',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Entity  $entity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Entity $entity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Entity  $entity
     * @return \Illuminate\Http\Response
     */
    public function destroy(Entity $entity)
    {
        //
    }
}
