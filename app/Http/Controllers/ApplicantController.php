<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Applicant;
use Illuminate\Support\Facades\Hash;

class ApplicantController extends Controller
{
    public function store(Request $request)
    {
        // Validate
        $request->validate([

            'first_name' => 'required',
            'last_name' => 'required',

            'street_no' => 'required',
            'full_address' => 'required',

            'email' => 'required|email',

            // NEW
            'username' => 'required|unique:applicants,username',
            'password' => 'required|min:8',

            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png',
            'resume' => 'nullable|mimes:pdf,doc,docx',
        ]);

        // Profile upload
        $profileImage = null;

        if ($request->hasFile('profile_image')) {

            $profileImage = $request
                ->file('profile_image')
                ->store('profiles', 'public');
        }

        // Resume upload
        $resume = null;

        if ($request->hasFile('resume')) {

            $resume = $request
                ->file('resume')
                ->store('resumes', 'public');
        }

        // Save to database
        Applicant::create([

            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,

            'street_no' => $request->street_no,
            'full_address' => $request->full_address,
            'region' => $request->region,
            'city' => $request->city,
            'barangay' => $request->barangay,

            'contact_number' => $request->contact_number,
            'email' => $request->email,

            // NEW
            'username' => $request->username,

            // HASH PASSWORD
            'password' => Hash::make(
                $request->password
            ),

            'profile_image' => $profileImage,
            'resume' => $resume,

        ]);

        return response()->json([
            'success' => true,
            'message' => 'Applicant registered successfully'
        ]);
    }
}