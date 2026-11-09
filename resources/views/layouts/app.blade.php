<!DOCTYPE html>
<html lang="id">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>@yield('titlePage') &ndash; {{ config('app.name') }}</title>
   <link rel="shortcut icon" href="{{ asset('assets/img/icon-indraco.ico') }}" type="image/x-icon">
   <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-5.3.8-dist/css/bootstrap.min.css') }}">
   <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-icons-1.13.1/bootstrap-icons.min.css') }}">
   <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
   <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
   @stack('head')
   <script src="{{ asset('assets/vendor/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
   <script src="{{ asset('assets/js/themeSwitcher.js') }}"></script>
</head>

<body class="p-0">
   <header>
      <nav class="navbar navbar-expand bg-primary-subtle bg-gradient border-bottom shadow" aria-label="Navigasi utama">
         <div class="container-fluid flex-wrap column-gap-2 text-nowrap">

            <a class="navbar-brand d-flex" href="{{ route('dashboard') }}">
               <img src="{{ asset('assets/img/logo-indraco.png') }}"
                  data-img-theme-light="{{ asset('assets/img/logo-indraco.png') }}"
                  data-img-theme-dark="{{ asset('assets/img/logo-indraco-invert.png') }}" alt="Logo INDRACO"
                  class="w-100 h-auto d-inline-block align-text-top" style="max-width: 120px">
            </a>

            <div class="d-flex align-items-center gap-2">
               @include('layouts.partials.themeSwitcher')
               <form action="{{ route('logout') }}" method="POST" class="m-0">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-danger d-flex align-items-center gap-2 bg-gradient">
                     <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
                     <span>LOGOUT</span>
                  </button>
               </form>
            </div>

         </div>
      </nav>
   </header>
   <main>
      @yield('content')
   </main>
   <footer class="py-3">
      <div class="container-fluid px-lg-4 px-xxl-5">
         <hr>
         <p class="small text-center text-muted mb-0">
            Dikembangkan dengan ❤️ oleh INDRACODEV. &copy; {{ date('Y') }} {{ config('app.name') }}. Seluruh hak
            dilindungi.
         </p>
      </div>
   </footer>

   @stack('modals')
   @stack('scripts')
</body>

</html>
