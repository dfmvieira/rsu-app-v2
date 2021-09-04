<?php

namespace App\Http\Controllers;

use App\Models\ViennaSign;
use App\Models\ViennaSignCategory;
use App\Services\RolesService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonRequest;
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
       
        $signs = DB::table('vienna_signs')
        ->leftjoin('vienna_sign_categories', 'vienna_signs.IDCategory', '=', 'vienna_sign_categories.id')
        ->select('vienna_signs.id', 'vienna_signs.name', 'vienna_sign_categories.category', 'vienna_signs.image')
        ->get();

        //$signs['image'] = Storage::disk('public')->get('img/ViennaSigns/' . $signs['name']);
        /* foreach($signs as $sign) {
            dump($sign->image);
            $sign->image = Storage::disk('public')->get($sign->image);
        } */

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        /* $request->validate([
        ]); */

        /* $user = auth()->user();
        $entityId = isset($user->IDEntity) ? $user->IDEntity : $response['error'] = "Can't get user entity";

        if (isset($response['error'])) {
            return response()->json($response, 401);
        } */


        if($request->image['base64']) {

            $image = $request->image;

            $base64_string = explode(',', $image['base64']);
            $imageBin = base64_decode($base64_string[1]);

            if (!Storage::disk('public')->exists('img/ViennaSigns/' . $image['name'])) {
                Storage::disk('public')->put('img/ViennaSigns/' . $image['name'], $imageBin);
            }
        }

        $viennaSign = new ViennaSign();
        $viennaSign->fill($request->all());
        $viennaSign->image = $request->image['base64'] ? 'img/ViennaSigns/' . $request->image['name'] : '';
        $viennaSign->save();

        return response()->json($viennaSign, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id) {

        //$viennaSign = ViennaSign::findOrFail($id);

        $viennaSign = DB::table('vienna_signs')
        ->select('*')
        ->where('id', '=', $id)
        ->get();

        return response()->json($viennaSign, 201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ViennaSign  $viennaSign
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, $id)
    {
        $sign = ViennaSign::findOrFail($id);

        $sign->update($request->all());

        return response()->json([
            'message'=>'Sign Updated Successfully!!',
        ]); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ViennaSign  $viennaSign
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
        /* try 
        {
            vienna_signs::whereIn('id', $request->id)->delete(); // $request->id MUST be an array
            return response()->json('users deleted');
        }
    
        catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        } */
    }

    public function delete($id)
    {
        DB::table('vienna_signs')->where('id','=', $id)->delete();
    
        return response()->json("ok");
    }

    public function deleteCategories($id)
    {
        DB::table('vienna_sign_categories')->where('id','=', $id)->delete();
    
        return response()->json("ok");
    }

    /**
     * Display a listing of the signs categories.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSignsCategories() {
        $categories = DB::table('vienna_sign_categories')
        ->select('vienna_sign_categories.id', 'vienna_sign_categories.category')
        ->get();

        return response()->json($categories);
    }

    public function storeCategories(Request $request)
    {
        $request->validate([
            

        ]);

        $category = new ViennaSignCategory();
        $category->fill($request->all());
        $category->save();

        return response()->json($category, 201);
    }

    public function updateCategory(Request $request, $id)
    {
       
        $category = ViennaSignCategory::findOrFail($id);

        $category->update($request->all());
        return response()->json([
            'message'=>'Category Updated Successfully!!',
        ]); 
       
        /* $category->fill($request->post())->save();
        return response()->json([
            'message'=>'Category Updated Successfully!!',
            'category'=>$category
        ]); */
    }
}