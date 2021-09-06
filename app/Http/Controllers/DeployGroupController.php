<?php

namespace App\Http\Controllers;

use App\Models\DeployGroup;
use Illuminate\Http\Request;
use App\Models\UsersDeployGroups;
use App\Models\SignsDeployGroups;
use Illuminate\Support\Facades\DB;

class DeployGroupController extends Controller
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
            $groups = DB::table('deploy_groups')
                ->select('*', DB::raw('(CASE WHEN deployed=1 THEN "Yes" ELSE "No" END) as deployed'))
                ->get();
        } else {
            $groups = DB::table('deploy_groups')
                ->select('*', DB::raw('(CASE WHEN deployed=1 THEN "Yes" ELSE "No" END) as deployed'))
                ->where('entityID', '=', $entityId)
                ->get();
        }

        foreach ($groups as $key => $group) {
            $groups[$key]->signs = DB::table('ivi_signs_map')
                ->select('ivi_signs_map.*')
                ->leftJoin('signs_deploy_groups', 'signs_deploy_groups.IDIviSign', '=', 'ivi_signs_map.id')
                ->where('signs_deploy_groups.IDDeployGroup', '=', $group->id)
                ->get();

            $groups[$key]->users = DB::table('users')
                ->select('users.name', 'users.email')
                ->leftjoin('users_deploy_groups', 'users_deploy_groups.IDUser', '=', 'users.id')
                ->where('users_deploy_groups.IDDeployGroup', '=', $group->id)
                ->get();
        }

        return response()->json($groups, 200);

    }

    /**
     * Display a listing of groups per user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGroupsByUser() {
        $user = auth()->user();

        if (!isset($user)) {
            $response['error'] = "Can't get user entity";

            return response()->json($response, 401);
        }


        $groups = DB::table('deploy_groups')
            ->leftjoin('users_deploy_groups', 'users_deploy_groups.IDDeployGroup', '=', 'deploy_groups.id')
            ->select('deploy_groups.*', 'users_deploy_groups.IDUser', 'users_deploy_groups.IDDeployGroup', DB::raw('(CASE WHEN deploy_groups.deployed=1 THEN "Yes" ELSE "No" END) as deployed'))
            ->where('users_deploy_groups.IDUser', '=', $user->id)
            ->get();
       
        if (!isset($groups)) { 
            $response['error'] = "This user has no deploy groups";

            return response()->json($response, 401);
        }

        foreach($groups as $key=>$group) {
            $groups[$key]->signs = DB::table('ivi_signs_map')
                    ->select('ivi_signs_map.*')
                    ->leftJoin('signs_deploy_groups', 'signs_deploy_groups.IDIviSign', '=', 'ivi_signs_map.id')
                    ->where('signs_deploy_groups.IDDeployGroup', '=', $group->id)
                    ->get();
        }

        

        return response()->json($groups, 200);

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
        $user = auth()->user();
        $entityId = isset($user->IDEntity) ? $user->IDEntity : $response['error'] = "Can't get user entity";

        if (isset($response['error'])) {
            return response()->json($response, 401);
        }

        $deploygroup = new DeployGroup();
        $deploygroup->name = $request['name'];
        $deploygroup->notes = $request['notes'];
        $deploygroup->entityID = $entityId;
        $deploygroup->deployed = 0;
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

    public function signsForDeploy() {

        $user = auth()->user();
        $entityId = isset($user->IDEntity) ? $user->IDEntity : $response['error'] = "Can't get user entity";

        $signs = DB::table('ivi_signs_map')
            ->where('deployed', '=', 0)
            ->where('published', '=', 1)
            ->where('entityId', '=', $entityId)
            ->get();
            

        foreach($signs as $key=>$sign) {
            $signGroup = null;
            $signGroup = DB::table('signs_deploy_groups')
                ->where('IDIviSign', '=', $sign->id)
                ->get();
            

            if (!$signGroup->isEmpty()) {

                unset($signs[$key]);
            }
        }

        return response()->json($signs, 200);
    }


    /**
     * Set group as deployed.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setDeployed(Request $request, $id) {
        DB::table('deploy_groups')->where('id', '=', $id)->update(['deployed' => 1]);

        foreach ($request->signs as $sign) {
            DB::table('ivi_signs_map')->where('id', '=', $sign['id'])->update(['deployed' => 1]);
        }

        return response()->json(['message' => 'The group with id ' . $id . ' has been marked has deployed']);
    }

    /**
     * Set group as Not Deployed.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setNotDeployed(Request $request, $id) {
        DB::table('deploy_groups')->where('id', '=', $id)->update(['deployed' => 0]);

        foreach ($request->signs as $sign) {
            DB::table('ivi_signs_map')->where('id', '=', $sign['id'])->update(['deployed' => 0]);
        }

        return response()->json(['message' => 'The group with id ' . $id . ' has been marked has not deployed']);
    }
}
