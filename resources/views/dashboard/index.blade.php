@extends('layouts.app')
@section('titlePage')
   Dashboard
@endsection
@section('content')
   <section aria-label="hero banner" style="padding: 125px 0;">
      <div class="container-fluid px-lg-4 px-xxl-5">
         <div class="page-title text-center mx-auto" style="max-width: 640px;">
            <h1 class="display-5 fw-bold mb-0">
               Masa Depan Jejaring Bisnis Ada di Genggaman Anda.
            </h1>
            <p class="lead">
               Tinggalkan kartu nama kertas. Bagikan profil profesional dan kontak bisnis Anda dalam satu ketukan dengan
               {{ config('app.name') }}.
            </p>
            <a href="#vcard-list" class="btn btn-primary bg-gradient">
               Kelola Sekarang <i class="bi bi-chevron-right"></i>
            </a>
         </div>
      </div>
   </section>

   <section aria-label="Daftar vCard">
      <div class="container-fluid px-lg-4 px-xxl-5">
         @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
               {{ session('success') }}
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
         @endif
         <div class="row row-gap-4">
            <div class="col col-12 position-sticky top-0 z-3">
               <div class="card bg-body-tertiary shadow">
                  <div class="card-body">
                     <div class="row row-gap-3 justify-content-between">
                        <div class="col col-12 col-md">
                           <form id="searchForm" class="input-group" method="GET" action="{{ route('dashboard') }}"
                              role="search">
                              <span class="input-group-text">
                                 <i class="bi bi-search" aria-hidden="true"></i>
                              </span>
                              <input type="search" id="searchInput" name="search" value="{{ request('search') }}"
                                 class="form-control" placeholder="Cari nama, jabatan, email, telp" aria-label="Cari vCard"
                                 autocomplete="off">
                           </form>
                        </div>
                        <div class="col col-12 col-md-auto">
                           <button type="button" id="btnAdd"
                              class="btn btn-primary bg-gradient w-100 d-flex align-items-center justify-content-center gap-2"
                              data-bs-toggle="modal" data-bs-target="#vcardModal">
                              <i class="bi bi-plus-lg" aria-hidden="true"></i>
                              <span>Tambah {{ config('app.name') }}</span>
                           </button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>

            <div class="col col-12">
               @if ($vcards->isEmpty())
                  <div class="alert alert-danger p-5 fs-5 text-center" role="alert">
                     <i class="bi bi-exclamation-triangle-fill me-2" aria-hidden="true"></i>
                     <small>Belum ada data vCard yang ditemukan.</small>
                  </div>
               @else
                  <div id="searchEmptyState" class="alert alert-danger p-5 fs-5 text-center d-none" role="alert">
                     <i class="bi bi-exclamation-triangle-fill me-2" aria-hidden="true"></i>
                     <span>Tidak ada data vCard yang cocok dengan pencarian Anda.</span>
                  </div>
                  <ul id="vcard-list" class="list-unstyled">
                     @foreach ($vcards as $v)
                        @php
                           $photoUrl = $v->photo_url;
                           $formattedPhone = formatPhone($v->mobile_phone);
                           $searchableText = mb_strtolower(
                               implode(' ', [
                                   $v->name,
                                   $v->full_name,
                                   $v->title,
                                   $v->email,
                                   $v->mobile_phone,
                                   $formattedPhone,
                                   $v->link_name,
                               ]),
                           );
                        @endphp
                        <li class="vcard-item" data-search="{{ $searchableText }}">
                           <article class="card shadow-sm h-100 position-relative shadow">
                              <div class="card-body p-4 text-center">

                                 <div
                                    class="ratio ratio-1x1 overflow-hidden rounded-circle w-50 mx-auto my-4 bg-secondary">
                                    @if ($photoUrl)
                                       <img src="{{ $photoUrl }}" width="100" height="100"
                                          alt="Foto {{ $v->full_name }}" class="object-fit-cover" loading="lazy">
                                    @else
                                       <img src="{{ asset('assets/img/no-image-invert.png') }}"
                                          alt="Avatar default {{ $v->full_name }}"
                                          class="w-50 h-50 top-50 start-50 translate-middle" loading="lazy">
                                    @endif
                                    <a href="{{ url($v->link_name) }}" target="_blank" rel="noopener noreferrer"
                                       class="stretched-link">
                                       <span class="visually-hidden">{{ $v->link_name }}</span>
                                    </a>
                                 </div>

                                 <h2 class="fs-5 text-capitalize fw-medium mb-1">{{ $v->full_name }}</h2>
                                 <p class="text-capitalize small text-muted mb-2">{{ $v->title }}</p>
                                 <p class="mb-1 small">{{ $v->email }}</p>
                                 <p class="mb-2 small">{{ $formattedPhone }}</p>
                                 <div>
                                    <span class="badge rounded-pill {{ $v->status->badgeClass() }}">
                                       {{ $v->status->label() }}
                                    </span>
                                 </div>

                                 <button type="button"
                                    class="btn btn-lg rounded-0 border-0 shadow-none position-absolute top-0 end-0 btnEdit"
                                    data-id="{{ $v->id }}" data-update-url="{{ route('vcards.update', $v->id) }}"
                                    data-name="{{ $v->name }}" data-full_name="{{ $v->full_name }}"
                                    data-title="{{ $v->title }}" data-email="{{ $v->email }}"
                                    data-mobile_phone="{{ $v->mobile_phone }}" data-link_name="{{ $v->link_name }}"
                                    data-photo="{{ $v->photo }}" data-photo-url="{{ $photoUrl ?? '' }}"
                                    data-status="{{ $v->status->value }}" data-bs-toggle="modal"
                                    data-bs-target="#vcardModal" title="Edit Vcard">
                                    <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                    <span class="visually-hidden">Edit</span>
                                 </button>

                                 <span
                                    class="py-1 px-2 position-absolute top-0 start-0 m-2 d-inline-flex align-items-center gap-1"
                                    title="Jumlah dilihat">
                                    <i class="bi bi-eye" aria-hidden="true"></i>
                                    <span>{{ $v->views }}</span>
                                 </span>

                              </div>
                           </article>
                        </li>
                     @endforeach
                  </ul>
                  <nav id="paginationWrapper" aria-label="Navigasi halaman vCard" class="mt-4 d-none">
                     <ul id="vcardPagination" class="pagination justify-content-center flex-wrap mb-0"></ul>
                  </nav>
               @endif
            </div>
         </div>
      </div>
   </section>
