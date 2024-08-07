<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BailiffsPapers;
use App\Models\TemporaryBailiffPapers;
use App\Models\TemporaryCaseFile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class BailiffController extends Controller
{
    public function addBailiffPaper(Request $request){
        $user = Auth::user();
        $client = User::find($request->user_id);
        if($user){
            if($user->user_type == 'محامي'){
                if($request->permission == " "){
                    BailiffsPapers::create([
                        'bailiffs_pen_en' => $request->bailiffs_pen_en,
                        'bailiffs_pen_ar' => $request->bailiffs_pen_ar,
                        'user_code' => $client->code,
                        'user_id' => $client->id,
                        'user_name' => $client->name,
                        'delivery_time' => $request->Delivery_time,
                        'session_time' => $request->session_time,
                        'bailiffs_num' => $request->bailiffs_num,
                        'permission' => $request->permission,
                        'announcment_time' => $request->announcment_time,
                        'bailiff_reply' => $request->bailiff_reply,
                        'status' => 'confirmed',
                    ]);
                    return response()->json([
                        'message' => 'Bailiffs Papers created successfully'
                    ]);
                }else{
                    BailiffsPapers::create([
                        'bailiffs_pen_en' => $request->bailiffs_pen_en,
                        'bailiffs_pen_ar' => $request->bailiffs_pen_ar,
                        'user_code' => $client->code,
                        'user_id' => $client->id,
                        'user_name' => $client->name,
                        'delivery_time' => $request->Delivery_time,
                        'session_time' => $request->session_time,
                        'bailiffs_num' => $request->bailiffs_num,
                        'permission' => $request->permission,
                        'announcment_time' => $request->announcment_time,
                        'bailiff_reply' => $request->bailiff_reply,
                        'status' => 'confirmed'
                    ]);
                    return response()->json([
                        'message' => 'Bailiffs Papers created successfully'
                    ]);
                }
                
            }
        }
        
    }

    public function editBailiffPaper(Request $request,$bailiff_id){
        $user = Auth::user();
        $client = User::find($request->user_id);
        $bailiff = BailiffsPapers::find($bailiff_id);
        if($user){
            if($user->user_type == 'محامي'){
                if($bailiff->status != 'rejected'){
                    $bailiff->update([
                    'bailiffs_pen_en' => $request->bailiffs_pen_en,
                    'bailiffs_pen_ar' => $request->bailiffs_pen_ar,
                    'user_code' => $client->code,
                    'user_id' => $client->id,
                    'user_name' => $client->name,
                    'delivery_time' => $request->Delivery_time,
                    'session_time' => $request->session_time,
                    'bailiffs_num' => $request->bailiffs_num,
                    'announcment_time' => $request->announcment_time,
                    'bailiff_reply' => $request->bailiff_reply,
                    'permission' => $request->permission,
                        
                    ]);
                    return response()->json([
                        'message' => 'Bailiffs Papers updated successfully'
                    ]);
                }
                
            }else{
                if($bailiff->status == 'pending' || $bailiff->status == 'confirmed' && $bailiff->permission != " "){
                    TemporaryBailiffPapers::create([
                        'bailiffs_pen_en' => $request->bailiffs_pen_en,
                        'bailiffs_pen_ar' => $request->bailiffs_pen_ar,
                        'bailiff_id' => $bailiff->id,
                        'user_code' => $client->code,
                        'user_id' => $client->id,
                        'user_name' => $client->name,
                        'delivery_time' => $request->Delivery_time,
                        'session_time' => $request->session_time,
                        'bailiffs_num' => $request->bailiffs_num,
                        'announcment_time' => $request->announcment_time,
                        'bailiff_reply' => $request->bailiff_reply,
                        'permission' => $bailiff->permission
                            
                    ]);
                    return response()->json([
                        'message' => 'Bailiffs Papers updated successfully'
                    ]);
                }
                
            }
        }
        
    }

    public function deleteBailiffPaper($bailiff_id){
        $user = Auth::user();
        $bailiff = BailiffsPapers::find($bailiff_id);
        if($user){
            if($user->user_type == 'محامي'){
                $bailiff->delete();
                return response()->json([
                    'message' => 'Case File deleted successfully'
                ]);
            }
        }
       
    }

    public function getAllBailiffPapers(){
        $user = Auth::user();
        if($user){
            $bailiffs = BailiffsPapers::where('status','!=','rejected')->get();
            $merged_bailiffs = $bailiffs->map(function($bailiff){
                return[
                    'id'=>$bailiff->id,
                    'bailiffs_pen_en' => $bailiff->bailiffs_pen_en,
                    'bailiffs_pen_ar' => $bailiff->bailiffs_pen_ar,
                    'user_code' => $bailiff->user_code,
                    'user_name' => $bailiff->user_name,
                    'delivery_time' => $bailiff->delivery_time,
                    'session_time' => $bailiff->session_time,
                    'status' => $bailiff->status,
                    'employee_name' => User::where('code',$bailiff->permission)->value('name'),
                    'permission' => $bailiff->permission,
                    'announcment_time' => $bailiff->announcment_time,
                    'bailiff_reply' => $bailiff->bailiff_reply,
                    'bailiffs_num' => $bailiff->bailiffs_num    
                ];
                
            });
            
            return response()->json([
                'BailiffsPapers' => $merged_bailiffs
            ]); 
        }
        
    }

    public function getAllPendingBailiffPapersLawyerSide(){
        $user = Auth::user();
        if($user){
            if($user->user_type == 'محامي'){
                return response()->json([
                    'temporaryBailiffPapers' => TemporaryBailiffPapers::where('status','pending')->get()
                ]);
            }
        }
    }

    public function getAllPendingBailiffPapersemployeeSide(){
        $user = Auth::user();
        if($user){
            if($user->user_type == 'موظف'){
                return response()->json([
                    'bailiffPapers' => BailiffsPapers::where('status','pending')->orWhere('status','confirmed')->Where('permission',$user->code)->get()
                ]);
            }
        }
    }


    // public function confirmBailiffPaper($bailiff_id){
    //     $user = Auth::user();
    //     $tempBailiff = TemporaryBailiffPapers::find($bailiff_id);
    //     $bailiff = BailiffsPapers::where('status','confirmed')->where('permission',$tempBailiff->permission)->where('bailiffs_pen_en',$tempBailiff->bailiffs_pen_en)->orWhere('bailiffs_pen_ar',$tempBailiff->bailiffs_pen_ar)->first();

    //     if($user){
    //         if($user->user_type == 'محامي'){
    //             $bailiff->update([
    //                 'bailiffs_pen_en' => $tempBailiff->bailiffs_pen_en,
    //                 'bailiffs_pen_ar' => $tempBailiff->bailiffs_pen_ar,
    //                 'user_code' => $tempBailiff->user_code,
    //                 'user_id' => $tempBailiff->user_id,
    //                 'user_name' => $tempBailiff->user_name,
    //                 'delivery_time' => $tempBailiff->delivery_time,
    //                 'session_time' => $tempBailiff->session_time,
    //                 'bailiffs_num' => $tempBailiff->bailiffs_num,
    //                 'permission' => $tempBailiff->permission,
    //                 'status' => 'confirmed',
    //               ]);
    //               $bailiff->update([
    //                 'status' => 'confirmed',
    //               ]);
    //               return response()->json([
    //                 'message' => 'BailiffsPapers confirmed successfully'
    //               ]);
    //         }
    //     }
               
    // }
    public function confirmBailiffPaper($bailiff_id){
    $user = Auth::user();
    $tempBailiff = TemporaryBailiffPapers::find($bailiff_id);

    if ($user && $user->user_type == 'محامي' && $tempBailiff) {
        // Correctly structure the query with orWhere
        $bailiff = BailiffsPapers::find($tempBailiff->bailiff_id);

        // Check if $bailiff is found
        if ($bailiff) {
            $bailiff->update([
                'bailiffs_pen_en' => $tempBailiff->bailiffs_pen_en,
                'bailiffs_pen_ar' => $tempBailiff->bailiffs_pen_ar,
                'user_code' => $tempBailiff->user_code,
                'user_id' => $tempBailiff->user_id,
                'user_name' => $tempBailiff->user_name,
                'delivery_time' => $tempBailiff->delivery_time,
                'session_time' => $tempBailiff->session_time,
                'bailiffs_num' => $tempBailiff->bailiffs_num,
                'announcment_time' => $tempBailiff->announcment_time,
                'bailiff_reply' => $tempBailiff->bailiff_reply,
                'permission' => $tempBailiff->permission,
                
                'status' => 'confirmed',
            ]);
                
            $tempBailiff->update([
                    'status' => 'confirmed'
                ]);
    
            return response()->json([
                'message' => 'BailiffsPapers confirmed successfully'
            ]);
        } else {
            // Bailiff not found
            return response()->json([
                'message' => 'No matching BailiffsPapers found'
            ], 404);
        }
    }

    // User not authenticated or not authorized
    return response()->json([
        'message' => 'Unauthorized or invalid data'
    ], 403);
}


    public function rejectBailiffPaper($bailiff_id){
        $user = Auth::user();
        $tempBailiff = TemporaryBailiffPapers::find($bailiff_id);
        $bailiff = BailiffsPapers::where('status','pending')->orWhere('status','confirmed')->where('permission',$tempBailiff->permission)->where('bailiffs_pen_en',$tempBailiff->bailiffs_pen_en)->orWhere('bailiffs_pen_ar',$tempBailiff->bailiffs_pen_ar)->first();

        if($user){
            if($user->user_type == 'محامي'){
                $tempBailiff->update([
                    'status' => 'rejected'
                 ]);
                 return response()->json([
                  'message' => 'BailiffsPapers rejected successfully'
                 ]);
            }
        }
       
    }

    public function getBailifPaperByBailifId($bailiff_id){
        $user = Auth::user();
        if($user){
            return response()->json([
                'BailifPaper' => BailiffsPapers::find($bailiff_id)
            ]);
        }
    }
    
    
    public function getClientBailiffPapers(){
        return response()->json([
                'BailifPapers' => BailiffsPapers::where('user_id',Auth::id())->where('status','confirmed')->get()
            ]);
    }
}
