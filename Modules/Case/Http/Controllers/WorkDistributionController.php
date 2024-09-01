<?php

namespace Modules\Case\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Modules\Case\Entities\WorkDistribution;

class WorkDistributionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('case::index');
    }



   function store(Request $request): \Illuminate\Http\JsonResponse
   {
        $validator = Validator::make($request->all(),[
            'employee_id' => 'required|integer|exists:employees,id',
            'case_id' => 'required|integer|exists:cases,id',
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
        return response()->json([]);

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
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
