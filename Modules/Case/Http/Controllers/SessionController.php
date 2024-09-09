<?php

namespace Modules\Case\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\PersonalAccessToken;
use Modules\Case\Entities\Session;

class SessionController extends Controller
{
    public array $data = [];
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {

        return response()->json(Session::with(['case','sessionResult'])->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $token = $request->bearerToken();
        $token_type = PersonalAccessToken::findToken($token)->tokenable_type;

        $data = Validator::make($request->all(), [
           'name' => 'nullable|string',
           'session_date' =>'required|date',
           'session_time' =>'required|date',
           'location'=>'required|string',
           'case_id'=>[
               'required',
               'integer',
               Rule::exists('case_files','id')
                   ->where(function($query) use($token_type){
               $query
                   ->where('lawyer_id',str_contains(strtolower($token_type),'lawyer')
                   ? Auth::user()->id
                   : Auth::user()->lawyer_id);
           })],
           'presiding_judge'=>'required|string',
       ]);
       if ($data->fails()) {
           return response()->json($data->errors(), 400);
       }


       Session::create(array_merge($request->only(['name','session_date','session_time','location','presiding_judge','case_id']),[
           'lawyer_id'=> str_contains(strtolower($token_type),'lawyer')  ? Auth::user()->id : Auth::user()->lawyer_id
       ]));
        return response()->json(['message' => 'Session created successfully.'],201);
    }

    /**
     * Show the specified resource.
     */
    public function show($id): JsonResponse
    {
        //

        return response()->json(Session::with(['case','sessionResult'])->find($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $token = $request->bearerToken();
        $token_type = PersonalAccessToken::findToken($token)->tokenable_type;

        $data = Validator::make($request->all(), [
            'name' => 'sometimes|string',
            'session_date' =>'sometimes|date',
            'session_time' =>'sometimes|date',
            'location'=>'sometimes|string',
            'case_id'=>['required','integer',Rule::exists('case_files','id')->where(function($query) use($token_type){
                $query->where('lawyer_id',str_contains(strtolower($token_type),'lawyer')  ? Auth::user()->id : Auth::user()->lawyer_id);
            })],
            'presiding_judge'=>'sometimes|string',
        ]);
        if ($data->fails()) {
            return response()->json($data->errors(), 400);
        }
        $session = Session::where('id',$id)->first();
        if(!$session) {
            return response()->json(['message' => 'Session not found.'],404);
        }
        $session->update([
            'name' => $request->name ?? $session->name,
            'session_date' => $request->session_date ?? $session->session_date,
            'session_time' => $request->session_time ?? $session->session_time,
            'location' => $request->location ?? $session->location,
            'case_id' => $request->case_id ?? $session->case_id,
            'presiding_judge' => $request->presiding_judge ?? $session->presiding_judge,

        ]);


        return response()->json(['message' => 'Session updated successfully.'],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $session = Session::where('id',$id)->first();
        if(!$session) {
            return response()->json(['message' => 'Session not found.'],404);
        }
        $session->delete();

        return response()->json(['message' => 'Session deleted successfully.'],200);
    }
    public function sessionCase($id)
    {
        return response()->json(Session::with(['case','sessionResult'])->find($id));

    }
}
