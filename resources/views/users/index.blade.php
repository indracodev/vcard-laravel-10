@extends('layouts.app')
@section('titlePage')
   Manajemen Akun
@endsection
@section('content')
   <section aria-label="Manajemen Akun Pengguna" class="py-5">
      <div class="container-fluid px-lg-4 px-xxl-5">
         @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
               {{ session('success') }}
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
         @endif
         @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
               {{ session('error') }}
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
         @endif

         <div class="row row-gap-4">
            <div class="col col-12">
               <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                  <div>
                     <h1 class="h3 fw-bold mb-1">Manajemen Akun Pengguna</h1>
                     <p class="text-muted small mb-0">
                        Kelola akses akun Administrator dan Tim HCS untuk pengelolaan data {{ config('app.name') }}.
                     </p>
                  </div>
               </div>
            </div>

            <div class="col col-12 position-sticky top-0 z-3">
               <div class="card bg-body-tertiary shadow">
                  <div class="card-body">
                     <div class="row row-gap-3 justify-content-between">
                        <div class="col col-12 col-md">
                           <form id="userSearchForm" role="search">
                              <label for="userSearchInput" class="visually-hidden">Cari akun pengguna</label>
                              <div class="input-group">
                                 <span class="input-group-text">
                                    <i class="bi bi-search" aria-hidden="true"></i>
                                 </span>
                                 <input type="search" id="userSearchInput" class="form-control"
                                    placeholder="Cari username, email, atau role" aria-label="Cari akun pengguna"
                                    autocomplete="off">
                              </div>
                           </form>
                        </div>
                        <div class="col col-12 col-md-auto">
                           <button type="button" id="btnAddUser"
                              class="btn btn-primary bg-gradient w-100 d-flex align-items-center justify-content-center gap-2"
                              data-bs-toggle="modal" data-bs-target="#userModal">
                              <i class="bi bi-person-plus" aria-hidden="true"></i>
                              <span>Tambah Akun</span>
                           </button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>

            <div class="col col-12">
               <div id="userSearchEmptyState" class="alert alert-danger p-5 fs-5 text-center d-none" role="alert"
                  aria-live="polite">
                  <i class="bi bi-exclamation-triangle-fill me-2" aria-hidden="true"></i>
                  <span>Tidak ada akun pengguna yang cocok dengan pencarian Anda.</span>
               </div>

               <div id="userTableCard" class="card shadow">
                  <div class="table-responsive">
                     <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                           <tr>
                              <th scope="col" class="ps-4">Username</th>
                              <th scope="col">Email</th>
                              <th scope="col">Role</th>
                              <th scope="col" class="text-end pe-4">Aksi</th>
                           </tr>
                        </thead>
                        <tbody id="userList">
                           @php
                              $currentUserId = auth()->id() ?? session('user_id');
                           @endphp
                           @foreach ($users as $u)
                              @php
                                 $isSelf = (int) $u->id === (int) $currentUserId;
                                 $searchableUser = mb_strtolower(
                                     implode(' ', [$u->name, $u->email, $u->role->value, $u->role->label()]),
                                 );
                              @endphp
                              <tr class="user-item" data-search="{{ $searchableUser }}">
                                 <td class="ps-4 fw-medium">
                                    {{ $u->name }}
                                    @if ($isSelf)
                                       <span class="badge text-bg-info ms-1">Anda</span>
                                    @endif
                                 </td>
                                 <td>{{ $u->email }}</td>
                                 <td>
                                    <span class="badge rounded-pill {{ $u->role->badgeClass() }}">
                                       {{ $u->role->label() }}
                                    </span>
                                 </td>
                                 <td class="text-end pe-4">
                                    <div class="d-inline-flex gap-2">
                                       <button type="button" class="btn btn-sm btn-outline-primary btnEditUser"
                                          data-id="{{ $u->id }}"
                                          data-update-url="{{ route('users.update', $u->id) }}"
                                          data-name="{{ $u->name }}" data-email="{{ $u->email }}"
                                          data-role="{{ $u->role->value }}" data-is-self="{{ $isSelf ? '1' : '0' }}"
                                          data-bs-toggle="modal" data-bs-target="#userModal"
                                          aria-label="Edit akun {{ $u->name }}"
                                          title="Edit akun {{ $u->name }}">
                                          <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                          <span>Edit</span>
                                       </button>
                                       @if (! $isSelf)
                                          <button type="button" class="btn btn-sm btn-outline-danger btnDeleteUser"
                                             data-delete-url="{{ route('users.destroy', $u->id) }}"
                                             data-name="{{ $u->name }}" aria-label="Hapus akun {{ $u->name }}"
                                             title="Hapus akun {{ $u->name }}">
                                             <i class="bi bi-trash" aria-hidden="true"></i>
                                             <span>Hapus</span>
                                          </button>
                                       @endif
                                    </div>
                                 </td>
                              </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
