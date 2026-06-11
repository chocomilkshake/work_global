@extends('layouts.employer')

@section('title', 'Employer Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="dashboard-overview">
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="dashboard-card">
                    <div>
                        <h5>Total Job Postings</h5>
                        <h2>12</h2>
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
                        <h2>84</h2>
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
                        <h2>28</h2>
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
                        <h2>59</h2>
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
                                <tr>
                                    <td>Marketing Specialist</td>
                                    <td><span class="badge bg-success">Open</span></td>
                                    <td>18</td>
                                    <td>3 days ago</td>
                                </tr>
                                <tr>
                                    <td>Software Developer</td>
                                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                                    <td>26</td>
                                    <td>6 days ago</td>
                                </tr>
                                <tr>
                                    <td>Customer Success Lead</td>
                                    <td><span class="badge bg-success">Open</span></td>
                                    <td>9</td>
                                    <td>1 week ago</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="table-section">
                    <h5 class="mb-3">Recent Applications</h5>
                    <div class="list-group">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Jorge Williams</strong>
                                <div class="text-muted">Applied for Marketing Specialist</div>
                            </div>
                            <span class="badge bg-primary rounded-pill">New</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Sarah Kim</strong>
                                <div class="text-muted">Applied for Software Developer</div>
                            </div>
                            <span class="badge bg-success rounded-pill">Viewed</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Daniel Reed</strong>
                                <div class="text-muted">Applied for Customer Success Lead</div>
                            </div>
                            <span class="badge bg-warning text-dark rounded-pill">Review</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection