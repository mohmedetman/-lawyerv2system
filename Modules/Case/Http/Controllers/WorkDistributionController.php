<?php

namespace Modules\Case\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Rules\CheckAbiltityEmployeeCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\Case\Entities\WorkDistribution;

class WorkDistributionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return WorkDistribution::with(['employee','case'])->get();
    }



   function store(Request $request): \Illuminate\Http\JsonResponse
   {
       $employee_id = $request->input('employee_id');
       $case_id = $request->input('case_id');
       $validator = Validator::make($request->all(),[
           'employee_id' => [new CheckAbiltityEmployeeCase,'integer','required','exists:employees,id'],
            'case_id' => 'required|integer|exists:case_files,id',
            'action'=>'required',
            'notes'=>'required'
        ] );

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ],422);
        }

        WorkDistribution::create($request->all());
        return response()->json(['success'=>'true'],201);

    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('case::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('case::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) : \Illuminate\Http\JsonResponse
    {// Define the validation rules
        $validator = Validator::make($request->all(), [
            'employee_id' => ['sometimes', 'integer', 'exists:employees,id', new CheckAbiltityEmployeeCase],
            'case_id' => ['sometimes', 'integer', 'exists:case_files,id'],
            'action' => ['sometimes', 'string', 'max:255'],
            'notes' => ['sometimes', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }
        $workDistribution = WorkDistribution::where('id',$id);
        if ($workDistribution->first() == null) {
            return response()->json([
                'message' => 'WorkDistribution not found',
            ], 404);
        }
        $workDistribution->update($request->only([
            'employee_id',
            'case_id',
            'action',
            'notes'
        ]));

        return response()->json([
            'success' => true,
            'data' => $workDistribution->with('employee','case')->first(),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $workDistribution = WorkDistribution::where('id',$id);
        if ($workDistribution->first() == null) {
            return response()->json([
                'message' => 'WorkDistribution not found',
            ], 404);
        }
        $workDistribution->delete();
        return response()->json(['success'=>'true'],200);
    }
}
