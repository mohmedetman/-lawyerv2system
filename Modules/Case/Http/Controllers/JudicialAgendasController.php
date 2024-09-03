<?php

namespace Modules\Case\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use Modules\Case\Entities\JudicialAgendas;
use App\Traits\ValidatesRequest;
class JudicialAgendasController extends Controller
{



    public function index()
    {

            $judAge = JudicialAgendas::select(
                'judicial_agendas.next_agenda_date',
                'judicial_agendas.id as id',
                'judicial_agendas.previous_agenda_date',
                'judicial_agendas.actions as actions',
                'case_files.court_ar',
                'case_files.court_en',
                'case_files.case_type_id',
                'case_files.case_degree_id',
                'customers.name_en as customer_name_en',
                'customers.name_ar as customer_name_ar',
                'case_types.name_en as case_type_name_en',
                'case_types.name_ar as case_type_name_ar',
                'case_degrees.name_en as case_degree_name_en',
                'case_degrees.name_ar as case_degree_name_ar',
                DB::raw("(SELECT customer_phones.phone_number FROM customer_phones WHERE customer_phones.customer_id = customers.id LIMIT 1) as phone_number")

            )
                ->join('case_files', 'case_files.id', '=', 'judicial_agendas.case_id')
                ->join('customers', 'customers.id', '=', 'case_files.customer_id')
                ->join('case_types','case_types.id','=','case_files.case_type_id')
                ->join('case_degrees','case_degrees.id','=','case_files.case_degree_id')
                ->get();
        return \Modules\Case\Transformers\JudicialAgendas::collection($judAge);
    }

    public function store(Request $request)
    {
        $token = request()->bearerToken();
        $personal_token = PersonalAccessToken::find($token);
        $model_instance = $personal_token->tokenable;
        $model_class = get_class($model_instance);
        $validator = Validator::make($request->all(), [
            'case_id' => 'required|integer|exists:case_files,id',
            'next_agenda_date' => 'required_without:previous_agenda_date|date_format:Y-m-d H:i',
            'previous_agenda_date' => 'required_without:next_agenda_date|date_format:Y-m-d H:i',
            'notes' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
        $agenda = JudicialAgendas::create(array_merge($request->all(),
            [
                'lawyer_id'=>str_contains($model_class, 'Lawyer') ? Auth::user()->id : Auth::user()->lawyer_id ,
                'model_type'=>$model_class,
                'employee_id'=>str_contains($model_class, 'Employee') ? Auth::user()->id : null]));
        return response()->json($agenda, 201);
    }

    public function show($id)
    {
        $result = JudicialAgendas::select(
            'judicial_agendas.next_agenda_date',
            'judicial_agendas.id as id',
            'judicial_agendas.previous_agenda_date',
            'judicial_agendas.actions as actions',
            'case_files.court_ar',
            'case_files.court_en',
            'case_files.case_type_id',
            'case_files.case_degree_id',
            'customers.name_en as customer_name_en',
            'customers.name_ar as customer_name_ar',
            'case_types.name_en as case_type_name_en',
            'case_types.name_ar as case_type_name_ar',
            'case_degrees.name_en as case_degree_name_en',
            'case_degrees.name_ar as case_degree_name_ar',
            DB::raw("(SELECT customer_phones.phone_number FROM customer_phones WHERE customer_phones.customer_id = customers.id LIMIT 1) as phone_number")
        )
            ->join('case_files', 'case_files.id', '=', 'judicial_agendas.case_id')
            ->join('customers', 'customers.id', '=', 'case_files.customer_id')
            ->join('case_types','case_types.id','=','case_files.case_type_id')
            ->join('case_degrees','case_degrees.id','=','case_files.case_degree_id')
            ->where('judicial_agendas.id', $id)
            ->first();
        if (!$result) {
            return response()->json(['message' => 'Judicial Agenda not found'], 404);
        }
        return \Modules\Case\Transformers\JudicialAgendas::make($result);
    }


    public function update(Request $request, $id)
    {
        $agenda = JudicialAgendas::find($id);
        if (!$agenda) {
            return response()->json(['message' => 'Judicial Agenda not found'], 404);
        }
        $agenda->update($request->all());
        return response()->json(['massage'=>'succss','agened'=>$agenda], 200);
    }

    public function destroy($id)
    {
        $agenda = JudicialAgendas::find($id);
        if (!$agenda) {
            return response()->json(['message' => 'Judicial Agenda not found'], 404);
        }
        $agenda->delete();
        return response()->json(['massage'=>'sucess'], 200);
    }
}
