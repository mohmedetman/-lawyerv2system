<?php

namespace Modules\Admin\Http\Controllers\api;

use App\Http\Controllers\Controller;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Testing\Fluent\Concerns\Has;
use Laravel\Sanctum\PersonalAccessToken;
use Modules\Admin\Http\Requests\LoginRequest;
use Modules\Customer\Entities\Customer;
use Modules\Lawyer\Entities\Employee;
use Modules\Lawyer\Entities\Lawyer;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $lawyer = Lawyer::where('email',$request->email)->first();
        if($lawyer && Hash::check($request->password,$lawyer->password)){
            return $this->createToken($lawyer,'lawyer');
        }
        $employee = Employee::where('email',$request->email)->first();
        if($employee && Hash::check($request->password,$employee->password)){
            return $this->createToken($employee,'employee');
        }
        $customer = Customer::where('email',$request->email)->first();
        if($customer && Hash::check($request->password,$customer->password)){
            return $this->createToken($customer,'customer');
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
    private function createToken($user,$type) {
        $token = $user->createToken($type.'-'.now())->plainTextToken;
        return response()->json([
            'token'=>$token,
            $type => $user ,
        ],201);
    }
}
