<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Employer;

class EmployerController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'company_name' => 'required',
            'username' => 'required|unique:employers',
            'password' => 'required|min:8',
        ]);

        $employer = Employer::create([
            'company_name'   => $request->company_name,
            'industry'       => $request->industry,
            'business_type'  => $request->business_type,
            'description'    => $request->description,

            'office_address' => $request->office_address,
            'city'           => $request->city,
            'barangay'       => $request->barangay,

            'contact_person' => $request->contact_person,
            'mobile_number'  => $request->mobile_number,
            'email'          => $request->email,

            'username'       => $request->username,
            'password'       => Hash::make($request->password),

            // Auto set status to Pending
            'status'         => 'Pending',
        ]);

        return response()->json([
            'message' => 'Employer registered successfully',
            'data' => $employer
        ]);
    }
}