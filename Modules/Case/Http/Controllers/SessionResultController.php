<?php

namespace Modules\Case\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Rules\SameLength;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Case\Entities\SessionResult;

class SessionResultController extends Controller
{
    public array $data = [];

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {

        return response()->json(SessionResult::with('session.case')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
         $data = Validator::make($request->all(), [
             'session_id' => 'required|exists:sessions,id',
             'result'=>'required' ,
             'legal_reasons' => 'required',
             'practical_reasons'=>['required',new SameLength($request->legal_reasons)],
         ]);
         if ($data->fails()) {
             return response()->json(['errors'=>$data->errors()],422);
         }
         $count = 0 ;
         if (is_array($request->legal_reasons))  $count = count($request->legal_reasons)-1 ;
         for ($i=0;$i<=$count;$i++) {
             SessionResult::create([
                 'session_id' => $request->session_id ,
                 'legal_reasons' => is_array($request->legal_reasons)  ?$request->legal_reasons[$i] : $request->legal_reasons,
                 'practical_reasons' => is_array($request->practical_reasons)  ?$request->practical_reasons[$i] : $request->practical_reasons,
                 'result' => $request->result,
             ]);
         }
        return response()->json(['message'=>'success'],200);
    }

    /**
     * Show the specified resource.
     */
    public function show($id): JsonResponse
    {
        return response()->json(SessionResult::with('session.case')->where('session_id',$id)->get());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        SessionResult::where('id',$id)->delete();
        return response()->json(['message'=>'success'],200);

    }
}
