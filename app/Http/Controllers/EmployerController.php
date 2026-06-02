<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Employer;
use App\Models\EmployerDocument;

class EmployerController extends Controller
{
    public function index()
    {
        $employers = Employer::orderBy('created_at', 'desc')->get();

        return response()->json($employers);
    }

    public function register(Request $request)
    {
        $request->validate([
            'company_name' => 'required',
            'username' => 'required|unique:employers',
            'password' => 'required|min:8',
            'company_logo' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'business_permit' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'dti_sec' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'bir_certificate' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'municipal_permit' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'valid_id' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        $companyLogoPath = null;
        if ($request->hasFile('company_logo')) {
            $companyLogoPath = $request->file('company_logo')->store('employer_logos', 'public');
        }

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
            'company_logo'   => $companyLogoPath,

            // Auto set status to Pending
            'status'         => 'Pending',
        ]);

        $documentData = [];
        foreach (['business_permit', 'dti_sec', 'bir_certificate', 'municipal_permit', 'valid_id'] as $field) {
            if ($request->hasFile($field)) {
                $documentData[$field] = $request->file($field)->store("employer_documents/{$field}", 'public');
            }
        }

        EmployerDocument::create(array_merge([
            'employer_id' => $employer->id,
        ], $documentData));

        return response()->json([
            'message' => 'Employer registered successfully',
            'data' => $employer
        ]);
    }
}