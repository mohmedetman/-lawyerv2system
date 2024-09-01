<?php

namespace Modules\Customer\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Customer\Entities\Customer;

class CustomerService
{
    public function  createCustomer(array $data)
    {
        return DB::transaction(function () use ($data) {
            $customer = Customer::create([
                'lawyer_id'=>Auth::user()->id,
                'name_en' => $data['name_en'] ?? '',
                'name_ar' => $data['name_ar'] ?? '',
                'password' => Hash::make($data['password']),
                'code' => $data['code'],
                'email' => $data['email'],
                'personal_id' => $data['personal_id'],
                'gender' => $data['gender'],
//                'litigationDegree_en' => $data['litigationDegree_en'],
//                'litigationDegree_ar' => $data['litigationDegree_ar'],
            ]);
            foreach ($data['addresses'] as $index => $address) {
                $isPrimary = $index == 0 ? true : false;
                $customer->addresses()->create([
                    'address' => $address,
                    'is_primary' => $isPrimary,
                ]);
            }
            foreach ($data['phone_numbers'] as $phone) {
                $customer->phones()->create(['phone_number' => $phone]);
            }
//            $customer->lawyers()->attach(Auth::user()->id);

            return $customer->load(['addresses', 'phones']);
        });
    }

    public function updateCustomer(Customer $customer, array $data)
    {
        return DB::transaction(function () use ($customer, $data) {
            $customer->update([
                'name_en' => $data['name_en'] ?? $customer->name_en,
                'name_ar' => $data['name_ar'] ?? $customer->name_ar,
                'email' => $data['email'] ?? $customer->email,
                'gender' => $data['gender'] ?? $customer->gender,
            ]);

            // Update addresses
            if (isset($data['addresses'])) {
                foreach ($data['addresses'] as $addressData) {
                    if (isset($addressData['is_primary']) && $addressData['is_primary']) {
                        $customer->addresses()->update(['is_primary' => false]);
                    }
                    $customer->addresses()->create($addressData);
                }
            }

            // Update phones
            if (isset($data['phones'])) {
                $customer->phones()->delete(); // Assuming you want to replace all phones
                foreach ($data['phones'] as $phone) {
                    $customer->phones()->create(['phone_number' => $phone]);
                }
            }

            return $customer->load(['addresses', 'phones']);
        });
    }

    public function deleteCustomer(Customer $customer)
    {
        return DB::transaction(function () use ($customer) {
            $customer->delete();
            return true;
        });
    }
    public function getAllCustomer()
    {
        return Customer::all();
    }
}
