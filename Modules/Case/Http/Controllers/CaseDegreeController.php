<?php

namespace Modules\Case\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Modules\Case\Entities\CaseDegree;

class CaseDegreeController extends Controller
{
    public function index()
    {
        $caseDegrees = CaseDegree::all();
        return response()->json($caseDegrees);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name_en' => 'required_without:name_ar|string|max:255|unique:case_types,name_en',
            'name_ar' => 'required_without:name_en|string|max:255|unique:case_types,name_ar',
        ] );
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        $caseDegree = CaseDegree::create([   'name_en'=>$request->name_en,
            'name_ar'=>$request->name_ar]);

        return response()->json([
            'message' => 'Case degree created successfully',
            'case_degree' => $caseDegree,
        ], 201);
    }

    public function show($id)
    {
        $caseDegree = CaseDegree::findOrFail($id);
        return response()->json($caseDegree);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255|unique:case_types',
        ] );
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        $caseDegree = CaseDegree::findOrFail($id);
        $caseDegree->update(['name'=>$request->name]);

        return response()->json([
            'message' => 'Case degree updated successfully',
            'case_degree' => $caseDegree,
        ]);
    }

    public function destroy($id)
    {
        $caseDegree = CaseDegree::findOrFail($id);
        $caseDegree->delete();

        return response()->json(['message' => 'Case degree deleted successfully']);
    }
}
