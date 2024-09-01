<?php

namespace Modules\Customer\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Modules\Customer\Entities\Customer;
use Modules\Customer\Entities\CustomerAddress;
use Modules\Customer\Entities\CustomerPhone;

class CustomerDetailController extends Controller
{
    public function addCustomerAddress(Request $request,$customer_id)
    {
        $validator = Validator::make($request->all(), [
            'address' => 'required',
        ]);
      if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }
      CustomerAddress::create([
          'customer_id' => $customer_id,
          'address'=>$request->address,
      ]);
      return response()->json(201);
    }
    public function addCustomerPhone(Request $request,Customer $customer_id){
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        CustomerPhone::create([
            'customer_id' => $customer_id->id,
            'phone_number'=>$request->phone,
        ]);
        return response()->json(201);
    }
    public function editPhone(Request $request, $phoneId)
    {
        $customer_phone = CustomerPhone::where('id',$phoneId)
            ->first();
        if (!isset($customer_phone)) {
            return response()->json(['message' => 'customer_phone not found']);
        }
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        $customer_phone->update(['phone_number' => $request->phone]);
        return response()->json(['success' => true, 'message' => 'Address updated successfully']);
    }
    public function deletePhone( $phoneId)
    {
        $customer_phone = CustomerPhone::where('id',$phoneId)
            ->first();
        if (!isset($customer_phone)) {
            return response()->json(['message' => 'customer_phone not found']);
        }
        $customer_phone->delete();
        return response()->json(['success' => true, 'message' => 'Phone number deleted successfully']);
    }
    public function editAddress(Request $request, $addressId)
    {
        $customer_address = CustomerAddress::where('id',$addressId)
            ->first();
        if (!isset($customer_address)) {
            return response()->json(['message' => 'address not found']);
        }
        $request->validate([
            'address' => 'required|string|max:255',
        ]);
        $customer_address->update(['address' => $request->address]);

        return response()->json(['success' => true, 'message' => 'Address updated successfully']);
    }
    public function deleteAddress( $addressId)
    {
        $customer_address = CustomerAddress::where('id',$addressId)
            ->first();
        if (!isset($customer_address)) {
            return response()->json(['message' => 'address not found']);
        }
        $customer_address->delete();

        return response()->json(['success' => true, 'message' => 'Address deleted successfully']);
    }
}
