@extends('layouts.app')

@section('title', 'Jobs')

@section('content')

<div class="main-wrapper">

    <div class="job-list-panel">

        <div class="job-list-header">
            <h3>Available Jobs</h3>
            <input id="jobSearchInput" class="job-search-input form-control mt-3" type="search" placeholder="Search jobs by title, company, or location">
        </div>

        @forelse($jobs as $job)
            @php($logo = $job->logoUrl())
            <div class="job-card {{ $loop->first ? 'active' : '' }}"
                 data-title="{{ strtolower($job->title) }}"
                 data-company="{{ strtolower($job->employer_name ?: optional($job->employer)->company_name ?: 'Employer') }}"
                 data-location="{{ strtolower($job->country) }}"
                 data-description="{{ strtolower($job->description) }}"
                 data-image="{{ $logo ?: '' }}">
                <div class="d-flex gap-3 align-items-center">
                    <div class="company-logo">
                        @if($logo)
                            <img src="{{ $logo }}" alt="{{ $job->employer_name ?? 'Company logo' }}">
                        @else
                            <span>{{ strtoupper(substr($job->title, 0, 1)) }}</span>
                        @endif
                    </div>

                    <div>
                        <div class="job-title">{{ $job->title }}</div>
                        <div class="company-name">{{ $job->employer_name ?: optional($job->employer)->company_name ?: 'Employer' }}</div>
                        <div class="company-location">{{ $job->country }}</div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5 text-muted">No jobs found in the database yet.</div>
        @endforelse

    </div>

    <div class="job-detail-panel">
        @if($jobs->isNotEmpty())
            @php($job = $jobs->first())
            <div class="detail-card">
                <div class="detail-header">
                    <div class="detail-left">
                        <div class="detail-logo" id="detailLogo">
                            @if($logo)
                                <img src="{{ $logo }}" alt="{{ $job->employer_name ?: optional($job->employer)->company_name ?: 'Company logo' }}">
                            @else
                                <span>{{ strtoupper(substr($job->title, 0, 1)) }}</span>
                            @endif
                        </div>
                        <div>
                            <div class="detail-title" id="detailTitle">{{ $job->title }}</div>
                            <div class="detail-company" id="detailCompany">
                                {{ $job->employer_name ?: optional($job->employer)->company_name ?: 'Employer' }} • {{ $job->country }}
                            </div>
                        </div>
                    </div>
                    <button class="apply-btn">Apply Now</button>
                </div>

                <div class="section-title">Job Description</div>
                <div class="job-description" id="detailDescription">{{ $job->description }}</div>
            </div>
        @else
            <div class="detail-card text-center py-5 text-muted">
                No job details available.
            </div>
        @endif
    </div>

</div>

@endsection