@endsection
@push('modals')
   <div id="userModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
      role="dialog" aria-modal="true" aria-labelledby="userModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-fullscreen-md-down modal-dialog-centered modal-dialog-scrollable">
         <form id="userForm" class="modal-content" method="POST">
            @csrf
            <input type="hidden" name="_method" id="userFormMethod" value="POST">

            <div class="modal-header bg-body-tertiary">
               <h2 class="modal-title fs-5 text-muted" id="userModalLabel">Tambah Akun</h2>
               <button type="button" class="btn-close rounded-0 border-0 shadow-none" data-bs-dismiss="modal"
                  aria-label="Tutup"></button>
            </div>

            <div class="modal-body d-flex flex-column row-gap-3">
               <div id="userFormGeneralError" class="alert alert-danger small d-none mb-0" role="alert"
                  aria-live="assertive"></div>

               <div class="form-group">
                  <label for="userName" class="form-label small">Username</label>
                  <input type="text" name="name" id="userName" class="form-control" autocomplete="username"
                     required>
               </div>
               <div class="form-group">
                  <label for="userEmail" class="form-label small">Alamat Email</label>
                  <input type="email" name="email" id="userEmail" class="form-control" autocomplete="email"
                     required>
               </div>
               <div class="form-group">
                  <label for="userRole" class="form-label small">Role Akun</label>
                  <select name="role" id="userRole" class="form-select" required>
                     @foreach (\App\Enums\UserRole::cases() as $roleCase)
                        <option value="{{ $roleCase->value }}">{{ $roleCase->label() }}</option>
                     @endforeach
                  </select>
               </div>
               <div class="form-group">
                  <label for="userPassword" class="form-label small" id="userPasswordLabel">Password</label>
                  <input type="password" name="password" id="userPassword" class="form-control"
                     autocomplete="new-password">
                  <div id="passwordHelpText" class="form-text small d-none">
                     Kosongkan jika tidak ingin mengubah password.
                  </div>
               </div>
               <div class="form-group">
                  <label for="userPasswordConfirmation" class="form-label small">Konfirmasi Password</label>
                  <input type="password" name="password_confirmation" id="userPasswordConfirmation" class="form-control"
                     autocomplete="new-password">
               </div>
            </div>

            <div class="modal-footer flex-nowrap bg-body-tertiary">
               <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Batal</button>
               <button type="submit" class="btn btn-success w-100" id="btnUserSubmit">Simpan</button>
            </div>
         </form>
      </div>
   </div>
@endpush
@push('scripts')
   <script>
      document.addEventListener('DOMContentLoaded', function() {
         const searchForm = document.getElementById('userSearchForm');
         const searchInput = document.getElementById('userSearchInput');
         const userItems = Array.from(document.querySelectorAll('.user-item'));
         const emptyState = document.getElementById('userSearchEmptyState');
         const tableCard = document.getElementById('userTableCard');

         const filterUsers = () => {
            if (!searchInput) return;
            const query = searchInput.value.trim().toLowerCase();
            let visibleCount = 0;

            userItems.forEach(row => {
               const haystack = row.dataset.search || '';
               const isMatch = query === '' || haystack.includes(query);
               row.classList.toggle('d-none', !isMatch);
               if (isMatch) visibleCount++;
            });

            if (emptyState) emptyState.classList.toggle('d-none', visibleCount > 0);
            if (tableCard) tableCard.classList.toggle('d-none', visibleCount === 0);
         };

         if (searchForm && searchInput) {
            searchForm.addEventListener('submit', e => {
               e.preventDefault();
               filterUsers();
            });
            searchInput.addEventListener('input', filterUsers);
            searchInput.addEventListener('search', filterUsers);
         }

         const form = document.getElementById('userForm');
         const modalTitle = document.getElementById('userModalLabel');
         const methodInput = document.getElementById('userFormMethod');
         const nameInput = document.getElementById('userName');
         const emailInput = document.getElementById('userEmail');
         const roleSelect = document.getElementById('userRole');
         const passwordInput = document.getElementById('userPassword');
         const passwordConfirmInput = document.getElementById('userPasswordConfirmation');
         const passwordHelpText = document.getElementById('passwordHelpText');
         const btnSubmit = document.getElementById('btnUserSubmit');
         const generalError = document.getElementById('userFormGeneralError');
         const csrfToken = document.querySelector('input[name="_token"]').value;

         const clearErrors = () => {
            form.querySelectorAll('.field-error').forEach(el => el.remove());
            form.querySelectorAll('[aria-invalid="true"]').forEach(input => {
               input.removeAttribute('aria-invalid');
               input.removeAttribute('aria-describedby');
            });
            generalError.classList.add('d-none');
            generalError.innerText = '';
         };

         document.getElementById('btnAddUser').addEventListener('click', () => {
            form.action = '{{ route('users.store') }}';
            methodInput.value = 'POST';
            modalTitle.innerText = 'Tambah Akun Pengguna';
            form.reset();
            roleSelect.value = 'hcs';
            roleSelect.disabled = false;
            passwordInput.required = true;
            passwordConfirmInput.required = true;
            passwordHelpText.classList.add('d-none');
            clearErrors();
         });

         document.querySelectorAll('.btnEditUser').forEach(btn => {
            btn.addEventListener('click', () => {
               form.action = btn.dataset.updateUrl;
               methodInput.value = 'PUT';
               modalTitle.innerText = 'Edit Akun Pengguna';
               form.reset();
               clearErrors();

               nameInput.value = btn.dataset.name || '';
               emailInput.value = btn.dataset.email || '';
               roleSelect.value = btn.dataset.role || 'hcs';
               passwordInput.required = false;
               passwordConfirmInput.required = false;
               passwordHelpText.classList.remove('d-none');
            });
         });

         document.querySelectorAll('.btnDeleteUser').forEach(btn => {
            btn.addEventListener('click', () => {
               const targetName = btn.dataset.name || '';
               const deleteUrl = btn.dataset.deleteUrl;
               if (!deleteUrl) return;

               if (!confirm(`Apakah Anda yakin ingin menghapus akun "${targetName}"?`)) {
                  return;
               }

               btn.disabled = true;

               fetch(deleteUrl, {
                     method: 'DELETE',
                     headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                     }
                  })
                  .then(res => res.json())
                  .then(data => {
                     btn.disabled = false;
                     if (data.success) {
                        location.reload();
                     } else if (data.errors && data.errors.general) {
                        alert(Array.isArray(data.errors.general) ? data.errors.general[0] : data.errors.general);
                     }
                  })
                  .catch(() => {
                     btn.disabled = false;
                     alert('Terjadi kesalahan saat menghapus akun pengguna.');
                  });
            });
         });

         form.addEventListener('submit', function(e) {
            e.preventDefault();
            btnSubmit.disabled = true;
            btnSubmit.innerText = 'Menyimpan...';
            clearErrors();

            const formData = new FormData(form);
            if (methodInput.value === 'PUT') {
               formData.set('_method', 'PUT');
            }

            fetch(form.action, {
                  method: 'POST',
                  headers: {
                     'X-CSRF-TOKEN': csrfToken,
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
                        generalError.innerText = Array.isArray(data.errors.general) ? data.errors.general[0] : data.errors.general;
                        generalError.classList.remove('d-none');
                     }

                     Object.keys(data.errors).forEach(key => {
                        const input = form.querySelector(`[name="${key}"]`);
                        if (input) {
                           const errorId = `user-error-${key}`;
                           const errorEl = document.createElement('div');
                           errorEl.id = errorId;
                           errorEl.setAttribute('role', 'alert');
                           errorEl.classList.add('text-danger', 'small', 'mt-1', 'field-error');
                           errorEl.innerText = Array.isArray(data.errors[key]) ? data.errors[key][0] : data.errors[key];
                           input.setAttribute('aria-invalid', 'true');
                           input.setAttribute('aria-describedby', errorId);
                           input.insertAdjacentElement('afterend', errorEl);
                        }
                     });
                  } else if (data.success) {
                     const modal = bootstrap.Modal.getInstance(document.getElementById('userModal'));
                     if (modal) modal.hide();
                     location.reload();
                  }
               })
               .catch(err => {
                  btnSubmit.disabled = false;
                  btnSubmit.innerText = 'Simpan';
                  generalError.innerText = err.message || 'Terjadi kesalahan saat menyimpan data.';
                  generalError.classList.remove('d-none');
               });
         });
      });
   </script>
@endpush

