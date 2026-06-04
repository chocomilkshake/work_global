<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Employer;
use App\Models\EmployerDocument;

class EmployerController extends Controller
{
    /**
     * Retrieve all employers with their documents and format data for API response
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // DEBUG: Fetch all employers with their related documents, ordered by newest first
        $employers = Employer::with('documents')->orderBy('created_at', 'desc')->get();

        // DEBUG: Transform employer data into array format with document URLs
        $data = $employers->map(function ($employer) {
            // DEBUG: Get related document record for this employer
            $document = $employer->documents;

            // DEBUG: Build response array with employer basic info
            return [
                'id' => $employer->id,
                'company_name' => $employer->company_name,
                'company_logo' => $employer->company_logo ? (str_starts_with($employer->company_logo, 'http') ? $employer->company_logo : asset('storage/' . $employer->company_logo)) : null,
                'contact_person' => $employer->contact_person,
                'username' => $employer->username,
                'status' => $employer->status,
                'expires_at' => $employer->expires_at,
                // DEBUG: Map all 5 required documents with file URLs generated using asset() helper
                'documents' => [
                    // DEBUG: Business Permit - check if document exists before generating URL
                    'business_permit' => $document && $document->business_permit ? [
                        'name' => basename($document->business_permit),
                        'url' => asset('storage/' . $document->business_permit),
                    ] : null,
                    // DEBUG: DTI/SEC Document
                    'dti_sec' => $document && $document->dti_sec ? [
                        'name' => basename($document->dti_sec),
                        'url' => asset('storage/' . $document->dti_sec),
                    ] : null,
                    // DEBUG: BIR Certificate
                    'bir_certificate' => $document && $document->bir_certificate ? [
                        'name' => basename($document->bir_certificate),
                        'url' => asset('storage/' . $document->bir_certificate),
                    ] : null,
                    // DEBUG: Municipal Permit
                    'municipal_permit' => $document && $document->municipal_permit ? [
                        'name' => basename($document->municipal_permit),
                        'url' => asset('storage/' . $document->municipal_permit),
                    ] : null,
                    // DEBUG: Valid ID Document
                    'valid_id' => $document && $document->valid_id ? [
                        'name' => basename($document->valid_id),
                        'url' => asset('storage/' . $document->valid_id),
                    ] : null,
                ],
            ];
        });

        // DEBUG: Return formatted employer data as JSON
        return response()->json($data);
    }

    /**
     * Register new employer with documents and company logo
     * Validates all 5 required documents and optional logo
     * @param Request $request - Contains form data and uploaded files
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // DEBUG: Validate all incoming form data and files
        $request->validate([
            // DEBUG: Validate company details
            'company_name' => 'required',
            'username' => 'required|unique:employers',
            'password' => 'required|min:8',
            // DEBUG: Logo is optional but must be image if provided
            'company_logo' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            // DEBUG: All 5 documents are required - validate file type and size
            'business_permit' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'dti_sec' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'bir_certificate' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'municipal_permit' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'valid_id' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        // DEBUG: Initialize logo variables
        $companyLogoPath = null;
        $companyLogoUrl = null;

        // DEBUG: Handle company logo upload if provided
        if ($request->hasFile('company_logo')) {
            // DEBUG: Create employer_logos directory in public storage
            Storage::disk('public')->makeDirectory('employer_logos');
            // DEBUG: Store logo file and get path
            $companyLogoPath = $request->file('company_logo')->store('employer_logos', 'public');
            // DEBUG: Generate public URL using asset() helper (replaces Storage::disk()->url())
            $companyLogoUrl = asset('storage/' . $companyLogoPath);
        }

        // DEBUG: Create employer record in database with all company information
        $employer = Employer::create([
            // DEBUG: Company basic information
            'company_name'   => $request->company_name,
            'industry'       => $request->industry,
            'business_type'  => $request->business_type,
            'description'    => $request->description,

            // DEBUG: Company address details
            'office_address' => $request->office_address,
            'city'           => $request->city,
            'barangay'       => $request->barangay,

            // DEBUG: Contact information
            'contact_person' => $request->contact_person,
            'mobile_number'  => $request->mobile_number,
            'email'          => $request->email,

            // DEBUG: Authentication credentials
            'username'       => $request->username,
            'password'       => Hash::make($request->password),
            // DEBUG: Store company logo public URL
            'company_logo'   => $companyLogoUrl,

            // DEBUG: Auto set status to Pending (requires admin approval)
            'status'         => 'Pending',
        ]);

        // DEBUG: Initialize document data array for storing file paths
        $documentData = [];
        // DEBUG: Create parent employer_documents directory
        Storage::disk('public')->makeDirectory('employer_documents');

        // DEBUG: Process and store all 5 required documents
        foreach (['business_permit', 'dti_sec', 'bir_certificate', 'municipal_permit', 'valid_id'] as $field) {
            // DEBUG: Check if document file was uploaded
            if ($request->hasFile($field)) {
                // DEBUG: Create subdirectory for each document type
                Storage::disk('public')->makeDirectory("employer_documents/{$field}");
                // DEBUG: Store file and get path, add to documentData array
                $documentData[$field] = $request->file($field)->store("employer_documents/{$field}", 'public');
            }
        }

        // DEBUG: Create EmployerDocument record with employer_id and all 5 document paths
        EmployerDocument::create(array_merge([
            'employer_id' => $employer->id,
        ], $documentData));

        // DEBUG: Return success response with employer data to frontend
        return response()->json([
            'message' => 'Employer registered successfully',
            'data' => $employer
        ]);
    }

    /**
     * Approve an employer registration
     */
    public function approve($id)
    {
        $employer = Employer::findOrFail($id);
        $employer->status = 'Approved';
        $employer->save();

        return response()->json(['message' => 'Employer approved successfully']);
    }

    /**
     * Reject an employer registration
     */
    public function reject($id)
    {
        $employer = Employer::findOrFail($id);
        $employer->status = 'Rejected';
        $employer->save();

        return response()->json(['message' => 'Employer rejected successfully']);
    }

    /**
     * Reject a specific employer document and set status back to pending
     */
    public function rejectDocument(Request $request, $id)
    {
        $request->validate([
            'document_key' => 'required|in:business_permit,dti_sec,bir_certificate,municipal_permit,valid_id',
        ]);

        $employer = Employer::findOrFail($id);
        $document = $employer->documents;

        if (! $document) {
            return response()->json(['message' => 'Employer documents not found.'], 404);
        }

        $field = $request->input('document_key');

        if ($document->$field) {
            Storage::disk('public')->delete($document->$field);
            $document->$field = null;
            $document->save();
        }

        $employer->status = 'Pending';
        $employer->save();

        return response()->json(['message' => 'Document rejected. Employer is now pending revision.']);
    }
}