<?php

namespace App\Http\Controllers;

use App\Models\IviSignMap;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DetectionZone;
use App\Models\AwarenessZone;
use App\Models\RelevanceZone;

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
            $signs = DB::table('ivi_signs_map')
                ->leftjoin('vienna_signs', 'vienna_signs.id', '=', 'ivi_signs_map.viennaSignId')
                ->get();
        } else {
            $signs = DB::table('ivi_signs_map')->where('entityId', '=', $entityId)
                ->leftjoin('vienna_signs', 'vienna_signs.id', '=', 'ivi_signs_map.viennaSignId')
                ->get();
        }

        foreach($signs as $key => $sign) {

            // Put latitude and longitude in new object inside signs for markers in map
            $signs[$key]->coordinates = new \stdClass();
            $signs[$key]->coordinates->lat = $sign->latitude;
            $signs[$key]->coordinates->lng = $sign->longitude;


            // Remove old latitude and longitude from object
            /* unset($signs[$key]->latitude);
            unset($signs[$key]->longitude); */
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

        if (isset($request->detectionZone)) {
            $detectionZone = new DetectionZone();

            $detectionZone->latitude1 = $request->detectionZone[0]['lat'];
            $detectionZone->longitude1 = $request->detectionZone[0]['lng'];

            $detectionZone->latitude2 = $request->detectionZone[1]['lat'];
            $detectionZone->longitude2 = $request->detectionZone[1]['lng'];

            $detectionZone->save();
        }

        if (isset($request->awarenessZone)) {
            $awarenessZone = new AwarenessZone();
            $awarenessZone->latitude1 = $request->awarenessZone[0]['lat'];
            $awarenessZone->longitude1 = $request->awarenessZone[0]['lng'];

            $awarenessZone->latitude2 = $request->awarenessZone[1]['lat'];
            $awarenessZone->longitude2 = $request->awarenessZone[1]['lng'];

            $awarenessZone->save();
        }

        if (isset($request->awarenessZone)) {
            $relevanceZone = new RelevanceZone();
            $relevanceZone->latitude1 = $request->relevanceZone[0]['lat'];
            $relevanceZone->longitude1 = $request->relevanceZone[0]['lng'];

            $relevanceZone->latitude2 = $request->relevanceZone[1]['lat'];
            $relevanceZone->longitude2 = $request->relevanceZone[1]['lng'];

            $relevanceZone->save();
        }

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
        $iviSign->IDDetection = isset($detectionZone->id) ? $detectionZone->id : 0;
        $iviSign->IDAwareness = isset($awarenessZone->id) ? $awarenessZone->id : 0;
        $iviSign->IDRelevance = isset($relevanceZone->id) ? $relevanceZone->id : 0;
        $iviSign->deployed = 0;
        $iviSign->published = 0;
        
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $sign = IviSignMap::findOrFail($id);

        if (isset($request->detectionZone)) {
            $detectionZone = new DetectionZone();

            $detectionZone->latitude1 = $request->detectionZone[0]['lat'];
            $detectionZone->longitude1 = $request->detectionZone[0]['lng'];

            $detectionZone->latitude2 = $request->detectionZone[1]['lat'];
            $detectionZone->longitude2 = $request->detectionZone[1]['lng'];

            $detectionZone->save();
        }

        if (isset($request->awarenessZone)) {
            $awarenessZone = new AwarenessZone();
            $awarenessZone->latitude1 = $request->awarenessZone[0]['lat'];
            $awarenessZone->longitude1 = $request->awarenessZone[0]['lng'];

            $awarenessZone->latitude2 = $request->awarenessZone[1]['lat'];
            $awarenessZone->longitude2 = $request->awarenessZone[1]['lng'];

            $awarenessZone->save();
        }

        if (isset($request->awarenessZone)) {
            $relevanceZone = new RelevanceZone();
            $relevanceZone->latitude1 = $request->relevanceZone[0]['lat'];
            $relevanceZone->longitude1 = $request->relevanceZone[0]['lng'];

            $relevanceZone->latitude2 = $request->relevanceZone[1]['lat'];
            $relevanceZone->longitude2 = $request->relevanceZone[1]['lng'];

            $relevanceZone->save();
        }

        $iviSign = new IviSignMap();
        $iviSign->entityId = $user->IDEntity;
        $iviSign->name = $request->name;
        $iviSign->guid = (string) Str::uuid();
        $iviSign->viennaSignId = $request->viennaSignId;
        $iviSign->latitude = $request->coordinates['lat'];
        $iviSign->longitude = $request->coordinates['lng'];
        $iviSign->comment = $request->comment;
        $iviSign->locked = 1;
        $iviSign->IDDetection = isset($detectionZone->id) ? $detectionZone->id : 0;
        $iviSign->IDAwareness = isset($awarenessZone->id) ? $awarenessZone->id : 0;
        $iviSign->IDRelevance = isset($relevanceZone->id) ? $relevanceZone->id : 0;
        $iviSign->status = isset($request->status['value']) ? $request->status['value'] : 1;

        $sign->update($iviSign);


        return response()->json([
            'message'=>'Sign Updated Successfully!',
        ]); 
    }

    /**
     * Update the coordinates of the sign.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Array $coordinates
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCoordinates(Request $request, $id) {
        DB::table('ivi_signs_map')
            ->where('id', '=', $id)
            ->update(['latitude' => $request['latitude'], 'longitude' => $request['longitude']]);

        return response()->json([
            'message'=>'Coordinates Updated Successfully!',
        ]);
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

    public function getZones($id) {
        $zones = DB::table('ivi_signs_map')
        ->leftJoin('detection_zones', 'ivi_signs_map.IDDetection', '=', 'detection_zones.id')
        ->leftJoin('awareness_zones', 'ivi_signs_map.IDAwareness', '=', 'awareness_zones.id')
        ->leftJoin('relevance_zones', 'ivi_signs_map.IDRelevance', '=', 'relevance_zones.id')
        ->select(
            'ivi_signs_map.id',
            'detection_zones.latitude1 as detection_latitude1',
            'detection_zones.latitude2 as detection_latitude2',
            'detection_zones.longitude1 as detection_longitude1',
            'detection_zones.longitude2 as detection_longitude2',
            'awareness_zones.latitude1 as awareness_latitude1',
            'awareness_zones.latitude2 as awareness_latitude2',
            'awareness_zones.longitude1 as awareness_longitude1',
            'awareness_zones.longitude2 as awareness_longitude2',
            'relevance_zones.latitude1 as relevance_latitude1',
            'relevance_zones.latitude2 as relevance_latitude2',
            'relevance_zones.longitude1 as relevance_longitude1',
            'relevance_zones.longitude2 as relevance_longitude2',
        )
        ->where('ivi_signs_map.id', '=', $id)
        ->get();

        return response()->json($zones, 200);
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getpublishedsigns()
    {
        $user = auth()->user();
        $entityId = isset($user->IDEntity) ? $user->IDEntity : $response['error'] = "Can't get user entity";

        if (isset($response['error'])) {
            return response()->json($response, 401);
        }

        if ($user->hasRole('admin')) {
            $signs = DB::table('ivi_signs_map')->where('published', '=', 1)->get();
        } else {
            $signs = DB::table('ivi_signs_map')->where('entityId', '=', $entityId)->where('published', '=', 1)->get();
        }

        /* foreach($signs as $key => $sign) {

            // Put latitude and longitude in new object inside signs for markers in map
            $signs[$key]->coordinates = new \stdClass();
            $signs[$key]->coordinates->lat = $sign->latitude;
            $signs[$key]->coordinates->lng = $sign->longitude;
        } */
        

        return response()->json($signs, 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getunpublishedsigns()
    {
        $user = auth()->user();
        $entityId = isset($user->IDEntity) ? $user->IDEntity : $response['error'] = "Can't get user entity";

        if (isset($response['error'])) {
            return response()->json($response, 401);
        }

        if ($user->hasRole('admin')) {
            $signs = DB::table('ivi_signs_map')->where('published', '=', 0)->get();
        } else {
            $signs = DB::table('ivi_signs_map')->where('entityId', '=', $entityId)->where('published', '=', 0)->get();
        }

        foreach($signs as $key => $sign) {

            // Put latitude and longitude in new object inside signs for markers in map
            $signs[$key]->coordinates = new \stdClass();
            $signs[$key]->coordinates->lat = $sign->latitude;
            $signs[$key]->coordinates->lng = $sign->longitude;
        }
        

        return response()->json($signs, 200);
    }

    /**
     * Update the published status of a sign.
     *
     * @param  \App\Models\IviSignMap  $iviSignMap
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function publishedUpdate (Request $request, $id) {
        $affected = DB::table('ivi_signs_map')
              ->where('id', $id)
              ->update(['published' => $request->published]);

        return response()->json(['message' => 'Sign updated with success'], 200);
    }


    /**
     * Display a listing of the signs to factory make.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSignsToMake() {
        $signs = DB::table('ivi_signs_map')
            ->leftjoin('vienna_signs', 'vienna_signs.id', '=', 'ivi_signs_map.viennaSignId')
            ->where('ivi_signs_map.published', '=', 1)
            ->select('ivi_signs_map.id', 'ivi_signs_map.entityId', 'ivi_signs_map.GUID', 'ivi_signs_map.latitude', 'ivi_signs_map.longitude', 'ivi_signs_map.viennaSignId', DB::raw('(CASE WHEN madeByFactory=1 THEN "Yes" ELSE "No" END) as madeByFactory'), 'vienna_signs.image')
            ->get();

        return response()->json($signs, 200);
    }

    /**
     * Set sign as made.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setMade(Request $request, $id) {
        DB::table('ivi_signs_map')->where('id', '=', $id)->update(['madeByFactory' => 1]);


        return response()->json(['message' => 'The sign with id ' . $id . ' has been marked has made by factory']);
    }

    /**
     * Set sign as not made.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setNotMade(Request $request, $id) {
        DB::table('ivi_signs_map')->where('id', '=', $id)->update(['madeByFactory' => 0]);

      

        return response()->json(['message' => 'The sign with id ' . $id . ' has been marked has not made by factory']);
    }

}
