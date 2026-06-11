@extends('layouts.employer')

@section('title', 'Job Creation')
@section('page-title', 'Job Creation')

@section('content')
    <div class="table-section">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-1">Add New Job</h5>
                <p class="text-muted mb-0">Fill in the job details below to publish a new opening.</p>
            </div>
            <a href="{{ route('employer.job') }}" class="btn btn-outline-secondary">Back to Jobs</a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('employer.add_job.store') }}" method="POST">
            @csrf
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Job Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" placeholder="Enter job title" value="{{ old('title') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Country <span class="text-danger">*</span></label>
                    <select name="country" class="form-select" required>
                        <option value="" disabled {{ old('country') ? '' : 'selected' }}>Select country</option>
                        @foreach(['United States','Canada','United Kingdom','Australia','Germany','France','Philippines','India','Singapore'] as $country)
                            <option value="{{ $country }}" {{ old('country') === $country ? 'selected' : '' }}>{{ $country }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">Job Description <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control" rows="5" placeholder="Write a brief description of the role" required>{{ old('description') }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Job Details <span class="text-danger">*</span></label>
                    <select name="job_type" class="form-select" required>
                        <option value="" disabled {{ old('job_type') ? '' : 'selected' }}>Select job type</option>
                        <option value="permanent" {{ old('job_type') === 'permanent' ? 'selected' : '' }}>Permanent</option>
                        <option value="fulltime" {{ old('job_type') === 'fulltime' ? 'selected' : '' }}>Fulltime</option>
                        <option value="parttime" {{ old('job_type') === 'parttime' ? 'selected' : '' }}>Parttime</option>
                        <option value="fixed-term" {{ old('job_type') === 'fixed-term' ? 'selected' : '' }}>Fixed Term</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Salary <span class="text-muted">(optional)</span></label>
                    <input type="text" name="salary" class="form-control" placeholder="Enter salary range or amount" value="{{ old('salary') }}">
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('employer.job') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Job</button>
            </div>
        </form>
    </div>
@endsection