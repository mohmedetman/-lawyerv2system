<?php

namespace App\Http\Controllers\API\Admin;


use App\Http\Resources\LawyerResource;
use App\Models\Admin;
use App\Models\Lawyer;
use App\Models\LawyerDepartment;
use App\Models\Subscription;
use App\Rules\ChechEmailUniqe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController
{
    public function adminLogin(Request $request) {

        $validator = Validator::make($request->all(), [
            'email'=>'required',
            'password'=>'required',
            'dev_name'=>'string',
            'ability' => 'array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first()
            ], 404);
        }
        $admin =Admin::where('email',$request->email)->first();
        if (!isset($admin)){
            return response()->json([ 'error' => 'auth Faild'
            ],404);
        }
        $plainTextPassword = $request->post('password');
        $hashedPassword = $admin->password;
        if($admin && password_verify($plainTextPassword, $hashedPassword) ) {
            $dev_name = $request->dev_name ?? $request->userAgent();
            $token = $admin->createToken($dev_name);
            return response()->json([
                'token'=>$token->plainTextToken,
                'user' => $admin ,
            ],201);
        }
        return response()->json(['faild auth '],401);
    }
    public function addDepartment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_en' => 'required_without:name_ar|string|max:255',
            'name_ar' => 'required_without:name_en|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first()
            ], 404);
        }
        LawyerDepartment::create([
            'name_en'=>$request->name_en,
            'name_ar'=>$request->name_ar,

        ]);
        return response()->json([
            'message' => 'Department added successfully'
        ],201);
    }
    public function showAllDepartment()
    {
        return response()->json([
            'departments' => LawyerDepartment::all()
        ]);
    }

    public function addLawyer(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name_en' => 'required_without:name_ar|string|max:255',
            'name_ar' => 'required_without:name_en|string|max:255',
            'email' => ['required','email',new ChechEmailUniqe],
            'phone_number' => 'required|string|max:20',
            'department_id' => 'required|string|max:20,exists:departments,id',
            'password' => 'required|string|min:6',
            'code' => 'required|string|unique:lawyers,code',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first()
            ], 404);
        }
        $new_password ='';
        $lawyer = Lawyer::create([
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'email' => $request->email,
            'code'=>$request->code,
            'password' => Hash::make($request->password),

//            'password'=>bcrypt($request->password),
            'phone_number' => $request->phone_number,
            'admin_id'=>Auth::user()->id,
            'bio_ar'=>$request->bio_ar,
            'bio_en'=>$request->bio_en,
            'department_id' =>$request->department_id,
        ]);
        return response()->json([
            'message' => 'Lawyer created successfully',
        ], 201);
    }
    public function showAllLawyer()
    {
      $lawyers = Lawyer::with('department')->get();
      return response()->json([LawyerResource::collection($lawyers)]);
    }
    public function addSubscribeLawyer(Request $request,$id)
    {
       $lawyer = Lawyer::find($id);
       if(!isset($lawyer)){
           return response()->json([
               'error' => 'Lawyer Not Found'
           ]);
       }
        Subscription::updateOrCreate(
            ['lawyer_id' => $id],
            ['status' => 'active']
        );

            return response()->json([
        'message' => 'Lawyer Subscribtion added successfully'
    ],401);
        }

}
