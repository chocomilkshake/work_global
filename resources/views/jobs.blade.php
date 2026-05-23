@extends('layouts.app')

@section('title', 'Home')

@section('content')

<div class="main-wrapper">

    <!-- Your page content here -->
      {{-- LEFT SIDE --}}
        <div class="job-list-panel">

            <div class="job-list-header">
                <h3>Available Jobs</h3>
            </div>

            <div class="job-card active"
                data-title="Laravel Developer"
                data-company="CSNK Manpower Agency"
                data-location="Naic, Cavite"
                data-description="Develop Laravel systems."
                data-logo="fa-code">

                <div class="d-flex gap-3">

                    <div class="company-logo">
                        <i class="fa fa-code"></i>
                    </div>

                    <div>
                        <div class="job-title">
                            Laravel Developer
                        </div>

                        <div class="company-name">
                            CSNK Manpower Agency
                        </div>
                    </div>

                </div>

            </div>

            <div class="job-card"
                data-title="UI/UX Designer"
                data-company="Zeslife Marketing"
                data-location="Manila"
                data-description="Create modern UI design."
                data-logo="fa-paint-brush">

                <div class="d-flex gap-3">

                    <div class="company-logo bg-success">
                        <i class="fa fa-paint-brush"></i>
                    </div>

                    <div>
                        <div class="job-title">
                            UI/UX Designer
                        </div>

                        <div class="company-name">
                            Zeslife Marketing
                        </div>
                    </div>

                </div>

            </div>

        </div>

        {{-- RIGHT SIDE --}}
        <div class="job-detail-panel">

            <div class="detail-card">

                <div class="detail-header">

                    <div class="detail-left">

                        <div class="detail-logo" id="detailLogo">
                            <i class="fa fa-code"></i>
                        </div>

                        <div>

                            <div class="detail-title" id="detailTitle">
                                Laravel Developer
                            </div>

                            <div class="detail-company" id="detailCompany">
                                CSNK Manpower Agency • Naic, Cavite
                            </div>

                        </div>

                    </div>

                    <button class="apply-btn">
                        Apply Now
                    </button>

                </div>

                <div class="section-title">
                    Job Description
                </div>

                <div class="job-description" id="detailDescription">
                    Develop Laravel systems.
                </div>

            </div>

        </div>

</div>

@endsection