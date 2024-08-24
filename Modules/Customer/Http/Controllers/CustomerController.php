<?php

namespace Modules\Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\UserResource;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\Customer\Entities\Customer;
use Modules\Customer\Entities\CustomerAddress;
use Modules\Customer\Entities\CustomerPhone;
use Modules\Customer\Services\CustomerService;
class CustomerController extends Controller
{
    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'Customer' =>
                CustomerResource::collection( Customer::with('lawyers.department')->get())
        ]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_en' => 'required_without:name_ar|string|max:255',
            'name_ar' => 'required_without:name_en|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'code' => 'required|string|max:10|unique:customers,code',
            'personal_id' => 'required|string|size:14',
            'email' => 'required|string|email|max:255|unique:customers,email',
            'gender' => 'required|in:male,female',
            'addresses' => 'required|array',
            'addresses.*' => 'required|string|max:255',
            'phone_numbers' => 'required|array',
            'phone_numbers.*' => 'required|string|max:15',
            'litigationDegree_en' => 'required|string|max:255',
            'litigationDegree_ar' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        $customer = $this->customerService->createCustomer($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Customer created successfully',
            'user' => $customer,
        ], 201);
    }


    /**
     * Show the specified resource.
     */
    public function getCustomerById($id) {
        $customer = Customer::where('id',$id)->first();
        if(!isset($customer)) {
            return response()->json(['message' => 'customer not found']);
        }
        return response()->json([CustomerResource::make($customer)]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name_en' => 'nullable|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'code' => [
                'nullable',
                'string',
                'max:10',
                Rule::unique('users')->ignore($id),
            ],
            'password' => 'nullable|string|min:8',
            'phone_number' => 'nullable|string|max:15',
            'personal_id' => 'nullable|string|size:14',
            'address' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female',
            'litigationDegree_en' => 'nullable|string|max:255',
            'litigationDegree_ar' => 'nullable|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }
        $auth = Auth::user();
        $client = Customer::where('id',$id)->first();
        if (!isset($client)){
            return response()->json([
                'message' => 'customer not found',
            ]);
        }
        $password = $request->filled('password') ? bcrypt($request->password) : $client->password;
        $name_ar = $request->filled('name_ar') ? $request->name_ar : $client->name_ar;
        $name_en = $request->filled('name_en') ? $request->name_en : $client->name_en;
        $code = $request->filled('code') ? $request->code : $client->code;
        $gender = $request->filled('gender') ? $request->gender : $client->gender;
        $litigationDegree_en = $request->filled('litigationDegree_en') ? $request->litigationDegree_en : $client->litigationDegree_en;
        $litigationDegree_ar = $request->filled('litigationDegree_ar') ? $request->litigationDegree_ar : $client->litigationDegree_ar;
        $client->update([
            'name_en' => $name_en,
            'name_ar' => $name_ar,
            'password' => $password,
            'code' => $code,
            'gender' => $gender,
            'litigationDegree_en' => $litigationDegree_en,
            'litigationDegree_ar' => $litigationDegree_ar,
        ]);
        return response()->json([
            'message' => 'user updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $customer = Customer::where('id',$id)->first();
        if(!isset($customer)) {
            return response()->json(['message' => 'customer not found']);
        }
        $customer->delete();
        return response()->json(['message' => 'customer deleted successfully']);
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
//          ->delete();
//        $address = $customer->addresses()->findOrFail($addressId);

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