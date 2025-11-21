@include('layout.sidebar')
<!DOCTYPE html>
<html lang="en">

<head>
<title>Leave Management</title>
    @include('layout.css')
</head>

<body class="bg-light" style="margin-bottom: 22px; margin-left:250px; cursor: inherit;">

    <div class="container">
        <div class="card mt-5">
            <h3 class="card-header p-3 text-center fw-bold">
                <i class="bi bi-calendar2-check-fill"></i> Leave Management
            </h3>

            <a class="btn btn-success mt-3 ms-2 mb-2 fw-bold rounded-3" style="width:170px;" href="javascript:void(0)"
                id="createNewLeave">
                <i class="bi bi-plus-lg"></i> Apply Leave
            </a>

            <div class="modal fade" id="ajaxModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="modelHeading"></h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <form id="leaveForm" class="form-horizontal">
                                <input type="hidden" name="leave_id" id="leave_id">
                                @csrf

                        @if(Auth::user()->role === 'admin')
                                <div class="col-md-12">
                                    <label for="employee" class="form-label fw-semibold">Employee</label>
                                    <select name="employee" id="employee" class="form-select shadow-sm mb-3">
                                        <option value="" disabled {{ old('employee') ? '' : 'selected' }}>
                                            Select Employee
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
                            @else
                            <input type="hidden" name="employee" id="employee" value="{{ Auth::user()->name }}">
                            @endif


                                <label for="leave_date" class="form-label fw-semibold">Leave Date</label>
                                <input type="date" name="leave_date" class="form-control" id="leave_date"
                                    value="{{ old('leave_date') }}">
                                @error('leave_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                                <div class="col-12" style="width:460px">
                                    <label for="reason" class="form-label fw-semibold mt-2">Reason</label>
                                    <textarea class="form-control shadow-sm me-2" id="reason" name="reason" rows="3" placeholder="Write Reason..">{{ old('reason') }}</textarea>
                                    @error('reason')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-start mb-3 ms-2 mt-2 ms-1">
                                    <button type="submit" class="btn btn-success rounded-3" id="saveBtn"
                                        value="create">
                                        <i class="bi bi-person-plus-fill"></i> Apply
                                    </button>
                                    <button type="button" class="btn btn-secondary ms-2 rounded-3" style="width:100px;"
                                        data-bs-dismiss="modal">
                                        <i class="bi bi-x-lg"></i> Close
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="card-body">
                <table class="table table-bordered table-striped leaveTable" id="leaveTable">
                    <thead class="text-center">
                        <tr class="text-center">
                            @if (Auth::user()->role === 'admin')
                                <th class="text-center">Id</th>
                                <th class="text-center">Employee</th>
                            @endif
                            <th class="text-center">Leave Date</th>
                            <th class="text-center">Reason</th>
                            <th class="text-center">Status</th>
                            @if (Auth::user()->role === 'admin')
                                <th class="text-center" style="width: 30%">Actions</th>
                            @endif

                        </tr>
                    </thead>
                    <tbody class="text-center">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('layout.script')

    <script type="text/javascript" class="text-center">
        if ("{{ Auth::user()->role }}" == 'admin') {
            var table = $('#leaveTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('leave.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'employee',
                        name: 'employee'
                    },
                    {
                        data: 'leave_date',
                        name: 'leave_date'
                    },
                    {
                        data: 'reason',
                        name: 'reason'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        } else {
            var table = $('#leaveTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('leave.index') }}",
                columns: [{
                        data: 'leave_date',
                        name: 'leave_date'
                    },
                    {
                        data: 'reason',
                        name: 'reason'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },

                ]
            });
        }

        $(document).ready(function() {
            $('#createNewLeave').click(function() {
                $('#saveBtn').val("create-leave");
                $('#leave_id').val('');
                $('#leaveForm').trigger("reset");
                $('#modelHeading').html(
                    "<i class='bi bi-file-earmark-person-fill'></i> Make Leave");
                $('#ajaxModal').modal('show');
            });

            $('#leaveForm').submit(function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                $('#saveBtn').html('Creating...');

                $.ajax({
                    type: 'POST',
                    url: "{{ route('leave.store') }}",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#saveBtn').html('Submit');
                        $('#leaveForm').trigger("reset");
                        $('#ajaxModal').modal('hide');
                        table.ajax.reload();

                        Swal.fire({
                            toast: true,
                            position: 'top',
                            icon: 'success',
                            title: 'Leave Applied',
                            showConfirmButton: false,
                            timer: 1500,
                        });
                    },
                });
            });

            $(document).on('click', '#approve-leave', function() {
                let approveId = $(this).data('id');
                let approveUrl = "{{ route('leave.approve', ':id') }}";
                approveUrl = approveUrl.replace(':id', approveId);

                Swal.fire({
                    title: "Are you sure?",
                    text: "Are you sure you want to approve this leave?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "forestgreen",
                    confirmButtonText: "Yes, Approve!",
                    cancelButtonColor: "#df4e31",
                    cancelButtonText: "No, Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: approveUrl,
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                Swal.fire("Approved!",
                                    "Leave Approved Successfully!",
                                    "success");
                                table.ajax.reload();
                            },
                        });
                    }
                });
            });

            $(document).on('click', '#reject-leave', function() {
                let rejectId = $(this).data('id');
                let rejectUrl = "{{ route('leave.reject', ':id') }}";
                rejectUrl = rejectUrl.replace(':id', rejectId);

                Swal.fire({
                    title: "Are you sure?",
                    text: "Are you sure you want to reject this leave?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#df4e31",
                    confirmButtonText: "Yes, Reject",
                    cancelButtonColor: "dimgray",
                    cancelButtonText: "No, Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: rejectUrl,
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                Swal.fire("Rejected!", "Leave Rejected!",
                                    "success");
                                table.ajax.reload();
                            },
                        });
                    }
                });
            });

            $(document).on('click', '#delete-leave', function() {
                let leaveId = $(this).data('id');
                let deleteUrl = "{{ route('leave.destroy', ':id') }}";
                deleteUrl = deleteUrl.replace(':id', leaveId);

                swal({
                    title: "Are you sure?",
                    text: "Are you sure want to delete record?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#df4e31ff",
                    confirmButtonText: "Yes, Delete!",
                    cancelButtonText: "No, Cancel",
                    cancelButtonColor: "#696969",
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
                                swal("Deleted!",
                                    "Record Deleted Successfully!",
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

            flatpickr("#datepicker", {
                mode: "multipele",
                dateFormat: "Y-m-d"
            });
        });
    </script>
</body>

</html>
