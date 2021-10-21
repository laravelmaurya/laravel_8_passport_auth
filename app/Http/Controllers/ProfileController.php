<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function index()
    {
        $profiles = auth()->user()->profiles;
 
        return response()->json([
            'success' => true,
            'data' => $profiles
        ]);
    }
 
    public function show($id)
    {
        $profile = auth()->user()->profiles()->find($id);
 
        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile not found '
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $profile->toArray()
        ], 400);
    }
 
    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'address' => 'required'
        ]);
 
        $profile = new Profile();
        $profile->user_id = $request->user_id;
        $profile->address = $request->address;
 
        if (auth()->user()->profiles()->save($profile))
            return response()->json([
                'success' => true,
                'data' => $profile->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Profile not added'
            ], 500);
    }
 
    public function update(Request $request, $id)
    {
       // dd($id);
        //dd($id,$request->user_id);
       $profile = Profile::where('user_id',$request->user_id)->get();
      // dd($profile);
        // $profile = auth()->user()->profiles()->where('user_id',$id);
       
       
        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile not found'
            ], 400);
        }
        $arrayData = ['address'=>$request->address];
         $profile=DB::table('profiles')->where('user_id',$request->user_id)->update($arrayData);
       // $updated = $profile->fill($request->all())->save();
 
       
    }
 
    public function destroy($id)
    {
        $profile = auth()->user()->profiles()->find($id);
 
        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile not found'
            ], 400);
        }
 
        if ($profile->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Profile can not be deleted'
            ], 500);
        }
    }

    
}
