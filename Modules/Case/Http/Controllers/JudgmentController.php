<?php

namespace Modules\Case\Http\Controllers;

use App\Helpers\ImageUploader;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Case\Entities\Judgment;

class JudgmentController extends Controller
{
    public array $data = [];
    use ImageUploader ;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(Judgment::with('case')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'case_id' => 'required|exists:case_files,id',
            'judgment_type' => 'required|in:primary,final,absentia',
            'issued_by' => 'required|string|max:255',
            'date_issued' => 'required|date',
            'details' => 'required|string',
            'appeal_possible' => 'string|in:true,false',
            'is_in_absentia' => 'string|in:true,false',
            'notification_date' => 'nullable|date',
            'default_party' => 'nullable|string|max:255',
            'appeal_deadline' => 'nullable|date',
            'is_final' => 'string|in:true,false',
            'finalized_on' => 'nullable|date',
            'execution_status' => 'required|in:pending,executed,delayed',
        ]);
        $file = '' ;
        if ($request->file) {
            $file = $this->uploadImage($request->file,'judgment');
        }

        $judgment = Judgment::create(array_merge($validatedData,['file'=>$file]));
        return response()->json(['massage'=>'success'],201);
    }

    /**
     * Show the specified resource.
     */
    public function show($id): JsonResponse
    {
        //

        return response()->json(Judgment::with('case')->find($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        //

        return response()->json($this->data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        //

        return response()->json($this->data);
    }
    public function judgmentCase($id)
    {

    }
}
