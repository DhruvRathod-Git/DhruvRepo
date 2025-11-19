@include('layout.sidebar')

<head>
    <title>Employee Homepage</title>
    @include('layout.css')
</head>

<body class="bg-light" style="margin-left: 200px;">

    <div class="container-fluid py-5 mt-5">

        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <div class="card shadow-sm border-0 rounded-4 p-5 bg-white">
                    <h2 class="fw-bold text-primary mb-3">
                        Hello, <span class="text-dark">{{ Auth::user()->name }}</span> 👋
                    </h2>
                    <p class="text-muted fs-5">
                        Welcome back! Here's a quick overview of your attendance and leaves.
                    </p>
                </div>
            </div>
        </div>

        <div class="row g-4 justify-content-center mb-5">

            {{-- 
            <div class="col-md-6 col-lg-3">
                <div class="card shadow-sm border-0 rounded-4 h-100 hover-shadow">
                    <div class="card-body text-center py-5">
                        <h5 class="text-primary fw-bold mb-3">Your Leaves Stats</h5>
                        <p class="text-muted mb-2">Pending / Approved / Rejected Requests</p>
                        <h2 class="fw-bold text-dark">{{ $totalLeaves }}</h2>
                        <a href="/leave/index" class="btn btn-primary rounded-3 mt-4 px-4 py-2">View Your Leaves</a>
                    </div>
                </div>
            </div>
            --}}

        </div>

        {{-- <div class="row justify-content-center">
            <div class="col-lg-6">
                <h4 class="fw-bold text-primary text-center mb-4">Monthly Attendance Stats</h4>
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th>Month</th>
                                    <th>Total Attendances</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @foreach ($monthlyAttendance as $m)
                                <tr>
                                    <td>{{ date('F', mktime(0, 0, 0, $m->month, 1)) }}</td>
                                    <td>{{ $m->total }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-white border-0 text-center py-3">
                        <a href="/att/index" class="btn btn-primary rounded-3 px-4 py-2">View Your Attendance</a>
                    </div>
                </div>
            </div>
        </div> --}}

    </div>

    @include('layout.script')

</body>