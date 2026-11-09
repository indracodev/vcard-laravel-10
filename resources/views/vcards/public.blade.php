<!DOCTYPE html>
<html lang="id">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>{{ strtoupper($vcard->full_name ?? 'INDRACO') }}</title>
   <link rel="shortcut icon" href="{{ asset('assets/img/icon-indraco.ico') }}" type="image/x-icon">
   <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-5.3.8-dist/css/bootstrap.min.css') }}">
   <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-icons-1.13.1/bootstrap-icons.min.css') }}">
   <link rel="stylesheet" href="{{ asset('assets/fonts/HelveticaNeue/HelveticaNeue.css') }}">
   <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
   <link rel="stylesheet" href="{{ asset('assets/css/vcard.css') }}">
   <script src="{{ asset('assets/vendor/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
   <script src="{{ asset('assets/js/themeSwitcher.js') }}"></script>
</head>

<body>
   <div class="wrapper bg-body shadow position-relative mx-auto">
      <header class="px-4 py-5 p-sm-5 vstack row-gap-5">
         <div class="position-absolute top-0 end-0 p-3">
            @include('layouts.partials.themeSwitcher')
         </div>
         <img src="{{ asset('assets/img/logo-indraco-dark.png') }}"
            data-img-theme-light="{{ asset('assets/img/logo-indraco.png') }}"
            data-img-theme-dark="{{ asset('assets/img/logo-indraco-invert.png') }}"
            alt="Logo INDRACO" loading="lazy" class="mx-auto" style="width: 45%; height: auto;">
         <div class="ratio ratio-1x1 rounded-circle overflow-hidden mx-auto bg-secondary" style="width:60%;">
            @if ($vcard->photo_url)
               <img src="{{ $vcard->photo_url }}" alt="Foto {{ $vcard->full_name }}"
                  loading="lazy" class="w-100 h-100 object-fit-cover">
            @else
               <img src="{{ asset('assets/img/no-image-invert.png') }}" alt="Avatar default {{ $vcard->full_name }}" loading="lazy"
                  class="w-50 h-50 top-50 start-50 translate-middle">
            @endif
         </div>
         <div class="text-center">
            <h1 class="fs-5 text-capitalize fw-bold">
               {{ $vcard->full_name ?? 'No Name' }}
            </h1>
            <p class="fs-small mb-0 text-capitalize">
               PT. Indraco Global Indonesia
               <br>
               {{ $vcard->title ?? '' }}
            </p>
         </div>
      </header>
      <main>
         <nav aria-label="Tautan cepat" class="navtop">
            <ul class="list-unstyled mb-0 hlist-divider d-grid" style="grid-template-columns: repeat(3, 1fr);">
               <li>
                  <a href="https://www.indracoglobal.com/download" target="_blank" rel="noopener noreferrer"
                     class="btn">
                     <i class="bi bi-book fs-5 d-grid lh-1" aria-hidden="true"></i>
                     BROCHURES
                  </a>
               </li>
               <li>
                  <a href="https://indracoglobal.com/" target="_blank" rel="noopener noreferrer" class="btn">
                     <i class="bi bi-globe fs-5 d-grid lh-1" aria-hidden="true"></i>
                     WEBSITE
                  </a>
               </li>
               <li>
                  <a href="{{ route('vcards.vcf', $vcard->link_name) }}" class="btn">
                     <i class="bi bi-person fs-5 d-grid lh-1" aria-hidden="true"></i>
                     SAVE CONTACT
                  </a>
               </li>
            </ul>
         </nav>

         <div class="p-4 py-5 p-sm-5">
            <nav aria-label="Informasi kontak">
               <ul class="list-unstyled m-0 d-flex flex-column row-gap-4 py-sm-4">
                  <li class="d-flex column-gap-3">
                     <i class="bi bi-phone fs-5 d-grid lh-1" aria-hidden="true"></i>
                     <div>
                        <a class="link-hover-underline" href="tel:{{ $vcard->mobile_phone }}">
                           {{ formatPhone($vcard->mobile_phone) }}
                        </a>
                        <div class="fs-small opacity-50">Mobile</div>
                     </div>
                  </li>
                  <li class="d-flex column-gap-3">
                     <i class="bi bi-telephone fs-5 d-grid lh-1" aria-hidden="true"></i>
                     <div>
                        <a class="link-hover-underline" href="tel:+62317668777">
                           +62 31 766 8777
                        </a>
                        <div class="fs-small opacity-50">Office</div>
                     </div>
                  </li>
                  <li class="d-flex column-gap-3">
                     <i class="bi bi-envelope fs-5 d-grid lh-1" aria-hidden="true"></i>
                     <div>
                        <a class="link-hover-underline" href="mailto:{{ $vcard->email }}">
                           {{ $vcard->email }}
                        </a>
                        <div class="fs-small opacity-50">Email</div>
                     </div>
                  </li>
                  <li class="d-flex column-gap-3">
                     <i class="bi bi-geo-alt fs-5 d-grid lh-1" aria-hidden="true"></i>
                     <div>
                        <a class="link-hover-underline" href="https://goo.gl/maps/UmQi3RbKgiBNkjCS6" target="_blank"
                           rel="noopener noreferrer">
                           Jl. Semeru No. 133-135 Bambe, <br>Kec. Driyorejo, Gresik 61177 <br>Jawa Timur - Indonesia
                        </a>
                        <div class="fs-small opacity-50">Show on map</div>
                     </div>
                  </li>
                  <li class="d-flex column-gap-3">
                     <i class="bi bi-globe fs-5 d-grid lh-1" aria-hidden="true"></i>
                     <div>
                        <a class="link-hover-underline" href="https://indracoglobal.com" target="_blank"
                           rel="noopener noreferrer">
                           www.indracoglobal.com
                        </a>
                        <div class="fs-small opacity-50">Website</div>
                     </div>
                  </li>
               </ul>
            </nav>
         </div>
      </main>
      <footer>
         <div class="p-4 py-5 d-flex flex-column gap-5 p-sm-5">
            <div>
               <img src="{{ asset('assets/img/logo-indraco.png') }}"
                  data-img-theme-light="{{ asset('assets/img/logo-indraco.png') }}"
                  data-img-theme-dark="{{ asset('assets/img/logo-indraco-invert.png') }}"
                  alt="Logo INDRACO" loading="lazy" class="w-100 h-auto" style="max-width: 248px">
               <p class="text-capitalize mb-0 fs-small">
                  <span>Roasting fine exquisite coffee since 1971.</span>
               </p>
            </div>
            <div>
               <h2 class="fw-bold fs-6 mb-3">Links</h2>
               <ul class="list-unstyled mb-0 d-grid gap-2 column-gap-3 fs-small"
                  style="grid-template-columns: auto auto;">
                  <li>
                     <a href="https://www.indracoglobal.com/products/rasa-sayang/" target="_blank"
                        rel="noopener noreferrer" class="text-reset text-decoration-none">
                        <i class="bi bi-chevron-right me-1" aria-hidden="true"></i>Rasa Sayang
                     </a>
                  </li>
                  <li>
                     <a href="https://www.indracoglobal.com/products/hao-cafe/" target="_blank"
                        rel="noopener noreferrer" class="text-reset text-decoration-none">
                        <i class="bi bi-chevron-right me-1" aria-hidden="true"></i>Hao Cafe
                     </a>
                  </li>
                  <li>
                     <a href="https://www.indracoglobal.com/products/tugu-buaya/" target="_blank"
                        rel="noopener noreferrer" class="text-reset text-decoration-none">
                        <i class="bi bi-chevron-right me-1" aria-hidden="true"></i>Tugu Buaya
                     </a>
                  </li>
                  <li>
                     <a href="https://www.indracoglobal.com/products/uang-emas/" target="_blank"
                        rel="noopener noreferrer" class="text-reset text-decoration-none">
                        <i class="bi bi-chevron-right me-1" aria-hidden="true"></i>Uang Emas
                     </a>
                  </li>
                  <li>
                     <a href="https://www.indracoglobal.com/products/jaheku/" target="_blank"
                        rel="noopener noreferrer" class="text-reset text-decoration-none">
                        <i class="bi bi-chevron-right me-1" aria-hidden="true"></i>Jaheku
                     </a>
                  </li>
                  <li>
                     <a href="https://www.indracoglobal.com/products/brochoco/" target="_blank"
                        rel="noopener noreferrer" class="text-reset text-decoration-none">
                        <i class="bi bi-chevron-right me-1" aria-hidden="true"></i>BROCHOCO
                     </a>
                  </li>
                  <li>
                     <a href="https://www.indracoglobal.com/products/ucafe/" target="_blank"
                        rel="noopener noreferrer" class="text-reset text-decoration-none">
                        <i class="bi bi-chevron-right me-1" aria-hidden="true"></i>UCAFÉ
                     </a>
                  </li>
                  <li>
                     <a href="https://www.indracoglobal.com/products/supresso/" target="_blank"
                        rel="noopener noreferrer" class="text-reset text-decoration-none">
                        <i class="bi bi-chevron-right me-1" aria-hidden="true"></i>Supresso
                     </a>
                  </li>
                  <li>
                     <a href="https://www.indracoglobal.com/products/balicafe/" target="_blank"
                        rel="noopener noreferrer" class="text-reset text-decoration-none">
                        <i class="bi bi-chevron-right me-1" aria-hidden="true"></i>Balicafé
                     </a>
                  </li>
               </ul>
            </div>
            <div>
               <h2 class="fw-bold fs-6 mb-3">Contact Us</h2>
               <address class="fs-small mb-0">
                  <strong>PT. Indraco Global Indonesia</strong>
                  <br>
                  Jl. Semeru No. 133-135 Bambe, Kec. Driyorejo,
                  <br>
                  Gresik 61177 Jawa Timur - Indonesia
                  <br><br>
                  T. +62 31 766 8777, 766 7388
                  <br>
                  E. info@indracoglobal.com
                  <br><br>
                  www.indracoglobal.com
               </address>
            </div>
         </div>
         <hr class="m-0 opacity-50">
         <div class="p-4 px-sm-5">
            <p class="text-center mb-0 fs-small text-muted">
               &copy; {{ date('Y') }} INDRACO. All rights reserved.
            </p>
         </div>
      </footer>
   </div>
</body>

</html>
