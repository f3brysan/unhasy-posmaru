@extends('layouts.main')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Ganti Password</h5>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <form id="changePasswordForm">
                            @csrf
                            <div class="mb-3">
                                <label for="old_password" class="form-label fw-bold">Password Lama</label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('old_password') is-invalid @enderror"
                                        id="old_password" name="old_password" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button"
                                        data-target="old_password">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                                @error('old_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label fw-bold">Password Baru</label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                                        id="new_password" name="new_password" minlength="8" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button"
                                        data-target="new_password">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                                <div id="password-strength" class="mt-2"></div>
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="new_password_confirmation" class="form-label fw-bold">Konfirmasi Password
                                    Baru</label>
                                <div class="input-group">
                                    <input type="password"
                                        class="form-control @error('new_password_confirmation') is-invalid @enderror"
                                        id="new_password_confirmation" name="new_password_confirmation" minlength="8"
                                        required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button"
                                        data-target="new_password_confirmation">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                                @error('new_password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 float-end">
                                <button type="submit" class="btn btn-primary">Ganti Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        function checkPasswordStrength(password) {
            let strength = 0;
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;

            let strengthText = '';
            let strengthClass = '';
            switch (strength) {
                case 0:
                case 1:
                    strengthText = 'Sangat Lemah';
                    strengthClass = 'text-danger';
                    break;
                case 2:
                    strengthText = 'Lemah';
                    strengthClass = 'text-warning';
                    break;
                case 3:
                    strengthText = 'Sedang';
                    strengthClass = 'text-info';
                    break;
                case 4:
                    strengthText = 'Kuat';
                    strengthClass = 'text-success';
                    break;
                case 5:
                    strengthText = 'Sangat Kuat';
                    strengthClass = 'text-success fw-bold';
                    break;
            }
            return {
                text: strengthText,
                class: strengthClass
            };
        }

        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('new_password');
            const strengthDiv = document.getElementById('password-strength');

            passwordInput.addEventListener('input', function() {
                const val = passwordInput.value;
                const result = checkPasswordStrength(val);
                strengthDiv.textContent = result.text;
                strengthDiv.className = result.class;
            });

            // Optional: Prevent form submit if password < 8 chars
            document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
                if (passwordInput.value.length < 8) {
                    e.preventDefault();
                    strengthDiv.textContent = 'Password minimal 8 karakter';
                    strengthDiv.className = 'text-danger';
                    passwordInput.focus();
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.toggle-password').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const targetId = btn.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const icon = btn.querySelector('i');
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });
        });

        // SweetAlert2 confirmation and AJAX submit for changePasswordForm using jQuery
        $(document).ready(function() {
            $('#changePasswordForm').on('submit', function(e) {
                e.preventDefault();

                var $form = $(this);
                var $passwordInput = $('#new_password');
                var $strengthDiv = $('#password-strength');

                if ($passwordInput.val().length < 8) {
                    $strengthDiv.text('Password minimal 8 karakter').attr('class', 'text-danger');
                    $passwordInput.focus();
                    return;
                }

                Swal.fire({
                    title: 'Yakin ingin mengganti password?',
                    text: "Pastikan password baru sudah benar.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, ganti password!',
                    cancelButtonText: 'Batal'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ URL::to('ganti-password/store') }}",
                            method: 'POST',
                            data: $('#changePasswordForm').serialize(),
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                if (data.status === 'success') {
                                    Swal.fire(
                                        'Berhasil!',
                                        data.message ||
                                        'Password berhasil diganti.',
                                        'success'
                                    ).then(function() {
                                        window.location.reload();
                                    });
                                }
                            },
                            error: function(xhr) {
                                var msg = 'Terjadi kesalahan pada server.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    msg = xhr.responseJSON.message;
                                }
                                toastr.error(msg);
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
