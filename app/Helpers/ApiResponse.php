<?php


namespace App\Helpers;

trait ApiResponse{

  public function apiResponse($status=200,$data='',$message=''){
    $arr['status']=$status;
    $arr ['data'] = $data;
    $arr['message'] = $message;
    return response()->json($arr);
  }
}
