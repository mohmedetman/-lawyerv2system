<?php

namespace Modules\Lawyer\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Modules\Lawyer\Entities\Employee;
use Modules\Lawyer\Entities\EmployeeAddress;
use Modules\Lawyer\Entities\EmployeePhone;

class EmployeeDeatialController extends Controller
{
    public function addEmployeeAddress(Request $request,$employee_id)
    {
        $validator = Validator::make($request->all(), [
            'address' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
            EmployeeAddress::create([
            'employee_id' => $employee_id,
            'address'=>$request->address,
        ]);
        return response()->json(201);
    }
    public function addEmployeePhone(Request $request,$employee_id){
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
//        dd($request->all());
        EmployeePhone::create([
            'employee_id' => $employee_id,
            'phone_number'=>$request->phone_number,
        ]);
        return response()->json(201);
    }
    public function editPhone(Request $request, $phoneId)
    {
        $employee = EmployeePhone::where('id',$phoneId)
            ->first();
        if (!isset($employee)) {
            return response()->json(['message' => 'employee phone not found']);
        }
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        $employee->update(['phone_number' => $request->phone]);
        return response()->json(['success' => true, 'message' => 'Address updated successfully']);
    }
    public function deletePhone( $phoneId)
    {
        $emp = EmployeePhone::where('id',$phoneId)
            ->first();
        if (!isset($emp)) {
            return response()->json(['message' => 'employe_phone not found']);
        }
        $emp->delete();
        return response()->json(['success' => true, 'message' => 'Phone number deleted successfully']);
    }
    public function editAddress(Request $request, $addressId)
    {
        $EMP = EmployeeAddress::where('id',$addressId)
            ->first();
        if (!isset($EMP)) {
            return response()->json(['message' => 'address not found']);
        }
        $request->validate([
            'address' => 'required|string|max:255',
        ]);
        $EMP->update(['address' => $request->address]);

        return response()->json(['success' => true, 'message' => 'Address updated successfully']);
    }
    public function deleteAddress( $addressId)
    {
        $EMP = EmployeeAddress::where('id',$addressId)
            ->first();
        if (!isset($customer_address)) {
            return response()->json(['message' => 'address not found']);
        }
        $EMP->delete();

        return response()->json(['success' => true, 'message' => 'Address deleted successfully']);
    }
}
