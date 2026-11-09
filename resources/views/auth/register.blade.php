<!DOCTYPE html>
<html lang="id">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Register &ndash; {{ config('app.name') }}</title>
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
               <small>Daftar Akun &ndash; INDRACO Vcard</small>
            </h1>
         </div>
         <div class="card-body flex-grow-1 overflow-x-hidden overflow-y-auto p-4">
            @if ($errors->any())
               <div class="alert alert-danger small mb-4" role="alert">
                  <ul class="mb-0 ps-3">
                     @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                     @endforeach
                  </ul>
               </div>
            @endif
            <form method="POST" action="{{ route('register') }}" class="d-flex flex-column row-gap-3">
               @csrf
               <div>
                  <label for="name" class="form-label fw-bold small">Akun Pengguna (Username)</label>
                  <div class="input-group">
                     <span class="input-group-text">
                        <i class="bi bi-person" aria-hidden="true"></i>
                     </span>
                     <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="form-control" placeholder="Username" autocomplete="username" required autofocus>
                  </div>
               </div>
               <div>
                  <label for="email" class="form-label fw-bold small">Alamat Email</label>
                  <div class="input-group">
                     <span class="input-group-text">
                        <i class="bi bi-envelope" aria-hidden="true"></i>
                     </span>
                     <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="form-control" placeholder="Email" autocomplete="email" required>
                  </div>
               </div>
               <div>
                  <label for="password" class="form-label fw-bold small">Kata Sandi (Password)</label>
                  <div class="input-group">
                     <span class="input-group-text">
                        <i class="bi bi-lock" aria-hidden="true"></i>
                     </span>
                     <input type="password" name="password" id="password" class="form-control" placeholder="Password"
                        autocomplete="new-password" required>
                  </div>
               </div>
               <div>
                  <label for="password_confirmation" class="form-label fw-bold small">Konfirmasi Kata Sandi</label>
                  <div class="input-group">
                     <span class="input-group-text">
                        <i class="bi bi-lock-fill" aria-hidden="true"></i>
                     </span>
                     <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                        placeholder="Konfirmasi Password" autocomplete="new-password" required>
                  </div>
               </div>

               <button type="submit"
                  class="btn btn-lg btn-primary bg-gradient w-100 mt-3 d-flex justify-content-center align-items-center gap-2">
                  <i class="bi bi-person-plus" aria-hidden="true"></i>
                  <span>REGISTER</span>
               </button>
            </form>
            <p class="small text-center mt-4 mb-0">
               @if (auth()->check() || session('login'))
                  <a href="{{ route('dashboard') }}" class="link-primary">Kembali ke Dashboard</a>
               @else
                  Sudah punya akun? <a href="{{ route('login') }}" class="link-primary">Login di sini</a>
               @endif
            </p>
         </div>
         <div class="card-footer text-center small text-muted">
            Dikembangkan dengan ❤️ oleh INDRACODEV. &copy; {{ date('Y') }} {{ config('app.name') }}. Seluruh hak
            dilindungi.
         </div>
      </div>
   </main>
</body>

</html>
