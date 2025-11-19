<!DOCTYPE html>
<html lang="en">

<head>
    @include('layout.css')
</head>

<body class="bg-light" style="margin-bottom:22px; margin-left:200px; cursor:inherit; overflow:hidden">
    @include('layout.sidebar')
    <div class="container-fluid">
        <div class="py-5" id="content">
            <div class="row justify-content-center mt-5">
                <div class="col-lg-8 col-md-10">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-body text-primary text-center py-3 rounded-4 bg-light">
                            <h2 class="mb-0 fw-bold">
                                <i class="bi bi-person-circle"></i> Profile Details
                            </h2>
                        </div>
                        <div class="card-body p-4 rounded-4">
                            <div class="row align-items-center">
                                <div class="col-md-4 text-center mb-3">
                                    <img src="{{ asset('storage/' . $Employees->image) }}" class="rounded-circle border shadow-lg" width="150" height="150" alt="">
                                </div>
                                <div class="col-md-8">
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <p class="mb-1"><strong>Name:</strong></p>
                                            <p>{{ $Employees->name }}</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="mb-1"><strong>Email:</strong></p>
                                            <p>{{ $Employees->email }}</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="mb-1"><strong>Phone:</strong></p>
                                            <p>{{ $Employees->phone }}</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="mb-1"><strong>Address:</strong></p>
                                            <p>{{ $Employees->address }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <button class="btn btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editModal">
                                            <i class="bi bi-pencil-fill"></i> Edit
                                        </button>
                                        <a href="javascript:history.back()" class="btn btn-secondary">
                                            <i class="bi bi-arrow-left"></i> Back
                                        </a>

                                        <div class="modal fade" id="editModal" data-bs-backdrop="false" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="editModalLabel">Edit Profile</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('profile.update', $Employees->id) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('POST')
                                                            <div class="mb-3 text-center">
                                                                <img src="{{ asset('storage/' . $Employees->image) }}" alt="" width="100" height="100" id="image-preview" class="rounded-circle border shadow-lg mb-3">
                                                                <input type="file" name="image" id="file-upload" class="form-control @error('image') is-invalid @enderror">
                                                                @error('image')
                                                                <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="name" class="form-label fw-semibold">Name</label>
                                                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $Employees->name) }}">
                                                                @error('name')
                                                                <small class="text-danger">{{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="email" class="form-label fw-semibold">Email</label>
                                                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $Employees->email) }}" disabled>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="phone" class="form-label fw-semibold">Phone</label>
                                                                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $Employees->phone) }}">
                                                                @error('phone')
                                                                <small class="text-danger">{{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="address" class="form-label fw-semibold">Address</label>
                                                                <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $Employees->address) }}">
                                                                @error('address')
                                                                <small class="text-danger">{{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                            <div class="d-flex justify-content-end mt-4">
                                                                <button type="submit" class="btn btn-primary me-1">
                                                                    <i class="bi bi-floppy"></i> Save
                                                                </button>
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                    <i class="bi bi-x-lg"></i> Close
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layout.script')
</body>

</html>
