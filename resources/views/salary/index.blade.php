@include('layout.sidebar')
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Salary Sheet</title>
    @include('layout.css')
</head>

<body class="bg-light" style="margin-bottom: 22px; margin-left:250px;">

    <div class="container">
        <div class="card mt-5">
            <h3 class="card-header p-3 text-center fw-bold">
                <i class="bi bi-wallet2"></i> Salary Sheet
            </h3>

            @if (Auth::user()->role === 'admin')
                <div class="p-3 d-flex justify-content-end">
                    <a class="btn btn-success fw-bold rounded-3 me-2" href="javascript:void(0)" id="createNewSalary">
                        <i class="bi bi-plus-lg"></i> Make Payment
                    </a>

                    <a href="/export" class="btn btn-secondary fw-semibold shadow-sm">
                        <i class="bi bi-download"></i> Download Sheet
                    </a>
                </div>
            @else
                <div class="p-3 d-flex justify-content-end">
                    <a href="/export" class="btn btn-success fw-semibold shadow-sm">
                        <i class="bi bi-download"></i> Download Your Sheet
                    </a>
                </div>
            @endif

            <div class="modal fade" id="ajaxModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h4 class="modal-title" id="modelHeading"></h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <form id="salaryForm">
                                @csrf
                                <input type="hidden" id="salary_id" name="salary_id">

                                <div class="mb-3 fw-semibold">
                                    <label>Employee</label>
                                    <select class="form-select" name="employee_id" id="employee_id">
                                        <option value="" disabled selected>Select Employee</option>
                                        @foreach ($employee as $emp)
                                            <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3 fw-semibold">
                                    <label>Salary</label>
                                    <input type="number" class="form-control" id="salary" name="salary">
                                </div>

                                <div class="mb-3 fw-semibold">
                                    <label>PF</label>
                                    <input type="number" class="form-control" id="pf" name="pf">
                                </div>

                                <div class="mb-3 fw-semibold">
                                    <label>Leave Deduction</label>
                                    <input type="number" class="form-control" id="leave_deduction"
                                        name="leave_deduction">
                                </div>

                                <div class="mb-3 fw-semibold">
                                    <label>Month</label>
                                    <select class="form-select" name="month" id="month">
                                        <option selected disabled>Select Month</option>
                                        @foreach (['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $m)
                                            <option value="{{ $m }}">{{ $m }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3 fw-semibold">
                                    <label>Net Salary</label>
                                    <input type="number" class="form-control" id="net_salary" name="net_salary"
                                        readonly>
                                </div>

                                <button type="submit" class="btn btn-primary" id="saveBtn">
                                <i class="bi bi-wallet2"></i> Credit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="editSalaryModal" tabindex="-1" aria-labelledby="editSalaryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editSalaryModalLabel">
                        <i class="bi bi-pencil-square"></i> Edit Salary Details
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="updateSalaryForm" method="POST">
                        @csrf
                        @method('POST')

                        <div class="mb-3">
                            <label for="edit-employee" class="form-label fw-semibold">Employee</label>
                            <select id="edit-employee" name="employee" class="form-select mb-3">
                                @foreach ($employee as $emp)
                                    <option value="{{ $emp->id }}"
                                        {{ ($salary->employee ?? old('employee')) == $emp->name ? 'selected' : '' }}>
                                        {{ $emp->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('employee')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="edit-salary" class="form-label fw-semibold">Salary</label>
                            <input type="number" id="edit-salary" name="salary" class="form-control"
                                value="{{ $salary->salary ?? old('salary') }}">
                            @error('salary')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="edit-pf" class="form-label fw-semibold">PF</label>
                            <input type="number" id="edit-pf" name="pf" class="form-control"
                                value="{{ $salary->pf ?? old('pf') }}">
                            @error('pf')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="edit-leave_deduction" class="form-label fw-semibold">Leave Deduction</label>
                            <input type="number" id="edit-leave_deduction" name="leave_deduction"
                                class="form-control"
                                value="{{ $salary->leave_deduction ?? old('leave_deduction') }}">
                            @error('leave_deduction')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="edit-net_salary" class="form-label fw-semibold">Net Salary</label>
                            <input type="number" id="edit-net_salary" name="net_salary" class="form-control"
                                value="{{ $salary->net_salary ?? old('net_salary') }}" readonly>
                            @error('net_salary')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="edit-month" class="form-label fw-semibold">Month</label>
                            <input type="text" id="edit-month" name="month" class="form-control"
                                value="{{ $salary->month ?? old('month') }}" readonly>
                            @error('month')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary" id="updateBtn">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

     <div class="modal fade" id="salaryShowModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Salary Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>ID:</strong> <span id="salary-id"></span></p>
                    <p><strong>Employee:</strong> <span id="salary-employee_id"></span></p>
                    <p><strong>Salary: </strong>₹<span id="salary-salary"></span></p>
                    <p><strong>PF:</strong> <span id="salary-pf"></span>%</p>
                    <p><strong>Leave Deduction:</strong> <span id="salary-leave_deduction"></span></p>
                    <p><strong>Net Salary: </strong>₹<span id="salary-net_salary"></span></p>
                    <p><strong>Month:</strong> <span id="salary-month"></span></p>
                </div>
                <div class="modal-footer text-start">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive px-3 pb-3">
        <table class="table table-bordered table-hover align-middle" id="salaryTable">
            <thead class="table text-center">
                <tr class="text-center">
                    <th>#</th>
                    @if (Auth::user()->role === 'admin')
                        <th>Employee</th>
                    @endif
                    <th>Salary</th>
                    <th>PF</th>
                    <th>Leave Deduction</th>
                    <th>Net Salary</th>
                    <th>Month</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>

    @include('layout.script')

    <script>
        $(function() {
            let isAdmin = "{{ Auth::user()->role }}" === "admin";

            let columns = [{
                data: 'id',
                name: 'id'
            }, ];

            if (isAdmin) {
                columns.push({
                    data: 'employee.name',
                    name: 'employee.name'
                });
            }

            columns.push({
                data: 'salary',
                name: 'salary'
            }, {
                data: 'pf',
                name: 'pf'
            }, {
                data: 'leave_deduction',
                name: 'leave_deduction'
            }, {
                data: 'net_salary',
                name: 'net_salary'
            }, {
                data: 'month',
                name: 'month'
            },{
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
            
            );

            $(function() {

                let table = $('#salaryTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('salary.index') }}",
                    columns: columns
                });

                // For Create New Salary
                $('#createNewSalary').click(function() {
                    $('#salaryForm')[0].reset();
                    $('#salary_id').val('');
                    $('#modelHeading').text("Make Salary Payment");
                    $('#ajaxModal').modal('show');
                });

                // For Submit Salary Form
                $('#salaryForm').submit(function(e) {
                    e.preventDefault();

                    let id = $('#salary_id').val();
                    let url = id ?
                        "/salary/update/" + id :
                        "{{ route('salary.store') }}";

                    $.ajax({
                        url: url,
                        method: "POST",
                        data: $(this).serialize(),
                        success: function() {
                            $('#ajaxModal').modal('hide');
                            table.ajax.reload();
                            Swal.fire("Success", "Record Saved", "success");
                        }
                    });
                });

                // For Edit Salary
                $('body').on('click', '.edit-salary', function() {
                    let url = $(this).data('url');

                    $.get(url, function(data) {
                        $('#salary_id').val(data.id);
                        $('#employee_id').val(data.employee_id);
                        $('#salary').val(data.salary);
                        $('#pf').val(data.pf);
                        $('#leave_deduction').val(data.leave_deduction);
                        $('#net_salary').val(data.net_salary);
                        $('#month').val(data.month);
                        $('#modelHeading').text("Edit Salary");
                        $('#ajaxModal').modal('show');
                    });
                });

                $('body').on('click', '.show-salary', function() {
                var salaryURL = $(this).data('url');
                $.get(salaryURL, function(data) {
                    $('#salaryShowModal').modal('show');
                    $('#salary-id').text(data.id);
                    $('#salary-employee_id').text(data.employee_id);
                    $('#salary-salary').text(data.salary);
                    $('#salary-pf').text(data.pf);
                    $('#salary-leave_deduction').text(data.leave_deduction);
                    $('#salary-net_salary').text(data.net_salary);
                    $('#salary-month').text(data.month);
                });
            });

                // For Delete
                $('body').on('click', '.delete-salary', function() {
                    let url = $(this).data('url');

                    Swal.fire({
                        title: "Delete this record?",
                        showCancelButton: true,
                        icon: "warning",
                        confirmButtonText: "Yes, Delete",
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#6c757d",
                        cancelButtonText: "No, Cancel",
                        showLoaderOnConfirm: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: url,
                                method: "DELETE",
                                data: {
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function() {
                                    table.ajax.reload();
                                    Swal.fire("Deleted!", "", "success");
                                }
                            });
                        }
                    });
                });
            });

        });
    </script>

</body>

</html>