<?php

namespace Modules\Office\Http\Controllers;

use App\Helpers\TokenType;
use App\Http\Controllers\Controller;
use App\Http\Resources\LawOfficeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use Modules\Office\Entities\LawOffice;

class OfficeLawController extends Controller
{
    public array $data = [] ;
    use TokenType ;
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
       $law_office = LawOffice::all();
        return response()->json(LawOfficeResource::collection($law_office));
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
       $auth = $this->generateToken();
        LawOffice::updateOrCreate(
            [
                'lawyer_id' => $auth == 'lawyer' ? Auth::user()->id : Auth::user()->lawyer_id
            ],
            [
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'specializations' => $specializations,
                'history'=> $request->input('history'),
                'phones' => $phones,
                'lawyer_id' => $auth == 'lawyer' ? Auth::user()->id : Auth::user()->lawyer_id
            ]
        );

        return response()->json(['massage'=>'success'],201);

    }

    /**
     * Show the specified resource.
     */
    public function show($id): JsonResponse
    {

        $law_office = LawOffice::where('id',$id)->first();
        return response()->json(LawOfficeResource::collection($law_office));
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
        LawOffice::destroy($id);
        return response()->json(['massage'=>'success'],200);
    }
    public function getLawyerOffice()
    {
        $auth = $this->generateToken();
        return response()->json(LawOfficeResource::make(
            LawOffice::where('lawyer_id',$auth=='lawyer'
                ?
                Auth::user()->id : Auth::user()->lawer_id)->first()));

    }

}
