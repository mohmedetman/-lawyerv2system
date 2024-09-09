<?php

namespace Modules\Case\Http\Controllers;

use App\Helpers\ImageUploader;
use App\Http\Controllers\Controller;
use App\Http\Resources\JudgmentResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\PersonalAccessToken;
use Modules\Case\Entities\Judgment;
use function PHPUnit\Framework\isEmpty;

class JudgmentController extends Controller
{
    public array $data = [];
    use ImageUploader ;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json( JudgmentResource::collection(Judgment::with('case')->get()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'case_id' => 'required|exists:case_files,id',
            'judgment_type' => 'required|in:primary,final,absentia',
            'issued_by' => 'required|string|max:255',
            'date_issued' => 'required|date',
            'details' => 'required|string',
            'appeal_possible' => 'string|in:0,1',
            'is_in_absentia' => 'string|in:0,1',
            'notification_date' => 'nullable|date',
            'default_party' => 'nullable|string|max:255',
            'appeal_deadline' => 'nullable|date',
            'is_final' => 'string|in:0,1',
            'finalized_on' => 'nullable|date',
            'file' => 'required',
            'execution_status' => 'required|in:pending,executed,delayed',
        ]);
        $file = '' ;
        if ($request->file) {
            $file = $this->uploadImage($request->file,'judgment');
        }

        $judgment = Judgment::create(array_merge($validatedData,['file'=>$file]));
        return response()->json(['massage'=>'success'],201);
    }

    /**
     * Show the specified resource.
     */
    public function show($id): JsonResponse
    {
        //

        return response()->json(JudgmentResource::make(Judgment::with('case')->find($id)));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
      $token_type = PersonalAccessToken::findToken($request->bearerToken())->tokenable_type;
     $auth = str_contains($token_type,'Lawyer') ? Auth::user()->id : Auth::user()->lawyer_id ;
        $validatedData = $request->validate([
             'case_id' => [ 'sometimes' , Rule::exists('case_files', 'id')
               ->where(function ($query) use ($auth) {
                   $query->where('lawyer_id', $auth);
               })
             ],
            'judgment_type' => 'sometimes|in:primary,final,absentia',
            'issued_by' => 'sometimes|string|max:255',
            'date_issued' => 'sometimes|date',
            'details' => 'sometimes|string',
            'appeal_possible' => 'sometimes|in:0,1',
            'is_in_absentia' => 'sometimes|in:0,1',
            'notification_date' => 'sometimes|date',
            'default_party' => 'sometimes|string|max:255',
            'appeal_deadline' => 'sometimes|date',
            'is_final' => 'sometimes|in:0,1',
            'finalized_on' => 'sometimes|date',
            'file' => 'sometimes',
            'execution_status' => 'sometimes|in:pending,executed,delayed',
        ]);
        $judgment = Judgment::find($id);
        if (!$judgment) {
             return response()->json(['massage'=>'error'],404);
        }
        $file = '' ;
        if ($request->hasFile('file')) {
            $file = $this->uploadImage($request->file('file'), 'judgment',$judgment->file);
        }
        $judgment->update(
            array_merge($validatedData,['file'=> empty($file) ? $judgment->file : $file])
        );
        return response()->json(['massage'=>'success'],201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        return response()->json($this->data);
    }
    public function judgmentCase($id)
    {
        return response()->json(JudgmentResource::collection(Judgment::with('case')->where('case_id',$id)->get()));

    }
}