@endsection
@push('modals')
   {{-- Modal Form --}}
   <div id="vcardModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
      aria-labelledby="vcardModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-fullscreen-md-down modal-dialog-centered modal-dialog-scrollable">
         <form id="vcardForm" class="modal-content" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div class="modal-header bg-body-tertiary">
               <h2 class="modal-title fs-5 text-muted" id="vcardModalLabel"></h2>
               <button type="button" class="btn-close rounded-0 border-0 shadow-none" data-bs-dismiss="modal"
                  aria-label="Tutup"></button>
            </div>

            <div class="modal-body d-flex flex-column row-gap-3">
               <div id="formGeneralError" class="alert alert-danger small d-none mb-0" role="alert"></div>

               <div class="form-group">
                  <div id="editPhoto"
                     class="ratio ratio-1x1 bg-secondary w-50 rounded-circle overflow-hidden mx-auto my-3">
                     <img id="photoPreview" src="{{ asset('assets/img/no-image-invert.png') }}" width="100"
                        height="100" alt="Pratinjau foto" class="w-50 h-50 top-50 start-50 translate-middle">
                  </div>
                  <label for="photoInput" class="form-label small">Foto</label>
                  <input type="file" name="photo" class="form-control" id="photoInput"
                     accept=".webp,.jpg,.jpeg,.png">
               </div>
               <div class="form-group">
                  <label for="name" class="form-label small">Nama Panggilan / Singkat</label>
                  <input type="text" name="name" id="name" class="form-control" required>
               </div>
               <div class="form-group">
                  <label for="full_name" class="form-label small">Nama Lengkap</label>
                  <input type="text" name="full_name" id="full_name" class="form-control" required>
               </div>
               <div class="form-group">
                  <label for="status" class="form-label small">Status</label>
                  <select name="status" id="status" class="form-select">
                     @foreach (\App\Enums\VcardStatus::cases() as $statusCase)
                        <option value="{{ $statusCase->value }}">{{ $statusCase->label() }}</option>
                     @endforeach
                  </select>
               </div>
               <div class="form-group">
                  <label for="title" class="form-label small">Jabatan</label>
                  <input type="text" name="title" id="title" class="form-control" required>
               </div>
               <div class="form-group">
                  <label for="email" class="form-label small">Email</label>
                  <input type="email" name="email" id="email" class="form-control" required>
               </div>
               <div class="form-group">
                  <label for="mobile_phone" class="form-label small">Nomor HP</label>
                  <input type="text" name="mobile_phone" id="mobile_phone" class="form-control"
                     placeholder="+62 812 3456 7890" required>
               </div>
               <div class="form-group">
                  <label for="link_name" class="form-label small">Link Name</label>
                  <input type="text" name="link_name" id="link_name" class="form-control" required>
               </div>
            </div>

            <div class="modal-footer flex-nowrap bg-body-tertiary">
               <button type="button" class="btn btn-danger w-100" data-bs-dismiss="modal">Batal</button>
               <button type="submit" class="btn btn-success w-100" id="btnSubmit">Simpan</button>
            </div>
         </form>
      </div>
   </div>
