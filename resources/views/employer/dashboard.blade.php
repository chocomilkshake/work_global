@extends('layouts.employer')

@section('title', 'Employer Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="dashboard-overview">
        @php
            $employer = Auth::guard('employer')->user();
            $missingDocs = $employer && $employer->status === 'Pending'
                ? $employer->missingDocuments()
                : [];
        @endphp

        @if ($missingDocs && count($missingDocs))
            <div class="alert alert-warning mb-4">
                <h6 class="mb-2">Account Pending</h6>
                <p class="mb-2">Please upload the following required document(s) to complete your account:</p>
                <ul class="mb-0">
                    @foreach ($missingDocs as $doc)
                        <li>{{ $doc }}</li>
                    @endforeach
                </ul>
            </div>
        @elseif ($employer && $employer->status === 'Pending')
            <div class="alert alert-info mb-4">
                Your account is pending administrator approval. All required documents have been submitted.
            </div>
        @endif

        @php
            $jobs = $jobs ?? collect();
        @endphp

        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="dashboard-card">
                    <div>
                        <h5>Total Job Postings</h5>
                        <h2>{{ $jobs->count() }}</h2>
                    </div>
                    <div class="dashboard-icon">
                        <i class="fa-solid fa-briefcase"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card">
                    <div>
                        <h5>Candidates</h5>
                        <h2>0</h2>
                    </div>
                    <div class="dashboard-icon">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card">
                    <div>
                        <h5>Encoded Candidates</h5>
                        <h2>0</h2>
                    </div>
                    <div class="dashboard-icon">
                        <i class="fa-solid fa-file-import"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card">
                    <div>
                        <h5>Submitted Applications</h5>
                        <h2>0</h2>
                    </div>
                    <div class="dashboard-icon">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="table-section">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Recent Job Postings</h5>
                        <a href="#" class="btn btn-sm btn-primary">Manage Jobs</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Job Title</th>
                                    <th>Status</th>
                                    <th>Applicants</th>
                                    <th>Posted</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($jobs as $job)
                                    <tr>
                                        <td>{{ $job->title }}</td>
                                        <td><span class="badge bg-success">Open</span></td>
                                        <td>0</td>
                                        <td>{{ $job->created_at->diffForHumans() }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            No job postings yet. Add a job to start posting.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="table-section">
                    <h5 class="mb-3">Recent Applications</h5>
                    <div class="list-group">
                        <div class="list-group-item text-center text-muted">
                            No applications yet.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection