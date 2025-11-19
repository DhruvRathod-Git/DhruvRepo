@include('layout.sidebar')
<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    @include('layout.css')
</head>

<body class="bg-light" style="margin-bottom: 22px; margin-left:200px; cursor: inherit;">

    <div class="container">
        <div class="card mt-5">
            <h3 class="card-header p-3 text-center fw-bold">
                <i class="bi bi-person-circle"></i> User's List
            </h3>

            @if (Auth::check() && Auth::user()->role === 'admin')
                <a class="btn btn-success mt-2 ms-2 mb-2 rounded-3" style="width:170px;" href="javascript:void(0)"
                    id="createNewUser">
                    <i class="bi bi-person-plus-fill"></i> Create New Employee
                </a>

                <div class="modal fade" id="ajaxModel" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="modelHeading"></h4>
                                <button type="button" class="btn-close" data-bs -dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <form id="userForm" name="userForm" class="form-horizontal">
                                    <input type="hidden" name="user_id" id="user_id">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="name" class="form-label fw-semibold">Name</label>
                                        <input type="text" name="name" class="form-control" id="name"
                                            placeholder="Enter Name" value="{{ old('name') }}">
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-semibold">Email</label>
                                        <input type="email" name="email" class="form-control" id="email"
                                            placeholder="Enter Email" value="{{ old('email') }}">
                                        @error('email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label fw-semibold">Password</label>
                                        <input type="password" name="password" class="form-control" id="password"
                                            placeholder="Enter Password">
                                        @error('password')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="number" class="form-label fw-semibold">Number</label>
                                        <input type="number" name="number" class="form-control" id="number"
                                            placeholder="Enter Number" value="{{ old('number') }}">
                                        @error('number')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="d-flex justify-content-start mb-2 ms-2">
                            <button type="submit" class="btn btn-success rounded-3 mb-2" id="saveBtn" value="create">
                                <i class="bi bi-person-plus-fill"></i> Create User
                            </button>
                            <button type="button" class="btn btn-secondary ms-2 rounded-3" style="width:110px;"
                                data-bs-dismiss="modal">
                                <i class="bi bi-x-lg"></i> Close
                            </button>
                        </div>
                        </form>
                    </div>
                </div>
        </div>
    </div>
    @endif

    <div class="card-body">
        <table class="table table-bordered table-striped userTable" id="userTable">
            <thead class="text-center">
                <tr>
                    <th class="text-center">Id</th>
                    <th class="text-center">Name</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Number</th>
                    <th class="text-center" style="width: 30%">Actions</th>
                </tr>
            </thead>
            <tbody class="text-center">
            </tbody>
        </table>
    </div>
    </div>
    </div>

    <div class="modal fade" id="userShowModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">User's Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>ID:</strong> <span id="user-id"></span></p>
                    <p><strong>Name:</strong> <span id="user-name"></span></p>
                    <p><strong>Email:</strong> <span id="user-email"></span></p>
                    <p><strong>Number:</strong> <span id="user-number"></span></p>
                </div>
                <div class="modal-footer text-start">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editUserModalLabel">
                        <i class="bi bi-pencil-square"></i> Edit User
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="updateUserForm" method="POST">
                        @csrf
                        @method('POST') 

                        <div class="mb-3">
                            <label for="edit-name" class="form-label fw-semibold">Name</label>
                            <input type="text" name="name" class="form-control" id="edit-name"
                                value="{{ old('name') }}">
                        </div>

                        <div class="mb-3">
                            <label for="edit-email" class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" id="edit-email"
                                value="{{ old('email') }}">
                        </div>

                        <div class="mb-3">
                            <label for="edit-number" class="form-label fw-semibold">Number</label>
                            <input type="number" name="number" class="form-control" id="edit-number"
                                value="{{ old('number') }}">
                        </div>


                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-floppy"></i> Save
                            </button>
                            <a href="{{ route('emp.index') }}" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-lg"></i> Close
                            </a>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    @include('layout.script')

    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('emp.index') }}",

                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'number',
                        name: 'number'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $('#createNewUser').on('submit', function() {
                event.preventDefault();
                $.ajax({
                    url: "{{ route('emp.store') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#createNewUser')[0].reset();
                        $('#createNewUser').modal('hide');
                        table.ajax.reload();

                        Swal.fire({
                            toast: true,
                            position: 'top',
                            icon: 'success',
                            title: 'New Record Created Successfully!',
                            showConfirmButton: false,
                            timer: 1000,
                        });
                    },
                });
            });

            $('body').on('click', '#edit-user', function() {
                var userURL = $(this).data('url');

                $.get(userURL, function(data) {
                    $('#updateUserForm').attr('action', '/user/update/' + data.id);
                    $('#editUserModal').modal('show');
                    $('#edit-name').val(data.name);
                    $('#edit-email').val(data.email);
                    $('#edit-number').val(data.number);
                    $('#updateUserForm').submit(function(event) {
                        event.preventDefault();

                        $.ajax({
                            url: $(this).attr('action'),
                            method: 'POST',
                            data: $(this).serialize(),
                            success: function(response) {
                                $('#updateUserForm')[0].reset();
                                $('#editUserModal').modal('hide');
                                table.ajax.reload();
                                Swal.fire({
                                    toast: true,
                                    position: 'top',
                                    icon: 'success',
                                    title: 'Record Updated Successfully',
                                    showConfirmButton: false,
                                    timer: 1000,
                                });
                            }
                        });
                    });
                });
            });

            $('body').on('click', '#show-user', function() {
                var userURL = $(this).data('url');
                $.get(userURL, function(data) {
                    $('#userShowModal').modal('show');
                    $('#user-id').text(data.id);
                    $('#user-name').text(data.name);
                    $('#user-email').text(data.email);
                    $('#user-number').text(data.number);
                });
            });

            $('#createNewUser').click(function() {
                $('#saveBtn').val("create-user");
                $('#user_id').val('');
                $('#userForm').trigger("reset");
                $('#modelHeading').html("<i class='bi bi-person-plus-fill'></i> Create New User");
                $('#ajaxModel').modal('show');
            });

            $('#userForm').submit(function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                $('#saveBtn').html('Creating...');

                $.ajax({
                    type: 'POST',
                    url: "{{ route('user.store') }}",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: (response) => {
                        $('#saveBtn').html('Submit');
                        $('#userForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        table.ajax.reload();

                        Swal.fire({
                            toast: true,
                            position: 'top',
                            icon: 'success',
                            title: 'New Record Created Successfully!',
                            showConfirmButton: false,
                            timer: 1500,
                        });
                    },
                });
            });

            $(document).on('click', '#delete-user', function() {
                let userId = $(this).data('id');
                let deleteUrl = "{{ route('user.destroy', ':id') }}";
                deleteUrl = deleteUrl.replace(':id', userId);

                swal({
                    title: "Are you sure?",
                    text: "Are you sure want to delete record?",
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
                                swal("Deleted!", "Record Deleted Successfully!",
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
        });
    </script>

</body>

</html>
