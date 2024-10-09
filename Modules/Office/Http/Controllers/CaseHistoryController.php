<?php

namespace Modules\Office\Http\Controllers;

use App\Helpers\TokenType;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Office\Entities\CaseHistory;

class CaseHistoryController extends Controller
{
    use TokenType;

    public array $data = [];

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(CaseHistory::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $data = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'date' => 'required|date',
        ]);
      if ($data->fails()) {
          return response()->json(['errors' => $data->errors()->all()]);
      }
      $auth = $this->generateToken();

        CaseHistory::create
        (
            array_merge(
                $request->all() , ['lawyer_id'=> $auth=='lawyer' ? Auth::user()->id : Auth::user()->lawyer_id]
            )
        );
        return response()->json(['message' => 'Case History created successfully']);
    }

    /**
     * Show the specified resource.
     */
    public function show($id): JsonResponse
    {
        //

        return response()->json(CaseHistory::find($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $data = Validator::make($request->all(), [
            'title' => 'sometimes|required',
            'description' => 'sometimes',
            'date' => 'sometimes|date',
        ]);
        if ($data->fails()) {
            return response()->json(['errors' => $data->errors()->all()]);
        }
        $case_history = CaseHistory::where('id', $id)->first();
        if ($case_history==null) {
            return response()->json(['message' => 'Case History not found'],404);
        }
        $case_history->update([
            'title' => $request->title ?? $case_history->title,
            'description' => $request->description ?? $case_history->description,
            'date' => $request->date ?? $case_history->date,
        ]);

        return response()->json(['message' => 'Case History updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $case_history = CaseHistory::where('id', $id)->first();
        if ($case_history==null) {
            return response()->json(['message' => 'Case History not found'],404);
        }
        $case_history->delete();
        return response()->json(['message' => 'Case History deleted successfully']);
    }
}
