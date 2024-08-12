<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Employee;
use App\Models\Lawyer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function createNewUser(Request $request){
        $validator = Validator::make($request->all(), [
            'name_en' => 'required_without:name_ar|string|max:255',
            'name_ar' => 'required_without:name_en|string|max:255',
            'password' => 'required|string|min:8',
            'code' => 'required|string|max:10|unique:users,code',
            'phone_number' => 'required|string|max:15',
            'personal_id' => 'required|string|size:14',
            'email' => 'required|string|email|max:255|unique:users,email',
            'address' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'user_type' => 'required|string|in:lawyer,user,employee',
            'litigationDegree_en' => 'required|string|max:255',
            'litigationDegree_ar' => 'required|string|max:255',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        $auth = Auth::user();
        if($request->user_type == 'user'){
            if($request->password == $request->confirm_password) {
                $user = User::create([
                    'name_en' => $request->name_en,
                    'name_ar' => $request->name_ar,
                    'password' => bcrypt($request->password),
                    'code' => $request->code,
                    'email'=>$request->email,
                    'phone_number' => $request->phone_number,
                    'personal_id' => $request->personal_id,
                    'address' => $request->address,
                    'gender' => $request->gender,
                    'lawyer_id'=>$auth->id ,
                    'litigationDegree_en' => $request->litigationDegree_en,
                    'litigationDegree_ar' => $request->litigationDegree_ar,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'User created successfully',
                    'user' => $user,
                ], 201);
            }
            if($validator->fails()){
                return response()->json(['error'=>'password not matched'], 400);
            }
        }
        elseif ($request->user_type =="employee") {
            $auth = Auth::user();
            if($request->password == $request->confirm_password) {
                $user = Employee::create([
                    'name_en' => $request->name_en,
                    'name_ar' => $request->name_ar,
                    'password' => bcrypt($request->password),
                    'code' => $request->code,
                    'email'=>$request->email,
                    'phone_number' => $request->phone_number,
                    'personal_id' => $request->personal_id,
                    'address' => $request->address,
                    'gender' => $request->gender,
                    'lawyer_id'=>$auth->id ,
//                'user_type' => 'client',
                    'litigationDegree_en' => $request->litigationDegree_en,
                    'litigationDegree_ar' => $request->litigationDegree_ar,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Employee created successfully',
                    'user' => $user,
                ], 201);
            }
            if($validator->fails()){
                return response()->json(['error'=>'password not matched'], 400);
            }
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type'=>'required|in:lawyer,employee,user',
            'email'=>'required',
            'password'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first()
            ], 404);
        }
        if($request->type == "lawyer") {
            $lawyer =Lawyer::where('email',$request->email)->first();
            if (!isset($lawyer)) {
                return response()->json([ 'error' => 'auth Faild'
                ],404);
            }
            $plainTextPassword = $request->post('password');
            $hashedPassword = $lawyer->password;
            if($lawyer && password_verify($plainTextPassword, $hashedPassword) ) {
                $dev_name = $request->dev_name ?? $request->userAgent();
                $token = $lawyer->createToken($dev_name);
                return response()->json([
                    'token'=>$token->plainTextToken,
                    'lawyer' => $lawyer ,
                ],201);
            }
        }
        if($request->type == "user") {
            $user =User::where('email',$request->email)->first();
            if (!isset($user)) {
                return response()->json([ 'error' => 'auth Faild'
                ],404);
            }
            $plainTextPassword = $request->post('password');
            $hashedPassword = $user->password;
            if($user && password_verify($plainTextPassword, $hashedPassword) ) {
                $dev_name = $request->dev_name ?? $request->userAgent();
                $token = $user->createToken($dev_name);
                return response()->json([
                    'token'=>$token->plainTextToken,
                    'user' => $user ,
                ],201);
            }
        }
        if($request->type == "employee") {
            $employee =Employee::where('email',$request->email)->first();
            if (!isset($employee)) {
                return response()->json([ 'error' => 'auth Faild'
                ],404);
            }
            $plainTextPassword = $request->post('password');
            $hashedPassword = $employee->password;
            if($employee && password_verify($plainTextPassword, $hashedPassword) ) {
                $dev_name = $request->dev_name ?? $request->userAgent();
                $token = $employee->createToken($dev_name);
                return response()->json([
                    'token'=>$token->plainTextToken,
                    'employee' => $employee ,
                ],201);
            }
        }

        return response()->json(['faild auth '],401);


    }

    public function userRoleRedirection(){
        $personal_token = PersonalAccessToken::find(request()->bearerToken());
        if($personal_token->tokenable_type  =="App\Models\Lawyer"){
            return response()->json([
                'type' => 'lawyer'
            ]);
        }
        if($personal_token->tokenable_type  =="App\Models\Admin"){
            return response()->json([
                'type' => 'Admin'
            ]);
        }
        if($personal_token->tokenable_type  =="App\Models\Employee"){
            return response()->json([
                'type' => 'employee'
            ]);
        }
        if($personal_token->tokenable_type  =="App\Models\User"){
            return response()->json([
                'type' => 'User'
            ]);
        }
    }
    public function me()
    {
     return  response()->json(auth()->user());
    }
}