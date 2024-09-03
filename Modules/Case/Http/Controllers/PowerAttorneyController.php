<?php

namespace Modules\Case\Http\Controllers;

use App\Helpers\ImageUploader;
use App\Http\Controllers\Controller;
use App\Rules\CheckUniqeClassification;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Modules\Case\Entities\PowerAttorney;

class PowerAttorneyController extends Controller
{
    use ImageUploader ;

    public function index()
    {
        return view('case::index');
    }


    public function create()
    {
        return view('case::create');
    }

    public function store(Request $request)
    {
      $data = \Illuminate\Support\Facades\Validator::make($request->all(), [
          'numeric_classification' => 'required',
          'alphabetic_classification' =>['required',new CheckUniqeClassification],
          'customer_id'=>'required',
          'image'=>'required'
      ]);
      if ($data->fails()) {
          return \response()->json(['errors' => $data->getMessageBag()->toArray()]);
      }
      $image = '' ;
      if ($request->hasFile('image')) {
          $image = $this->uploadImage($request->image,'power_attorney','post');
      }
//      dd('4');
//      dd(PowerAttorney::all());
        PowerAttorney::create([
         'customer_id' => $request->customer_id,
         'numeric_classification'=>$request->numeric_classification,
         'alphabetic_classification'=>$request->alphabetic_classification,
         'image'=>$image?? '',
          'notes'=>$request->notes
     ]);
      return \response()->json(['success' => 'Power Attorney added successfully.']);

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
