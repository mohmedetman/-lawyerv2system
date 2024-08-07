<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use function Laravel\Prompts\password;

class UserController extends Controller
{
    public function getAllusers()
    {
            $Admin = Auth::user();
            return response()->json([
                'users' =>UserResource::collection( User::where('lawyer_id',$Admin->id)->get() )
            ]);
    }

    public function editUsers(Request $request,$user_id){

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8',
            'code' => 'nullable|string|max:10',
            'phone_number' => 'nullable|string|max:15',
            'personal_id' => 'nullable|string|size:14',
            'address' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female',
            'user_type' => 'nullable|string|in:lawyer,client',
            'litigationDegree_en' => 'nullable|string|max:255',
            'litigationDegree_ar' => 'nullable|string|max:255',
            'lawyer_id' => 'nullable|exists:lawyers,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }


        $auth = Auth::user();
        $client = User::where('id',$user_id)->where('lawyer_id',$auth->id)->first();
        if (!isset($client)){
            return response()->json([
                'message' => 'User not found',
            ]);
        }
        $password = '' ;
        $password = $request->filled('password') ? Hash::make($request->password) : $client->password;
        $name = $request->filled('name') ? $request->name : $client->name;
        $code = $request->filled('code') ? $request->code : $client->code;
        $phone_number = $request->filled('phone_number') ? $request->phone_number : $client->phone_number;
        $personal_id = $request->filled('personal_id') ? $request->personal_id : $client->personal_id;
        $address = $request->filled('address') ? $request->address : $client->address;
        $gender = $request->filled('gender') ? $request->gender : $client->gender;
        $user_type = $request->filled('user_type') ? $request->user_type : $client->user_type;
        $litigationDegree_en = $request->filled('litigationDegree_en') ? $request->litigationDegree_en : $client->litigationDegree_en;
        $litigationDegree_ar = $request->filled('litigationDegree_ar') ? $request->litigationDegree_ar : $client->litigationDegree_ar;
        $client->update([
            'name' => $name,
            'password' => $password,
            'code' => $code,
            'phone_number' => $phone_number,
            'personal_id' => $personal_id,
            'address' => $address,
            'gender' => $gender,
            'user_type' => $user_type,
            'litigationDegree_en' => $litigationDegree_en,
            'litigationDegree_ar' => $litigationDegree_ar,
            'lawyer_id' => $auth->id,
        ]);
            return response()->json([
                'message' => 'user updated successfully'
            ]);



    }

    public function deleteUser($user_id){

           $lawyer = Auth::user();
           $user =   User::where('id',$user_id)->where('lawyer_id',$lawyer->id)->first();
           if(!isset($user)) {
               return response()->json([
                   'message' => 'user not found'
               ]);
           }
        $user->delete();
        return response()->json([
            'message' => 'user deleted successfully'
        ]);

        }



    public function getUserById($user_id){
        $auth = Auth::user();
        $user = User::where('id',$user_id)->where('lawyer_id',$auth->id)->first();
        if(!isset($user)) {
            return response()->json(['message' => 'user not found']);
        }
        return response()->json([UserResource::make($user)]);

    }
}
