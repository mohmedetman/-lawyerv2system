<?php

namespace Modules\Office\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Office\Entities\LawOffice;

class OfficeLawController extends Controller
{
    public array $data = [];

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {

        return response()->json($this->data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
       $data = Validator::make($request->all(), [
           'name' => 'required',
           'description' => 'required',
           'phone' => 'required|array',
           'history' =>  'required',
           'specializations'=>'required|array',

       ]);
       if ($data->fails()) {
           return response()->json(['errors' => $data->errors()->all()]);
       }
       $specializations = '' ;
       $phones = '' ;
          collect($request->specializations)->map(function ($specialization)use (&$specializations) {
           $specializations.=$specialization.',';
           });
           collect($request->phone)->map(function ($phone)use (&$phones) {
            $phones.=$phone.',';
           });
        $specializations = rtrim($specializations,',');
        $phones = rtrim($phones,',');
        LawOffice::create(
            $request->all(),
        );
        return response()->json(['massage'=>'success'],201);

    }

    /**
     * Show the specified resource.
     */
    public function show($id): JsonResponse
    {
        //

        return response()->json($this->data);
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
    public function getOfficeLaw(Request $request)
    {

    }
}
