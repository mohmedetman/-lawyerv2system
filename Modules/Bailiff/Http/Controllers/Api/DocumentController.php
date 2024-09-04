<?php

namespace Modules\Bailiff\Http\Controllers\api;

use App\Helpers\ImageUploader;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Bailiff\Entities\Document;

class DocumentController extends Controller
{
    public array $data = [];
    use  ImageUploader ;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
       $dec = Document::all();
        return response()->json($dec,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $data = Validator::make($request->all(),[
            'case_id' => 'required|exists:case_files,id',
            'document_type' =>'required',
            'document_file' => 'required|file|mimes:pdf',
        ]);

       if($data->fails()){
           return response()->json(['errors'=>$data->errors()],422);
       }
       $file='';
       if ($request->hasFile('document_file')) {
           $file = $this->uploadImage($request->file('document_file'),'document');
       }
       Document::create(array_merge($request->except('document_file'),['document_file'=>$file]));
       return response()->json(['message'=>'Document created'],201);
    }

    /**
     * Show the specified resource.
     */
    public function show($id): JsonResponse
    {
        $doc  = Document::where('id',$id)->first();
        if (is_null($doc)) {
            return response()->json(['message'=>'Document not found'],404);
        }
        return response()->json($doc,200);
        //

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
       $data = Validator::make($request->all(),[
          'case_id' => 'sometimes|exists:case_files,id',
          'document_type' =>'sometimes|required',
          'document_file' => 'sometimes|file|mimes:pdf',
       ]);
       if($data->fails()){
           return response()->json(['errors'=>$data->errors()],422);
       }
       $doc = Document::where('id',$id)->first();
       if (is_null($doc)) {
           return response()->json(['message'=>'Document not found'],404);
       }

       if ($request->hasFile('document_file')) {
           $file = $this->uploadImage($request->file('document_file'),'document',);
       }
       $file ='';
       $doc->update([
           'case_id' => $request->case_id ?? $doc->case_id,
           'document_type' => $request->document_type ?? $doc->document_type,
           'document_file' => !empty($file) ? $file :  $doc->document_file,
       ]);
        return response()->json(['massage'=>'sucess'],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $doc = Document::where('id',$id)->first();
        if (is_null($doc)) {
            return response()->json(['message'=>'Document not found'],404);
        }
        $doc->delete();
        return response()->json(['message'=>'Document deleted'],200);
    }
}