@endpush
@push('scripts')
   <script>
      document.addEventListener('DOMContentLoaded', function() {
         const searchForm = document.getElementById('searchForm');
         const searchInput = document.getElementById('searchInput');
         const vcardList = document.getElementById('vcard-list');
         const vcardItems = Array.from(document.querySelectorAll('.vcard-item'));
         const searchEmptyState = document.getElementById('searchEmptyState');
         const paginationWrapper = document.getElementById('paginationWrapper');
         const vcardPagination = document.getElementById('vcardPagination');
         const ITEMS_PER_PAGE = 20;
         let currentPage = Math.max(1, parseInt(new URL(window.location.href).searchParams.get('page'), 10) || 1);

         const renderPagination = (totalPages) => {
            if (!paginationWrapper || !vcardPagination) return;

            if (totalPages <= 1) {
               paginationWrapper.classList.add('d-none');
               vcardPagination.innerHTML = '';
               return;
            }

            paginationWrapper.classList.remove('d-none');
            vcardPagination.innerHTML = '';

            const createPageItem = (label, targetPage, { disabled = false, active = false, ariaLabel = '' } = {}) => {
               const li = document.createElement('li');
               li.className = `page-item${disabled ? ' disabled' : ''}${active ? ' active' : ''}`;

               const btn = document.createElement('button');
               btn.type = 'button';
               btn.className = 'page-link';
               btn.innerHTML = label;
               if (ariaLabel) btn.setAttribute('aria-label', ariaLabel);
               if (active) btn.setAttribute('aria-current', 'page');

               if (!disabled && !active) {
                  btn.addEventListener('click', () => {
                     currentPage = targetPage;
                     filterVcards(false);
                     vcardList.scrollIntoView({ behavior: 'smooth', block: 'start' });
                  });
               }

               li.appendChild(btn);
               return li;
            };

            vcardPagination.appendChild(
               createPageItem('&laquo; Sebelumnya', currentPage - 1, {
                  disabled: currentPage === 1,
                  ariaLabel: 'Halaman sebelumnya'
               })
            );

            for (let page = 1; page <= totalPages; page++) {
               vcardPagination.appendChild(
                  createPageItem(String(page), page, {
                     active: page === currentPage,
                     ariaLabel: `Halaman ${page}`
                  })
               );
            }

            vcardPagination.appendChild(
               createPageItem('Selanjutnya &raquo;', currentPage + 1, {
                  disabled: currentPage === totalPages,
                  ariaLabel: 'Halaman selanjutnya'
               })
            );
         };

         const filterVcards = (resetPage = true) => {
            if (!vcardList) return;

            const query = searchInput ? searchInput.value.trim().toLowerCase() : '';
            if (resetPage) {
               currentPage = 1;
            }

            const matchedItems = vcardItems.filter(item => {
               const haystack = item.dataset.search || '';
               return query === '' || haystack.includes(query);
            });

            const totalMatches = matchedItems.length;
            const totalPages = Math.max(1, Math.ceil(totalMatches / ITEMS_PER_PAGE));
            if (currentPage > totalPages) {
               currentPage = totalPages;
            }

            const startIndex = (currentPage - 1) * ITEMS_PER_PAGE;
            const visibleSet = new Set(matchedItems.slice(startIndex, startIndex + ITEMS_PER_PAGE));

            vcardItems.forEach(item => {
               item.classList.toggle('d-none', !visibleSet.has(item));
            });

            if (searchEmptyState) {
               searchEmptyState.classList.toggle('d-none', totalMatches > 0);
            }
            vcardList.classList.toggle('d-none', totalMatches === 0);

            renderPagination(totalPages);

            const url = new URL(window.location.href);
            if (query !== '' && searchInput) {
               url.searchParams.set('search', searchInput.value.trim());
            } else {
               url.searchParams.delete('search');
            }

            if (currentPage > 1 && totalPages > 1) {
               url.searchParams.set('page', String(currentPage));
            } else {
               url.searchParams.delete('page');
            }
            window.history.replaceState({}, '', url);
         };

         if (searchForm && searchInput) {
            searchForm.addEventListener('submit', function(e) {
               e.preventDefault();
               filterVcards(true);
            });
            searchInput.addEventListener('input', () => filterVcards(true));
            searchInput.addEventListener('search', () => filterVcards(true));
         }

         filterVcards(false);

         const form = document.getElementById('vcardForm');
         const modalTitle = document.getElementById('vcardModalLabel');
         const methodInput = document.getElementById('formMethod');
         const photoPreview = document.getElementById('photoPreview');
         const photoInput = document.getElementById('photoInput');
         const statusSelect = document.getElementById('status');
         const btnSubmit = document.getElementById('btnSubmit');
         const generalError = document.getElementById('formGeneralError');
         const defaultPhotoUrl = '{{ asset('assets/img/no-image-invert.png') }}';
         let currentObjectUrl = null;
         let initialPhotoUrl = '';

         const setPhotoPreview = (src, isCustomPhoto) => {
            photoPreview.src = src || defaultPhotoUrl;
            if (isCustomPhoto) {
               photoPreview.className = 'object-fit-cover';
            } else {
               photoPreview.className = 'w-50 h-50 top-50 start-50 translate-middle';
            }
         };

         photoPreview.addEventListener('error', () => {
            if (photoPreview.src !== defaultPhotoUrl) {
               setPhotoPreview(defaultPhotoUrl, false);
            }
         });

         photoInput.addEventListener('change', function() {
            if (currentObjectUrl) {
               URL.revokeObjectURL(currentObjectUrl);
               currentObjectUrl = null;
            }

            const file = this.files && this.files[0];
            if (file) {
               currentObjectUrl = URL.createObjectURL(file);
               setPhotoPreview(currentObjectUrl, true);
            } else if (initialPhotoUrl) {
               setPhotoPreview(initialPhotoUrl, true);
            } else {
               setPhotoPreview(defaultPhotoUrl, false);
            }
         });

         const clearErrors = () => {
            document.querySelectorAll('.field-error').forEach(el => el.remove());
            generalError.classList.add('d-none');
            generalError.innerText = '';
         };

         document.getElementById('btnAdd').addEventListener('click', () => {
            form.action = '{{ route('vcards.store') }}';
            methodInput.value = 'POST';
            modalTitle.innerText = 'Tambah {{ config('app.name') }}';
            form.reset();
            photoInput.required = true;
            initialPhotoUrl = '';
            setPhotoPreview(defaultPhotoUrl, false);
            statusSelect.value = '1';
            clearErrors();
         });

         document.querySelectorAll('.btnEdit').forEach(btn => {
            btn.addEventListener('click', () => {
               const id = btn.dataset.id;
               form.action = btn.dataset.updateUrl || `{{ url('/vcards') }}/${id}`;
               methodInput.value = 'PUT';
               modalTitle.innerText = 'Edit {{ config('app.name') }}';

               form.reset();
               photoInput.required = false;
               clearErrors();

               document.getElementById('name').value = btn.dataset.name || '';
               document.getElementById('full_name').value = btn.dataset.full_name || '';
               document.getElementById('title').value = btn.dataset.title || '';
               document.getElementById('email').value = btn.dataset.email || '';
               document.getElementById('mobile_phone').value = btn.dataset.mobile_phone || '';
               document.getElementById('link_name').value = btn.dataset.link_name || '';
               statusSelect.value = btn.dataset.status || '1';

               initialPhotoUrl = btn.dataset.photoUrl || '';
               if (initialPhotoUrl) {
                  setPhotoPreview(initialPhotoUrl, true);
               } else {
                  setPhotoPreview(defaultPhotoUrl, false);
               }
            });
         });

         form.addEventListener('submit', function(e) {
            e.preventDefault();

            btnSubmit.disabled = true;
            btnSubmit.innerText = 'Menyimpan...';
            clearErrors();

            const formData = new FormData(form);
            const method = methodInput.value;

            if (method === 'PUT') {
               formData.set('_method', 'PUT');
            }

            fetch(form.action, {
                  method: 'POST',
                  headers: {
                     'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                     'Accept': 'application/json'
                  },
                  body: formData
               })
               .then(async res => {
                  const contentType = res.headers.get('content-type');
                  if (contentType && contentType.includes('application/json')) {
                     return res.json();
                  }
                  throw new Error('Respon server tidak valid (' + res.status + ')');
               })
               .then(data => {
                  btnSubmit.disabled = false;
                  btnSubmit.innerText = 'Simpan';

                  if (data.errors) {
                     if (data.errors.general) {
                        generalError.innerText = Array.isArray(data.errors.general) ? data.errors.general[
                           0] : data.errors.general;
                        generalError.classList.remove('d-none');
                     }

                     Object.keys(data.errors).forEach(key => {
                        const input = form.querySelector(`[name="${key}"]`);
                        if (input) {
                           const errorEl = document.createElement('div');
                           errorEl.classList.add('text-danger', 'small', 'mt-1', 'field-error');
                           errorEl.innerText = Array.isArray(data.errors[key]) ? data.errors[key][0] :
                              data.errors[key];
                           input.insertAdjacentElement('afterend', errorEl);
                        }
                     });
                  } else if (data.success) {
                     const modal = bootstrap.Modal.getInstance(document.getElementById('vcardModal'));
                     if (modal) modal.hide();
                     location.reload();
                  }
               })
               .catch(err => {
                  console.error('Error:', err);
                  btnSubmit.disabled = false;
                  btnSubmit.innerText = 'Simpan';

                  generalError.innerText = err.message || 'Terjadi kesalahan saat menyimpan data.';
                  generalError.classList.remove('d-none');
               });
         });
      });
   </script>
@endpush
