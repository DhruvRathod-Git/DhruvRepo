@include('layout.sidebar')

<head>
    <title>Employee Dashboard</title>
    @include('layout.css')
</head>

<body class="bg-light" style="margin-left: 200px; overflow-x: hidden;">

<div class="container-fluid py-5">

    <div class="row justify-content-center mb-5">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-body text-center py-5">
                    <h2 class="fw-bold text-primary mb-2">
                        Hello, <span class="text-dark">{{ Auth::user()->name }}</span> 👋
                    </h2>
                    <p class="text-secondary fs-6 mb-0">
                        Welcome back! Here's a quick overview of your company's employee statistics.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 justify-content-center mb-5">

        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 h-100 hover-scale">
                <div class="card-body text-center py-5">
                    <h5 class="text-primary fw-bold mb-2">Employees Leaves Stats</h5>
                    <p class="text-muted mb-3">Pending / Approved / Rejected Requests</p>
                    <h2 class="fw-bold text-dark">{{ $totalLeaves }}</h2>
                    <div class="mt-4">
                        <a class="btn btn-primary btn-lg rounded-3" href="/leave/index">
                            <i class="bi bi-journal-check me-2"></i> View Leaves
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 h-100 hover-scale">
                <div class="card-body text-center py-5">
                    <h5 class="fw-bold text-success mb-2">Salary Summary</h5>
                    <p class="text-muted mb-3">Total Salary Paid</p>
                    <h2 class="fw-bold text-dark">{{ $totalSalaryPaid }}</h2>
                    <div class="mt-4">
                        <a class="btn btn-success btn-lg rounded-3" href="/salary/index">
                            <i class="bi bi-wallet2 me-2"></i> View Salary Sheet
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h4 class="fw-bold text-primary text-center mb-4">Monthly Attendance Stats</h4>
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle text-center">
                            <thead class="table-primary">
                                <tr>
                                    <th>Month</th>
                                    <th>Total Attendances</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($monthlyAttendance as $m)
                                <tr>
                                    <td>{{ date('F', mktime(0, 0, 0, $m->month, 1)) }}</td>
                                    <td>{{ $m->total }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 text-center py-3">
                    <a href="/att/index" class="btn btn-primary rounded-3 px-4 py-2">View Your Attendance</a>
                </div>
            </div>
        </div>
    </div>

</div>

@include('layout.script')
</body>
