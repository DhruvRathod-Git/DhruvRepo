<!DOCTYPE html>
<html lang="en">

<head>
<title>Attendance Management</title>
    @include('layout.css')
</head>

<body class="bg-light" style="margin-bottom: 22px; margin-left:250px; cursor: inherit;">

    @include('layout.sidebar')

    <div class="container">
        <div class="card mt-5">
            <h3 class="card-header p-3 text-center fw-bold">
                <i class="bi bi-file-earmark-person-fill"></i> Attendance Management
            </h3>

            <div class="d-flex justify-content-start mt-4 mb-4 ms-2">
    @if(Auth::user()->role === 'employee')
                <a class="btn btn-primary fw-semibold rounded-3" href="javascript:void(0)" id="checkInBtn">
                    <i class="bi bi-box-arrow-in-right"></i> Check In
                </a>

                <a class="btn btn-danger fw-semibold rounded-3 ms-1 me-2" href="javascript:void(0)" id="checkOutBtn">
                    <i class="bi bi-box-arrow-in-left"></i> Check Out
                </a>
            @endif
            
                @if (Auth::user()->role === 'admin')
                    <a class="btn btn-success fw-bold rounded-3" href="javascript:void(0)" id="createNewAttendance">
                        <i class="bi bi-plus-lg"></i> Fill Attendance
                    </a>
                @endif
            </div>

            <div class="modal fade" id="ajaxModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="modelHeading"></h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="attendanceForm" name="attendanceForm" class="form-horizontal">
                                @csrf
                                <input type="hidden" name="attendance_id" id="attendance_id">

                                <div class="col-md-12">
                                    <label for="employee" class="form-label fw-semibold">Employee</label>
                                    <select name="employee" id="employee" class="form-select shadow-sm mb-3">
                                        <option value="" disabled {{ old('employee') ? '' : 'selected' }}>
                                            Select Employees
                                        </option>
                                        @foreach ($employee as $emp)
                                            <option value="{{ $emp->id }}"
                                                {{ old('employee') == $emp->name ? 'selected' : '' }}>
                                                {{ $emp->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('employee')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="check_in" class="form-label fw-semibold">Check-in</label>
                                        <input type="time" id="check_in" name="check_in" class="form-control"
                                            value="{{ old('check_in') }}">
                                        @error('check_in')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="check_out" class="form-label fw-semibold">Check-out</label>
                                        <input type="time" id="check_out" name="check_out" class="form-control"
                                            value="{{ old('check_out') }}">
                                        @error('check_out')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="date" class="form-label fw-semibold">Date</label>
                                        <input type="date" id="date" name="date" class="form-control"
                                            min="{{ date('Y-m-d') }}" value="{{ old('date') }}"
                                            {{ Auth::user()->role !== 'admin' ? 'readonly' : '' }}>
                                        @error('date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-between mt-3">
                                    <button type="submit" id="saveBtn" class="btn btn-success px-4 rounded-3">
                                        <i class="bi bi-plus-circle"></i> Add Attendance
                                    </button>
                                    <a href="{{ route('att.index') }}" class="btn btn-secondary px-4 rounded-3"
                                        data-bs-dismiss="modal">
                                        <i class="bi bi-x-lg"></i> Close
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped attendanceTable" id="attendanceTable">
                    <thead class="text-center">
                        <tr>
                            @if (Auth::user()->role === 'admin')
                                <th class="text-center">#</th>
                                <th class="text-center">Employee</th>
                            @endif
                            <th class="text-center">Check-In</th>
                            <th class="text-center">Check-Out</th>
                            <th class="text-center" style="width:10%">Date</th>
                            <th class="text-center" style="width: 30%">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="attendanceShowModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Show Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if(Auth::user()->role === 'admin')<p><strong>Employee:</strong> <span id="attendance-employee"></span></p>@endif
                    <p><strong>Check-In:</strong> <span id="attendance-check_in"></span></p>
                    <p><strong>Check-Out:</strong> <span id="attendance-check_out"></span></p>
                    <p><strong>Date:</strong> <span id="attendance-date"></span></p>
                </div>
                <div class="modal-footer text-start">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editAttendanceModal" tabindex="-1" aria-labelledby="editAttendanceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editAttendanceModalLabel">
                        <i class="bi bi-pencil-square"></i> Edit Employee's Details
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="updateAttendanceForm" method="POST">
                        @csrf
                        @method('POST')

                        <div class="col-md-12">
                            <label for="edit-employee" class="form-label fw-semibold">Employee</label>
                            <select id="edit-employee" name="employee" class="form-select mb-3">
                                @foreach ($employee as $emp)
                                    <option value="{{ $emp->id }}"
                                        {{ ($task->employee ?? old('employee')) == $emp->name ? 'selected' : '' }}>
                                        {{ $emp->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('employee')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="check_in" class="form-label fw-semibold">Check-in</label>
                                <input type="time" id="edit-check_in" name="check_in" class="form-control"
                                    {{ old('check_in') }}>
                                @error('check_in')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="check_out" class="form-label fw-semibold">Check-out</label>
                                <input type="time" id="edit-check_out" name="check_out" class="form-control"
                                    {{ old('check_out') }}>
                                @error('check_out')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="date" class="form-label fw-semibold">Date</label>
                                <input type="date" class="form-control shadow-sm" id="edit-date" name="date"
                                    min="{{ date('Y-m-d') }}" value="{{ old('date') }}">
                                @error('date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary saveEdit">
                                <i class="bi bi-floppy"></i> Save
                            </button>
                            <a href="{{ route('att.index') }}" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-lg"></i> Close
                            </a>
                        </div>

                        @include('layout.script')

                            <script type="text/javascript">
                            @if(Auth::user()->role === 'admin')
                                $(document).ready(function() {
                                    var table = $('#attendanceTable').DataTable({
                                        processing: true,
                                        serverSide: true,
                                        ajax: "{{ route('att.index') }}",
                                        columns: [{
                                                data: 'id',
                                                name: 'id'
                                            },
                                            {
                                                data: 'employee',
                                                name: 'employee'
                                            },
                                            {
                                                data: 'check_in',
                                                name: 'check_in'
                                            },
                                            {
                                                data: 'check_out',
                                                name: 'check_out'
                                            },
                                            {
                                                data: 'date',
                                                name: 'date'
                                            },
                                            {
                                                data: 'action',
                                                name: 'action',
                                                orderable: false,
                                                searchable: false
                                            },
                                            @else
                                             $(document).ready(function() {
                                    var table = $('#attendanceTable').DataTable({
                                        processing: true,
                                        serverSide: true,
                                        ajax: "{{ route('att.index') }}",
                                        columns: [{
                                                data: 'check_in',
                                                name: 'check_in'
                                            },
                                            {
                                                data: 'check_out',
                                                name: 'check_out'
                                            },
                                            {
                                                data: 'date',
                                                name: 'date'
                                            },
                                            {
                                                data: 'action',
                                                name: 'action',
                                                orderable: false,
                                                searchable: false
                                            },
                                            @endif
                                        ]
                                    });
                                });
-
                                    $('#createNewAttendance').click(function() {
                                        $('#saveBtn').val("create-attendance");
                                        $('#attendance_id').val('att.index');
                                        $('#attendanceForm').trigger("reset");
                                        $('#modelHeading').html("<i class='bi bi-file-earmark-person-fill'></i>Make Attendance");
                                        $('#ajaxModal').modal('show');
                                    });

                                    $('#attendanceForm').submit(function(e) {
                                        e.preventDefault();

                                        let formData = new FormData(this);
                                        $('#saveBtn').html('Creating...');

                                        $.ajax({
                                            type: 'POST',
                                            url: "{{ route('att.store') }}",
                                            data: formData,
                                            contentType: false,
                                            processData: false,
                                            success: function(response) {
                                                $('#saveBtn').html('Submit');
                                                $('#attendanceForm').trigger("reset");
                                                $('#ajaxModal').modal('hide');
                                                table.ajax.reload();

                                                Swal.fire({
                                                    toast: true,
                                                    position: 'top',
                                                    icon: 'success',
                                                    title: 'New Attendance Created',
                                                    showConfirmButton: false,
                                                    timer: 1500,
                                                });
                                            },
                                        });
                                    });

                                    $('body').on('click', '#edit-attendance', function() {
                                        var employeeURL = $(this).data('url');
                                        $.get(employeeURL, function(data) {
                                            $('#updateAttendanceForm').attr('action', '/att/update/' + data.id);
                                            $('#editAttendanceModal').modal('show');
                                            @if(Auth::user()->role === 'admin')
                                            $('#edit-employee').val(data.employee);
                                            @endif
                                            $('#edit-check_in').val(data.check_in);
                                            $('#edit-check_out').val(data.check_out);
                                            $('#edit-date').val(data.date);
                                        });
                                    });

                                    $('#updateAttendanceForm').submit(function(e) {
                                        e.preventDefault();
                                        $.ajax({
                                            url: $(this).attr('action'),
                                            method: 'POST',
                                            data: $(this).serialize(),
                                            success: function(response) {
                                                $('#updateAttendanceForm')[0].reset();
                                                $('#editAttendanceModal').modal('hide');
                                                table.ajax.reload();
                                                Swal.fire({
                                                    toast: true,
                                                    position: 'top',
                                                    icon: 'success',
                                                    title: 'Attendance Updated Successfully',
                                                    showConfirmButton: false,
                                                    timer: 1000,
                                                });
                                            }
                                        });
                                    });

                                    $('body').on('click', '#show-attendance', function() {
                                        var employeeURL = $(this).data('url');
                                        $.get(employeeURL, function(data) {
                                            $('#attendanceShowModal').modal('show');
                                            $('#attendance-employee').text(data.employee);
                                            $('#attendance-check_in').text(data.check_in);
                                            $('#attendance-check_out').text(data.check_out);
                                            $('#attendance-date').text(data.date);
                                        });
                                    });

                                    $(document).on('click', '#delete-attendance', function() {
                                        let attendanceId = $(this).data('id');
                                        let deleteUrl = "{{ route('att.destroy', ':id') }}";
                                        deleteUrl = deleteUrl.replace(':id', attendanceId);

                                        swal({
                                            title: "Are you sure?",
                                            text: "Are you sure want to delete Attendance?",
                                            type: "warning",
                                            showCancelButton: true,
                                            confirmButtonColor: "#df4e31ff",
                                            confirmButtonText: "Yes, Delete!",
                                            cancelButtonColor: "#0000ffb0",
                                            cancelButtonText: "No, Cancel",
                                            closeOnConfirm: false,
                                            closeOnCancel: true
                                        }, function(isConfirm) {
                                            if (isConfirm) {
                                                $.ajax({
                                                    url: deleteUrl,
                                                    type: 'DELETE',
                                                    data: {
                                                        _token: "{{ csrf_token() }}"
                                                    },
                                                    success: function(response) {
                                                        swal("Deleted!", "Attendance Deleted Successfully!",
                                                            "success");
                                                        table.ajax.reload();
                                                        setTimeout(function() {
                                                            swal.close();
                                                        }, 1000);
                                                    },
                                                });
                                            }
                                        });
                                    });

                                    $('#checkInBtn').on('click', function() {
                                        let url = "{{ route('att.checkIn') }}";
                                        Swal.fire({
                                            title: "Are you sure?",
                                            text: "Do you want to check in now?",
                                            icon: "question",
                                            showCancelButton: true,
                                            confirmButtonText: "Yes, CheckIn",
                                            confirmButtonColor: "#006400",
                                            cancelButtonColor: "#d33",
                                            cancelButtonText: "No, Cancel"
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                $.ajax({
                                                    url: url,
                                                    type: 'POST',
                                                    data: {
                                                        _token: "{{ csrf_token() }}"
                                                    },
                                                    success: function(response) {
                                                        Swal.fire('Checked In!', '', 'success');
                                                        table.ajax.reload();
                                                    }
                                                });
                                            }
                                        });
                                    });

                                    $('#checkOutBtn').on('click', function() {
                                        let url = "{{ route('att.checkOut') }}";
                                        Swal.fire({
                                            title: "Are you sure?",
                                            text: "Do you want to check out now?",
                                            icon: "question",
                                            showCancelButton: true,
                                            confirmButtonText: "Yes, CheckOut",
                                            confirmButtonColor: "#d33",
                                            cancelButtonColor: "#006400",
                                            cancelButtonText: "No, Cancel"
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                $.ajax({
                                                    url: url,
                                                    type: 'POST',
                                                    data: {
                                                        _token: "{{ csrf_token() }}"
                                                    },
                                                    success: function(response) {
                                                        Swal.fire('Checked Out!', '', 'success');
                                                        table.ajax.reload();
                                                    }
                                                });
                                            }
                                        });
                                    });
                            </script>

</body>

</html>