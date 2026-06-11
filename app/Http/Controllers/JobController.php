<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JobPosting;
use App\Models\Employer;

class JobController extends Controller
{
    public function publicIndex()
    {
        $jobs = JobPosting::with('employer')->orderBy('created_at', 'desc')->get();
        return view('jobs', compact('jobs'));
    }

    public function index()
    {
        $jobs = JobPosting::where('employer_id', Auth::guard('employer')->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('employer.job', compact('jobs'));
    }

    public function create()
    {
        return view('employer.add_job');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'country' => 'required|string|max:100',
            'job_type' => 'required|in:permanent,fulltime,parttime,fixed-term',
            'salary' => 'nullable|string|max:100',
        ]);

        $validated['employer_id'] = Auth::guard('employer')->id();

        JobPosting::create($validated);

        return redirect()->route('employer.job')->with('success', 'Job posting created successfully.');
    }

    public function destroy(JobPosting $job)
    {
        if ($job->employer_id !== Auth::guard('employer')->id()) {
            abort(403);
        }

        $job->delete();

        return redirect()->route('employer.job')->with('success', 'Job posting deleted successfully.');
    }
}
