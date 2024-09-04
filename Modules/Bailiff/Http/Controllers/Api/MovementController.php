<?php

namespace Modules\Bailiff\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Bailiff\Entities\Document;
use Modules\Bailiff\Entities\Movement;
use Modules\Bailiff\Transformers\MovementResource;

class MovementController extends Controller
{
    public array $data = [];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movements = Movement::with(['document','bailiff'])->get();
        return response()->json(MovementResource::collection($movements));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $data = Validator::make($request->all(), [
            'document_id' => 'required|exists:documents,id',
            'bailiff_id' =>'required|exists:bailiffs,id',
            'action_taken' => 'required',
            'action_date' => 'required|date',
            'result'=>'required',
        ]);
        if ($data->fails()) {
            return response()->json(['errors' => $data->errors()->all()],404);
        }
        Movement::create($request->all());
        return response()->json(['massage'=>'success'],201);
    }

    /**
     * Show the specified resource.
     */
    public function show($id): JsonResponse
    {
        $movement = Movement::with(['document','bailiff'])->find($id);
        return response()->json(movementResource::make($movement));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $data = Validator::make($request->all(), [
            'document_id' => 'sometimes|exists:documents,id',
            'bailiff_id' =>'sometimes|exists:bailiffs,id',
            'action_taken' => 'sometimes',
            'action_date' => 'sometimes|date',
            'result'=>'sometimes',
        ]);
        if ($data->fails()) {
            return response()->json(['errors' => $data->errors()->all()],404);
        }
        $mov = Movement::where('id', $id)->first();
        if (!$mov) {
            return response()->json(['massage'=>'error'],404);
        }
        $mov->update([
            'document_id' => $request->document_id ?? $mov->document_id,
            'bailiff_id' => $request->bailiff_id ?? $mov->bailiff_id,
            'action_taken' => $request->action_taken ?? $mov->action_taken,
            'action_date' => $request->action_date ?? $mov->action_date,
            'result' => $request->result ?? $mov->result,
        ]);
        return response()->json(['massage'=>'success'],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $mov = Movement::where('id', $id)->first();
        if (!$mov) {
            return response()->json(['massage'=>'not found'],404);
        }
     $mov->delete();
        return response()->json(['massage'=>'success'],200);
    }
    public function getMovementsByCaseId($case_id)
    {
        $move_case = Movement::with(['document' => function($query) use ($case_id) {
            $query->where('documents.case_id', $case_id);
        }])->with('bailiff')->get();
       return response()->json(MovementResource::collection($move_case));
    }
}
