<?php

namespace App\Http\Controllers;

use App\Models\IviSignMap;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IviSignMapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = auth()->user();
        $entityId = isset($user->IDEntity) ? $user->IDEntity : $response['error'] = "Can't get user entity";

        if (isset($response['error'])) {
            return response()->json($response, 401);
        }

        if ($user->hasRole('admin')) {
            $signs = DB::table('ivi_signs_map')->get();
        } else {
            $signs = DB::table('ivi_signs_map')->where('entityId', '=', $entityId)->get();
        }

        foreach($signs as $key => $sign) {

            // Put latitude and longitude in new object inside signs for markers in map
            $signs[$key]->coordinates = new \stdClass();
            $signs[$key]->coordinates->lat = $sign->latitude;
            $signs[$key]->coordinates->lng = $sign->longitude;


            // Remove old latitude and longitude from object
            unset($signs[$key]->latitude);
            unset($signs[$key]->longitude);
        }
        

        return response()->json($signs, 200);
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
        /* $validatedData = $request->validate([
            'name' => 'required|min:3|max:128',
            'viennaSignId' => 'required',
            'status' => 'required'
        ]);
 */
        $user = auth()->user();

        $iviSign = new IviSignMap();
        $iviSign->entityId = $user->IDEntity;
        $iviSign->name = $request->name;
        $iviSign->guid = (string) Str::uuid();
        $iviSign->viennaSignId = $request->viennaSignId;
        $iviSign->latitude = $request->coordinates['lat'];
        $iviSign->longitude = $request->coordinates['lng'];
        $iviSign->comment = $request->comment;
        $iviSign->locked = 1;
        $iviSign->status = isset($request->status['value']) ? $request->status['value'] : 1;
        
        $iviSign->save();

        $iviSign['message'] = 'Sign has been added with success';

        return response()->json($iviSign, 201);
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
     * @param  Int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $user = auth()->user();
        if (!isset($user)) {
            return response()->json(['message' => 'You have no permissions to delete a sign'], 403);
        }

        DB::table('ivi_signs_map')->where('id', '=', $id)->delete();

        return response()->json(['message' => 'Sign has been deleted with success'], 200);
    }

    public function getSignById($id) {

        $user = auth()->user();
        $entityId = $user->IDEntity;

        if ($user->hasRole('admin')) {
            $sign = DB::table('ivi_signs_map')->where('id', '=', $id)->get();
        } else {
            $sign = DB::table('ivi_signs_map')
                                    ->where('entityId', '=', $entityId)
                                    ->where('id', '=', $id)
                                    ->get();
        }

        if (empty($sign)) {
            $responseCode = 204; // No Content
            $sign = ['error' => 'No sign was found with this ID'];
        } else {
            $responseCode = 200; // Ok
        }



        return response()->json($sign, $responseCode);
    }

    public function getIviSignsMapMarkers() {
        $user = auth()->user();
        $entityId = isset($user->IDEntity) ? $user->IDEntity : $response['error'] = "Can't get user entity";

        if (isset($response['error'])) {
            return response()->json($response, 401);
        }


        if ($user->hasRole('admin')) {
            $signsId = DB::table('ivi_signs_map')
                ->select('id')
                ->get();

        } else {
            $signsId = DB::table('ivi_signs_map')
                ->select('id')
                ->where('entityId', '=', $entityId)
                ->get();
        }
        foreach ($signsId as $key=>$id) {
            $coordinates = DB::table('ivi_signs_map')->select('latitude', 'longitude')->where('id', '=', $id->id)->get();
            $signs[$key]['id'] = $id->id;
            $signs[$key]['coordinates']['lat'] = $coordinates[0]->latitude;
            $signs[$key]['coordinates']['lng'] = $coordinates[0]->longitude;
        }


        return response()->json($signs, 200);
    }


    /**
     * Update status of locked state.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateLockStatus(Request $request) {

        DB::table('ivi_signs_map')->where('id', $request->id)->update(['locked' => $request->locked]);

        $response = [];
        if ($request->locked == 1) {
            $response['message'] = 'Sign has been locked';
        } else {
            $response['message'] = 'Sign has been unlocked';
        }
        return response()->json($response, 200);
    }
}
