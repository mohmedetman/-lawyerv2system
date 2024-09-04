<?php

namespace Modules\Bailiff\Http\Controllers\api;

use App\Helpers\TokenType;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use Modules\Bailiff\Entities\Bailiff;

class BailiffController extends Controller
{
//    public array $data = [];
use TokenType ;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $data = Bailiff::all();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
           $data = Validator::make($request->all(), [
              'name' => 'required',
              'email' => 'required|email|unique:bailiffs,email',
              'phone_number' => 'required',
          ]);
          if ($data->fails()) {
              return response()->json(['errors' => $data->errors()->all()]);
          }
          Bailiff::create(
              array_merge($request->all(),
                  ['lawyer_id'=>$this->generateToken()=='lawyer'?Auth::user()->id : Auth::user()->lawyer_id]));
        return response()->json(['massage' => 'success']);
    }

    /**
     * Show the specified resource.
     */
    public function show($id): JsonResponse
    {
        $biff = Bailiff::where('id', $id)->first();
        if (!$biff) {
            return response()->json(['massage' => 'not found'],404);
        }

        return response()->json($biff);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = Validator::make($request->all(), [
            'name' => 'sometimes|required',
            'email' => 'sometimes|required|email|unique:bailiffs,email,' . $id,
            'phone' => 'sometimes|required',
        ]);
        if ($data->fails()) {
            return response()->json(['errors' => $data->errors()->all()],404);
        }
        $biff = Bailiff::where('id', $id)->first();
        if (!$biff) {
            return response()->json(['massage' => 'not found'],404);
        }
        $biff->update($request->all());
        return response()->json(['massage'=>'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $biff = Bailiff::where('id', $id)->first();
        if(!$biff){
            return response()->json(['massage'=>'not found'],404);
        }
        $biff = Bailiff::destroy($id);
        return response()->json(['massage'=>'success'],200);
    }
}
