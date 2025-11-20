@include('layout.sidebar')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Employee Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    @include('layout.css')
    <link rel="icon" type="image/x-icon" href="vnnovate.png">
</head>

<body class="bg-light" style="margin-bottom: 22px; margin-left: 250px; cursor: inherit;">

    <div class="container mt-4">

        <div class="card shadow-sm">
            <h3 class="card-header text-center fw-bold p-3">
                <i class="bi bi-people-fill"></i> Employee List
            </h3>


            @if (Auth::user()->role === 'admin')
                <div class="p-3">
                    <a id="createNewEmp" href="javascript:void(0)" class="btn btn-success rounded-3 mb-3 fw-bold">
                        <i class="bi bi-plus-lg"></i> Add Employee
                    </a>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover text-center" id="empTable">
                    <thead class="table">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Image</th>
                            <th>Documents</th>
                            <th>Experiences</th>
                            <th style="width: 30%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="modal fade" id="ajaxModel" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form method="post" id="createEmpForm" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-body">
                        <input type="hidden" name="emp_id" id="emp_id">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" id="name" class="form-control">
                                @error('name')
                                    <small class="text-danger fw-semibold">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control">
                                @error('email')
                                    <small class="text-danger fw-semibold">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control">
                                @error('password')
                                    <small class="text-danger fw-semibold">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="tel" name="phone" id="phone" class="form-control" maxlength="10">
                                @error('phone')
                                    <small class="text-danger fw-semibold">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" id="address" class="form-control"></textarea>
                                @error('address')
                                    <small class="text-danger fw-semibold">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Image</label>
                                <input type="file" name="image" id="image"
                                    class="form-control img-thumbnail mb-2" width="120" accept="image/*">
                                @error('image')
                                    <small class="text-danger fw-semibold">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Documents</label>
                                <input type="file" name="documents[]" id="documents" class="form-control" multiple>
                                @error('documents')
                                    <small class="text-danger fw-semibold">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Experience</label>
                                <div id="exp-wrapper-create">
                                    <div class="experience-item border rounded p-2 mb-2">
                                        <input type="text" name="experience[0][company]" class="form-control mb-2"
                                            placeholder="Company Name">
                                        <input type="text" name="experience[0][role]" class="form-control mb-2"
                                            placeholder="Role">
                                        <input type="number" name="experience[0][years]" class="form-control mb-2"
                                            placeholder="Years Worked">
                                        @error('experience')
                                            <small class="text-danger fw-semibold">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <button type="button" id="addExpCreate" class="btn btn-primary btn-sm mt-2">+ Add
                                    Experience</button>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-success" type="submit">Create</button>
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="empShowModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Employee Details</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="row mb-2">
                        <div class="col-4 fw-bold">ID:</div>
                        <div class="col-8" id="emp-id"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 fw-bold">Name:</div>
                        <div class="col-8" id="emp-name"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 fw-bold">Email:</div>
                        <div class="col-8" id="emp-email"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 fw-bold">Phone:</div>
                        <div class="col-8" id="emp-phone"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 fw-bold">Address:</div>
                        <div class="col-8" id="emp-address"></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4 fw-bold">Image:</div>
                        <div class="col-8">
                            <img src="" id="emp-image">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 fw-bold">Documents:</div>
                        <div class="col-8" id="emp-documents"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 fw-bold">Experiences:</div>
                        <div class="col-8" id="emp-experiences"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>


    <div class="modal fade" id="editEmpModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Edit Employee</h4>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form id="updateEmpForm" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-body">

                        <input type="hidden" id="edit-id" name="id">

                        <div class="mb-3"><label class="form-label">Name</label><input id="edit-name"
                                name="name" class="form-control"></div>
                        <div class="mb-3"><label class="form-label">Email</label><input id="edit-email"
                                name="email" class="form-control"></div>
                        <div class="mb-3"><label class="form-label">Phone</label><input id="edit-phone"
                                name="phone" class="form-control"></div>
                        <div class="mb-3"><label class="form-label">Address</label><input id="edit-address"
                                name="address" class="form-control"></div>
                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input id="edit-image" name="image" type="file" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Documents</label>
                            <input id="edit-documents" name="documents[]" type="file" class="form-control"
                                multiple>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Experience</label>
                            <div id="exp-wrapper-edit"></div>
                            {{-- <button type="button" id="addExpEdit" class="btn btn-primary btn-sm mt-2">+ Add
                                Experience</button> --}}
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Save</button>
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('layout.script')
    <script>
        $(function() {

            let table = $('#empTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('emp.index') }}",
                columns: [{
                    data: 'id',
                    name: 'id'
                }, {
                    data: 'name',
                    name: 'name'
                }, {
                    data: 'email',
                    name: 'email'
                }, {
                    data: 'phone',
                    name: 'phone'
                }, {
                    data: 'address',
                    name: 'address'
                }, {
                    data: 'image',
                    name: 'image'
                }, {
                    data: 'documents',
                    name: 'documents'
                }, {
                    data: 'experiences',
                    name: 'experiences'
                }, {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }]
            });

            $('#createNewEmp').click(function() {
                $('#createEmpForm')[0].reset();
                $('#modelHeading').text("Create Employee");
                $('#ajaxModel').modal('show');
            });

            $('#createEmpForm').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('emp.store') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function() {
                        $('#ajaxModel').modal('hide');
                        table.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Created Successfully',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                });
            });

            $('body').on('click', '.show-emp', function() {
                $.get($(this).data('url'), function(data) {

                    $('#empShowModal').modal('show');
                    $('#emp-id').text(data.id);
                    $('#emp-name').text(data.name);
                    $('#emp-email').text(data.email);
                    $('#emp-phone').text(data.phone);
                    $('#emp-address').text(data.address);
                    $('#emp-image').attr("src", "/storage/" + data.image);
                    let docs = JSON.parse(data.documents || "[]");

                    // for documents display like link
                    $("#emp-documents").html(
                        docs.map(d =>
                            `<a href="/storage/${d}" class="d-block" target="_blank">${d}</a>`)
                        .join(""));
                    let exps = JSON.parse(data.experiences || "[]");
                    $("#emp-experiences").html(exps.map(e =>
                        `${e.company} - ${e.role} (year:${e.years})`
                    ).join("<br>"));
                });
            });

            $('body').on('click', '.edit-emp', function() {
                $.get($(this).data('url'), function(res) {

                    $('#editEmpModal').modal('show');
                    $('#edit-id').val(res.id);
                    $('#edit-name').val(res.name);
                    $('#edit-email').val(res.email);
                    $('#edit-phone').val(res.phone);
                    $('#edit-address').val(res.address);
                    $("#edit-preview").attr("src", "/storage/" + res.image);
                    let expWrap = $("#exp-wrapper-edit");
                    expWrap.html("");

                    let experiences = res.experiences || [];
                    experiences.forEach((exp, i) => {
                        expWrap.append(`
                        <div class='experience-item border rounded p-2 mb-2'>
                            <input type='text' name='experience[${i}][company]' class='form-control mb-2' value='${exp.company}'>
                            <input type='text' name='experience[${i}][role]' class='form-control mb-2' value='${exp.role}'>
                            <input type='number' name='experience[${i}][years]' class='form-control mb-2' value='${exp.years}'>
                            <button type='button' class='btn btn-danger btn-sm remove-experience'>Remove</button>
                        </div>
                    `);
                    });

                    $('#updateEmpForm').attr('action', '/emp/update/' + res.id);
                });
            });

            $(document).on("click", ".remove-experience", function() {
                $(this).closest(".experience-item").remove();
            });


            $('#updateEmpForm').submit(function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,

                    success: function() {
                        $('#editEmpModal').modal('hide');
                        table.ajax.reload();

                        Swal.fire({
                            icon: 'success',
                            title: 'Updated Successfully',
                            timer: 1200,
                            showConfirmButton: false,
                        });
                    }
                });
            });

            $(document).on('click', '.delete-emp', function() {

                let empId = $(this).data('id');

                Swal.fire({
                    title: "Are you sure?",
                    text: "This record will be deleted.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    confirmButtonText: "Yes, Delete",
                }).then((result) => {

                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/emp/" + empId,
                            type: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function() {

                                table.ajax.reload();

                                Swal.fire({
                                    icon: "success",
                                    title: "Deleted",
                                    timer: 1000,
                                    showConfirmButton: false
                                });

                            }
                        });
                    }
                });
            });

            let expCreate = 1;
            $("#addExpCreate").click(function() {
                $("#exp-wrapper-create").append(`
                <div class="experience-item border rounded p-2 mb-2">
                    <input type="text" name="experience[${expCreate}][company]" class="form-control mb-2" placeholder="Company Name">
                    <input type="text" name="experience[${expCreate}][role]" class="form-control mb-2" placeholder="Role">
                    <input type="number" name="experience[${expCreate}][years]" class="form-control mb-2" placeholder="Years Worked">
                    <button type="button" class="btn btn-danger btn-sm remove-experience">Remove</button>
                </div>
            `);
                expCreate++;
            });

            $(document).on("click", ".remove-experience", function() {
                $(this).closest(".experience-item").remove();
            });

        });
    </script>

</body>

</html>
