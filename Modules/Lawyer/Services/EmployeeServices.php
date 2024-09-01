<?php

namespace Modules\Lawyer\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Lawyer\Entities\Employee;

class EmployeeServices
{
    public function  createEmployee(array $data)
    {
//        dd($data);
        return DB::transaction(function () use ($data) {
            $employee = Employee::create([
                'lawyer_id'=>Auth::user()->id,
                'name_en' => $data['name_en'] ?? '',
                'name_ar' => $data['name_ar'] ?? '',
                'password' => Hash::make($data['password']),
                'code' => $data['code'],
                'email' => $data['email'],
                'personal_id' => $data['personal_id'],
                'gender' => $data['gender'],
                'litigationDegree_en' => $data['litigationDegree_en'],
                'litigationDegree_ar' => $data['litigationDegree_ar'],
            ]);
            foreach ($data['addresses'] as $index => $address) {
                $employee->addresses()->create([
                    'address' => $address,
                    'is_primary' => 1,
                ]);
            }
            foreach ($data['phone_numbers'] as $phone) {
                $employee->phones()->create(['phone_number' => $phone]);
            }
            return $employee->load(['addresses', 'phones']);
        });
    }
}
