@extends('layouts.employer')

@section('title', 'Job Postings')
@section('page-title', 'Job Postings')

@section('content')
    <div class="table-section">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-1">Your Job Postings</h5>
                <p class="text-muted mb-0">Manage active postings with quick actions.</p>
            </div>
            <a href="{{ route('employer.add_job') }}" class="btn btn-primary">Create New Job</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Location</th>
                        <th>Type</th>
                        <th>Salary</th>
                        <th>Posted</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobs as $job)
                        <tr>
                            <td>{{ $job->title }}</td>
                            <td>{{ $job->country }}</td>
                            <td>{{ ucfirst(str_replace('-', ' ', $job->job_type)) }}</td>
                            <td>{{ $job->salary ?: '—' }}</td>
                            <td>{{ $job->created_at->diffForHumans() }}</td>
                            <td class="text-end">
                                <div class="action-dropdown">
                                    <button type="button" class="btn btn-sm btn-outline-secondary action-dropdown-toggle" aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <div class="action-dropdown-menu">
                                        <a href="#" class="dropdown-item"><i class="fa-solid fa-eye me-2"></i>View</a>
                                        <a href="#" class="dropdown-item"><i class="fa-solid fa-pen-to-square me-2"></i>Edit</a>
                                        <form action="{{ route('employer.job.destroy', $job) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger border-0 bg-transparent w-100 text-start px-3 py-2">
                                            <i class="fa-solid fa-trash me-2"></i>Delete
                                        </button>
                                    </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No job postings yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection