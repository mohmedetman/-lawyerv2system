<?php

namespace Modules\PowerAttorney\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\PowerAttorney\Entities\PowerAttrotney;

class PowerAttorneyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'alphabetic_classification' => ['required','string',
                Rule::unique('power_attrotneys')->where(function ($query) use ($request) {
                    return $query->where('alphabetic_classification', $request->alphabetic_classification);
                }),
            ],
            'numeric_classification' => [
             'nullable',
            'integer',
                Rule::unique('power_attrotneys')->where(function ($query) use ($request) {
                    return $query->where('numeric_classification', $request->numeric_classification);
                }),
            ],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
        PowerAttrotney::create($request->all());

    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('powerattorney::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('powerattorney::edit');
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
