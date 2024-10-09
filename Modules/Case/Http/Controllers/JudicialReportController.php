<?php

namespace Modules\Case\Http\Controllers;

use App\Helpers\ImageUploader;
use App\Helpers\TokenType;
use App\Http\Controllers\Controller;
use App\Http\Resources\JudicialReportResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Case\Entities\JudicialReport;

class JudicialReportController extends Controller
{
    public array $data = [];
    use ImageUploader,TokenType ;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $jr = JudicialReport::all();
        return response()->json(JudicialReportResource::collection($jr));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
       $data  =  Validator::make($request->all(),
        [
            'report_number' => 'required|string|max:255|unique:judicial_reports,report_number',
            'report_date' => 'required|date',
            'report_type' => 'required|in:final,archived',
            'description' => 'nullable|string',
            'status' => 'required|in:active,archived',
            'file' => 'required|file|mimes:pdf,doc,docx',
        ]);
       if ($data->fails()) {
           return response()->json(['errors' => $data->errors()->all()]);
       }
       $auth =static::generateToken();
       $file_path = '' ;
       if ($request->file) {
           $file_path = $this->uploadImage($request->file,'judicial-report');
       }
       JudicialReport::create(
           array_merge($request->except('file'),
               [
                   'lawyer_id' =>$auth=='lawyer'
                   ? Auth::user()->id
                   : Auth::user()->lawyer_id , 'file' => $file_path
               ])
       );
        return response()->json(['massage' => 'success'],201);
    }

    /**
     * Show the specified resource.
     */
    public function show($id): JsonResponse
    {
        return response()->json(JudicialReportResource::make(JudicialReport::find($id)));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        //
        $data  =  Validator::make($request->all(),
            [
                'report_number' => 'sometimes|string|max:255|unique:judicial_reports,report_number',
                'report_date' => 'sometimes|date',
                'report_type' => 'sometimes|in:final,archived',
                'description' => 'sometimes|string',
                'status' => 'sometimes|in:active,archived',
                'file' => 'sometimes|file|mimes:pdf,doc,docx',
            ]);
        if ($data->fails()) {
            return response()->json(['errors' => $data->errors()->all()]);
        }
        $jr = JudicialReport::where('id',$id)->first();
        if (!$jr) {
            return response()->json(['errors' => 'JudicialReport not found'], 404);
        }
        $file_path = '' ;
        if ($request->file) {
            $file_path = $this->uploadImage($request->file,'judicial-report',$jr->file);
        }
        $jr->update(array_merge($request->except('file'),['file' => empty($file_path) ? $jr->file : $file_path]));
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
}
