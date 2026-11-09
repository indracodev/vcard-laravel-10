<!DOCTYPE html>
<html lang="id">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Login &ndash; {{ config('app.name') }}</title>
   <link rel="shortcut icon" href="{{ asset('assets/img/icon-indraco.ico') }}" type="image/x-icon">
   <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-5.3.8-dist/css/bootstrap.min.css') }}">
   <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-icons-1.13.1/bootstrap-icons.min.css') }}">
   <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
   <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
   <script src="{{ asset('assets/vendor/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
   <script src="{{ asset('assets/js/themeSwitcher.js') }}"></script>
</head>

<body>
   <div class="fixed-top p-3 d-flex justify-content-end z-2">
      @include('layouts.partials.themeSwitcher')
   </div>

   <main class="w-100 z-1">
      <div class="card shadow vstack">
         <div class="card-header p-3 bg-primary-subtle bg-gradient">
            <div class="text-center mb-1">
               <img src="{{ asset('assets/img/logo-indraco.png') }}"
                  data-img-theme-light="{{ asset('assets/img/logo-indraco.png') }}"
                  data-img-theme-dark="{{ asset('assets/img/logo-indraco-invert.png') }}" alt="Logo INDRACO"
                  height="32">
            </div>
            <h1 class="fs-6 text-center mb-0">
               <small>INDRACO Vcard &ndash; Kartu Nama Digital</small>
            </h1>
         </div>
         <div class="card-body flex-grow-1 overflow-x-hidden overflow-y-auto p-4">
            @if (session('error'))
               <div class="alert alert-danger small" role="alert">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
               <div class="alert alert-danger small" role="alert">
                  <ul class="mb-0 ps-3">
                     @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                     @endforeach
                  </ul>
               </div>
            @endif
            <form action="{{ url('/login') }}" method="POST" class="d-flex flex-column row-gap-3">
               @csrf
               <div>
                  <label for="username" class="form-label fw-bold small">Akun Pengguna (Username)</label>
                  <div class="input-group">
                     <span class="input-group-text">
                        <i class="bi bi-person" aria-hidden="true"></i>
                     </span>
                     <input type="text" name="username" id="username" value="{{ old('username') }}"
                        class="form-control" placeholder="Username" autocomplete="username" required autofocus>
                  </div>
               </div>
               <div>
                  <label for="password" class="form-label fw-bold small">Kata Sandi (Password)</label>
                  <div class="input-group">
                     <span class="input-group-text">
                        <i class="bi bi-lock" aria-hidden="true"></i>
                     </span>
                     <input type="password" name="password" id="password" class="form-control" placeholder="Password"
                        autocomplete="current-password" required>
                     <button type="button" class="btn btn-outline-secondary" id="togglePassword"
                        aria-label="Tampilkan kata sandi" aria-pressed="false" aria-controls="password"
                        title="Tampilkan kata sandi">
                        <i class="bi bi-eye" id="togglePasswordIcon" aria-hidden="true"></i>
                     </button>
                  </div>
               </div>
               <button type="submit"
                  class="btn btn-lg btn-primary bg-gradient w-100 mt-3 d-flex justify-content-center align-items-center gap-2">
                  <i class="bi bi-box-arrow-in-right" aria-hidden="true"></i>
                  <span>LOGIN</span>
               </button>
            </form>
         </div>
         <div class="card-footer text-center small text-muted">
            Dikembangkan dengan <span role="img" aria-label="cinta">❤️</span> oleh INDRACODEV. &copy;
            {{ date('Y') }} {{ config('app.name') }}. Seluruh hak
            dilindungi.
         </div>
      </div>
   </main>

   <script>
      document.addEventListener('DOMContentLoaded', function() {
         const passwordInput = document.getElementById('password');
         const toggleBtn = document.getElementById('togglePassword');
         const toggleIcon = document.getElementById('togglePasswordIcon');

         if (passwordInput && toggleBtn && toggleIcon) {
            toggleBtn.addEventListener('click', function() {
               const isHidden = passwordInput.getAttribute('type') === 'password';
               passwordInput.setAttribute('type', isHidden ? 'text' : 'password');
               toggleBtn.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
               const label = isHidden ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi';
               toggleBtn.setAttribute('aria-label', label);
               toggleBtn.setAttribute('title', label);
               toggleIcon.classList.toggle('bi-eye', !isHidden);
               toggleIcon.classList.toggle('bi-eye-slash', isHidden);
            });
         }
      });
   </script>
</body>

</html>
