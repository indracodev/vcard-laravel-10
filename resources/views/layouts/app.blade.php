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

            <a class="navbar-brand d-flex" href="{{ route('dashboard') }}"
               aria-label="Beranda Dashboard {{ config('app.name') }}">
               <img src="{{ asset('assets/img/logo-indraco.png') }}"
                  data-img-theme-light="{{ asset('assets/img/logo-indraco.png') }}"
                  data-img-theme-dark="{{ asset('assets/img/logo-indraco-invert.png') }}" alt="Logo INDRACO"
                  class="w-100 h-auto d-inline-block align-text-top" style="max-width: 120px">
            </a>

            <div class="d-flex align-items-center gap-2">
               @include('layouts.partials.themeSwitcher')
               @if ((auth()->user() && auth()->user()->isAdmin()) || session('role') === 'admin')
                  @if (request()->routeIs('users.*'))
                     <a href="{{ route('dashboard') }}"
                        class="btn btn-sm btn-primary d-flex align-items-center gap-2 bg-gradient">
                        <i class="bi bi-grid" aria-hidden="true"></i>
                        <span>DASHBOARD</span>
                     </a>
                  @else
                     <a href="{{ route('users.index') }}"
                        class="btn btn-sm btn-primary d-flex align-items-center gap-2 bg-gradient">
                        <i class="bi bi-people" aria-hidden="true"></i>
                        <span>MANAJEMEN AKUN</span>
                     </a>
                  @endif
               @endif
               <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="m-0">
                  @csrf
                  <input type="hidden" name="idle" id="logoutIdleInput" value="0">
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
            Dikembangkan dengan <span role="img" aria-label="cinta">❤️</span> oleh INDRACODEV. &copy;
            {{ date('Y') }} <strong>{{ config('app.name') }}</strong>. Seluruh hak
            dilindungi.
         </p>
      </div>
   </footer>

   @stack('modals')
   @stack('scripts')
   <script>
      document.addEventListener('DOMContentLoaded', function() {
         const logoutForm = document.getElementById('logoutForm');
         const logoutIdleInput = document.getElementById('logoutIdleInput');
         if (!logoutForm) return;

         const IDLE_TIMEOUT_MS = 5 * 60 * 1000;
         let idleTimer = null;

         const triggerIdleLogout = () => {
            if (logoutIdleInput) {
               logoutIdleInput.value = '1';
            }
            logoutForm.submit();
         };

         const resetIdleTimer = () => {
            if (idleTimer) clearTimeout(idleTimer);
            idleTimer = setTimeout(triggerIdleLogout, IDLE_TIMEOUT_MS);
         };

         ['mousemove', 'mousedown', 'keydown', 'scroll', 'touchstart', 'click'].forEach(eventName => {
            window.addEventListener(eventName, resetIdleTimer, {
               passive: true
            });
         });

         resetIdleTimer();
      });
   </script>
</body>

</html>
