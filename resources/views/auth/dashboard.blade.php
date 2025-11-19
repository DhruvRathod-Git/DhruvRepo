<!doctype html>
<html lang="en">

<head>
    @include('layout.css')
</head>

<body class="bg-light d-flex flex-column min-vh-100" style="margin-left:0px; cursor: inherit; overflow: hidden">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center ms-0">
        <img src="/vnnovate.png" height="40" width="40" class="me-2 rounded" alt="Vnnovate Logo" />
        <span class="fw-bold">Vnnovate</span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle fw-semibold" id="adminDropdown" role="button" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle me-1"></i> Add Credentials
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="adminDropdown">
              <li><a class="dropdown-item fw-semibold" href="{{ route('auth.login') }}"><i class="bi bi-shield-lock"></i> Login</a></li>
              <li><a class="dropdown-item fw-semibold" href="{{ route('auth.register') }}"><i class="bi bi-shield-check"></i> Register</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
</nav>
@include('layout.script')
</body>
</html>