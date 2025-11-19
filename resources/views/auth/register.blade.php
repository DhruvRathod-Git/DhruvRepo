<!DOCTYPE html>
<html lang="en">
<head>
    @include('layout.css')
</head>

<body class="bg-light d-flex justify-content-center align-items-center min-vh-100">

    <div class="container d-flex justify-content-center">
        <div class="card shadow-lg border-0 rounded-4 p-2" style="max-width: 420px; width: 100%;">
            <div class="card-body p-4">

                <div class="text-center mb-4">
                    <img src="/vnnovate.png" alt="Logo" width="70" class="mb-2">
                    <h3 class="fw-bold text-success">Create an Account</h3>
                </div>

                <form method="POST" action="{{ route('auth.store') }}">
                    @csrf
                    @method('POST')

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" id="name"
                                   class="form-control rounded-end"
                                   placeholder="Enter your name"
                                   value="{{ old('name') }}">
                        </div>
                        @error('name')
                        <small class="text-danger fw-semibold">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" id="email"
                                   class="form-control rounded-end"
                                   placeholder="abc@gmail.com"
                                   value="{{ old('email') }}">
                        </div>
                        @error('email')
                        <small class="text-danger fw-semibold">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" id="password"
                                   class="form-control rounded-end"
                                   placeholder="••••••••">
                        </div>
                        @error('password')
                        <small class="text-danger fw-semibold">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-success btn-lg rounded-3">
                            <i class="bi bi-person-check-fill me-1"></i> Register
                        </button>
                    </div>

                    <p class="text-center mb-0">
                        <small class="text-muted">Already have an account?</small>
                        <a href="{{ route('auth.login') }}" class="fw-semibold ms-1">Login</a>
                    </p>
                </form>

            </div>
        </div>
    </div>

    @include('layout.script')
</body>
</html>
