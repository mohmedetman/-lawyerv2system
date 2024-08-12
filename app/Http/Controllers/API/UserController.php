<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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
            'name_en' => 'nullable|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'code' => [
                'nullable',
                'string',
                'max:10',
                Rule::unique('users')->ignore($user_id),
            ],
            'password' => 'nullable|string|min:8',
//            'code' => 'nullable|string|max:10',
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
        $password = $request->filled('password') ? bcrypt($request->password) : $client->password;
        $name = $request->filled('name') ? $request->name : $client->name;
        $code = $request->filled('code') ? $request->code : $client->code;
        $phone_number = $request->filled('phone_number') ? $request->phone_number : $client->phone_number;
        $personal_id = $request->filled('personal_id') ? $request->personal_id : $client->personal_id;
        $address = $request->filled('address') ? $request->address : $client->address;
        $gender = $request->filled('gender') ? $request->gender : $client->gender;
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
    public function getAllEmployees()
    {
        $user = Auth::user();
        return response()->json([
            'Employee' =>UserResource::collection( Employee::where('lawyer_id',$user->id)->get() )
        ]);

    }
    public function getEmployeesById($user_id)
    {
        $auth = Auth::user();
        $employee = Employee::where('id',$user_id)
            ->where('lawyer_id',$auth->id)
            ->first();
        if(!isset($employee)) {
            return response()->json(['message' => 'employee not found']);
        }
        return response()->json([UserResource::make($employee)]);
    }
    public function editEmployees(Request $request,$user_id){
        $validator = Validator::make($request->all(), [
            'name_en' => 'nullable|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'code' => [
                'nullable',
                'string',
                'max:10',
                Rule::unique('employees')->ignore($user_id),
            ],
            'password' => 'nullable|string|min:8',
//            'code' => 'nullable|string|max:10',
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
        $employee = Employee::where('id',$user_id)->where('lawyer_id',$auth->id)->first();
        if (!isset($employee)){
            return response()->json([
                'message' => 'employee not found',
            ]);
        }
//        dd($employee,$request->all());
        $password = '' ;
        $password = $request->filled('password') ? bcrypt($request->password) : $employee->password;
        $name_en = $request->filled('name_en') ? $request->name_en : $employee->name_en;
        $name_ar = $request->filled('name_ar') ? $request->name_ar : $employee->name_ar;
        $code = $request->filled('code') ? $request->code : $employee->code;
        $phone_number = $request->filled('phone_number') ? $request->phone_number : $employee->phone_number;
        $personal_id = $request->filled('personal_id') ? $request->personal_id : $employee->personal_id;
        $address = $request->filled('address') ? $request->address : $employee->address;
        $gender = $request->filled('gender') ? $request->gender : $employee->gender;
        $litigationDegree_en = $request->filled('litigationDegree_en') ? $request->litigationDegree_en : $employee->litigationDegree_en;
        $litigationDegree_ar = $request->filled('litigationDegree_ar') ? $request->litigationDegree_ar : $employee->litigationDegree_ar;
        $employee->update([
            'name_en' => $name_en,
            'name_ar' => $name_ar,
            'password' => $password,
            'code' => $code,
            'phone_number' => $phone_number,
            'personal_id' => $personal_id,
            'address' => $address,
            'gender' => $gender,
            'litigationDegree_en' => $litigationDegree_en,
            'litigationDegree_ar' => $litigationDegree_ar,
            'lawyer_id' => $auth->id,
        ]);
        return response()->json([
            'message' => 'employee updated successfully'
        ]);



    }
//deleteEmployees
    public function deleteEmployees($user_id){

        $lawyer = Auth::user();
        $employee =   Employee::where('id',$user_id)->where('lawyer_id',$lawyer->id)->first();
        if(!isset($employee)) {
            return response()->json([
                'message' => 'employee not found'
            ]);
        }
        $employee->delete();
        return response()->json([
            'message' => 'user deleted successfully'
        ]);

    }

}
