<?php

namespace Modules\Case\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\Case\Entities\CaseType;

class CaseTypeController extends Controller
{
    public function index()
    {
        $caseTypes = CaseType::all();
        return response()->json($caseTypes);
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

        $caseType = CaseType::create([
            'name_en'=>$request->name_en,
            'name_ar'=>$request->name_ar
        ]);

        return response()->json([
            'message' => 'Case type created successfully',
            'case_type' => $caseType,
        ], 201);
    }

    public function show($id)
    {
        $caseType = CaseType::findOrFail($id);
        return response()->json($caseType);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'name_en' => 'nullable|string|max:255|unique:case_types,name_en,' . $id,
            'name_ar' => 'nullable|string|max:255|unique:case_types,name_ar,' . $id,
        ] );
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }


        $caseType = CaseType::findOrFail($id);
        $caseType->update([
            'name_en' => $request->name_en ?? $caseType->name_en,
            'name_ar' => $request->name_ar ?? $caseType->name_ar,
        ]);

        return response()->json([
            'message' => 'Case type updated successfully',
            'case_type' => $caseType,
        ]);
    }

    public function destroy($id)
    {
        $caseType = CaseType::find($id);
//        dd()
        if (!$caseType) {
            return response()->json(['message' => 'Case type not found'], 404);
        }
        $caseType->delete();

        return response()->json(['message' => 'Case type deleted successfully']);
    }
}
