@extends('layouts.employer')

@section('title', 'Account Settings')
@section('page-title', 'Account Settings')

@section('content')
    <div class="account-settings">
        <div class="row">
            <div class="col-lg-8">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Edit Account Details</h5>

                        <form method="POST" action="{{ route('employer.account.update') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Company Name</label>
                                <input type="text" class="form-control" value="{{ $employer->company_name }}" disabled>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Owner</label>
                                <input type="text" name="owner" class="form-control @error('owner') is-invalid @enderror"
                                    value="{{ old('owner', $employer->owner) }}"
                                    placeholder="Owner name">
                                @error('owner')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Contact Person</label>
                                <input type="text" name="contact_person"
                                    class="form-control @error('contact_person') is-invalid @enderror"
                                    value="{{ old('contact_person', $employer->contact_person) }}"
                                    placeholder="Contact Person">
                                @error('contact_person')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="{{ $employer->email }}" disabled>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" value="{{ $employer->username }}" disabled>
                            </div>

                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Account Status</h5>
                        <p><strong>Status:</strong> {{ $employer->status }}</p>

                        @php
                            $missingDocs = $employer->status === 'Pending' ? $employer->missingDocuments() : [];
                        @endphp

                        @if ($missingDocs && count($missingDocs))
                            <div class="alert alert-warning">
                                <h6 class="mb-2">Missing Documents</h6>
                                <ul class="mb-0">
                                    @foreach ($missingDocs as $doc)
                                        <li>{{ $doc }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @elseif ($employer->status === 'Pending')
                            <div class="alert alert-info">
                                All required documents have been submitted. Waiting for admin approval.
                            </div>
                        @else
                            <div class="alert alert-success">
                                Your account is {{ $employer->status }}.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
