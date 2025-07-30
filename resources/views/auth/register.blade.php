<!doctype html>

<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ URL::to('/') }}/assets/" data-template="horizontal-menu-template-no-customizer">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Multi Steps Sign-up - Pages | Vuexy - Bootstrap Admin Template</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ URL::to('/') }}/assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/vendor/fonts/tabler-icons.css" />
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/vendor/css/rtl/core.css" />
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/vendor/css/rtl/theme-default.css" />
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/vendor/libs/typeahead-js/typeahead.css" />
    <!-- Vendor -->
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/vendor/libs/bs-stepper/bs-stepper.css" />
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/vendor/libs/bootstrap-select/bootstrap-select.css" />
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/vendor/libs/select2/select2.css" />
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/vendor/libs/@form-validation/form-validation.css" />

    <!-- Page CSS -->

    <!-- Page -->
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/vendor/css/pages/page-auth.css" />

    <!-- Helpers -->
    <script src="{{ URL::to('/') }}/assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ URL::to('/') }}/assets/js/config.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"
        integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .multi-step-form {
            max-width: 800px;
            margin: 2rem auto;
            background: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.1);
            padding: 2rem;
        }

        .steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
        }

        .steps::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background: #ebedf0;
            z-index: 1;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #ebedf0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.5rem;
            color: #6e6b7b;
            font-weight: 600;
        }

        .step.active .step-number {
            background: #7367f0;
            color: white;
        }

        .step.completed .step-number {
            background: #28c76f;
            color: white;
        }

        .step-title {
            color: #6e6b7b;
            font-size: 0.875rem;
        }

        .step.active .step-title {
            color: #7367f0;
            font-weight: 600;
        }

        .step.completed .step-title {
            color: #28c76f;
        }

        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.786rem 1.5rem;
            border-radius: 0.358rem;
            font-weight: 500;
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background-color: #7367f0;
            color: #fff;
        }

        .btn-outline-secondary {
            background-color: transparent;
            border: 1px solid #d8d6de;
            color: #6e6b7b;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #5e5873;
        }

        .form-control {
            width: 100%;
            padding: 0.438rem 1rem;
            border: 1px solid #d8d6de;
            border-radius: 0.358rem;
            background-color: #fff;
            color: #6e6b7b;
        }

        .form-control:focus {
            border-color: #7367f0;
            outline: 0;
            box-shadow: 0 3px 10px 0 rgba(34, 41, 47, 0.1);
        }

        .invalid-feedback {
            color: #ea5455;
            font-size: 0.857rem;
            margin-top: 0.25rem;
        }

        .is-invalid {
            border-color: #ea5455 !important;
        }

        .summary-item {
            display: flex;
            margin-bottom: 1rem;
        }

        .summary-label {
            font-weight: 600;
            min-width: 150px;
            color: #5e5873;
        }

        .success-icon {
            font-size: 5rem;
            color: #28c76f;
            margin-bottom: 2rem;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <!-- Content -->

    <div class="authentication-wrapper authentication-cover authentication-bg">
        <div class="authentication-inner row">
            <!-- Left Text -->
            <div
                class="d-none d-lg-flex col-lg-4 align-items-center justify-content-center p-5 auth-cover-bg-color position-relative auth-multisteps-bg-height">
                <img src="{{ URL::to('/') }}/assets/img/illustrations/auth-register-multisteps-illustration.png"
                    alt="auth-register-multisteps" class="img-fluid" width="280" />

                <img src="{{ URL::to('/') }}/assets/img/illustrations/bg-shape-image-light.png"
                    alt="auth-register-multisteps" class="platform-bg"
                    data-app-light-img="illustrations/bg-shape-image-light.png"
                    data-app-dark-img="illustrations/bg-shape-image-dark.png" />
            </div>
            <!-- /Left Text -->

            <!--  Multi Steps Registration -->
            <div class="d-flex col-lg-8 align-items-center justify-content-center p-sm-5 p-3">
                <div class="w-px-700">
                    <div id="multiStepsValidation" class="bs-stepper shadow-none">
                        <div class="bs-stepper-content">
                            <!-- Step Indicators -->
                            <div class="steps">
                                <div class="step active" data-step="1">
                                    <div class="step-number">1</div>
                                    <div class="step-title">Data Akademik</div>
                                </div>
                                <div class="step" data-step="2">
                                    <div class="step-number">2</div>
                                    <div class="step-title">Data Pribadi</div>
                                </div>
                                <div class="step" data-step="3">
                                    <div class="step-number">3</div>
                                    <div class="step-title">Konfirmasi</div>
                                </div>
                            </div>

                            <!-- Form Steps -->
                            <form id="registrationForm">
                                @csrf
                                <!-- Step 1: Data Akademik -->
                                <div class="form-step active" data-step="1">
                                    <div class="form-group">
                                        <label for="nim" class="form-label">NIM <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nim" name="nim" required>
                                        <div class="invalid-feedback">NIM wajib diisi</div>
                                    </div>

                                    <div class="form-group">
                                        <label for="nama" class="form-label">Nama Lengkap <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nama" name="nama" required readonly>
                                        <div class="invalid-feedback">Nama wajib diisi</div>
                                    </div>

                                    <div class="form-group">
                                        <label for="fakultas" class="form-label">Fakultas <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="fakultas" name="fakultas" required disabled>
                                            <option value="">Pilih Fakultas</option>
                                            @foreach ($faculties as $faculty)
                                            <option value="{{ $faculty->kode_fakultas }}">Fakultas {{ $faculty->fakultas
                                                }}</option> @endforeach
                                        </select>
                                        <input type="hidden" class="form-control" name="fakultas_kode" id="fakultas_kode">
                                        <div class="invalid-feedback">
                                            Fakultas wajib dipilih</div>
                                    </div>

                                    <div class="form-group">
                                        <label for="prodi" class="form-label">Program Studi <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="prodi" name="prodi" required disabled>
                                            <option value="">Pilih Program Studi</option>
                                            @foreach ($prodis as $prodi)
                                            <option value="{{ $prodi->kode_prodi }}">Program Studi {{ $prodi->prodi }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" class="form-control" name="prodi_kode" id="prodi_kode">
                                        <div class="invalid-feedback">Program studi wajib dipilih</div>
                                    </div>

                                    <div class="form-group">
                                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                        <div class="invalid-feedback">Jenis kelamin wajib dipilih</div>
                                    </div>

                                    <div class="form-actions">
                                        <button type="button" class="btn btn-outline-secondary"
                                            disabled>Sebelumnya</button>
                                        <button type="button" class="btn btn-primary next-step">Selanjutnya</button>
                                    </div>
                                </div>

                                <!-- Step 2: Data Pribadi -->
                                <div class="form-step" data-step="2">
                                    <div class="form-group">
                                        <label for="ukuran_kaos" class="form-label">Ukuran Kaos <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="ukuran_kaos" name="ukuran_kaos" required>
                                            <option value="">Pilih Ukuran Kaos</option>
                                            <option value="S">S</option>
                                            <option value="M">M</option>
                                            <option value="L">L</option>
                                            <option value="XL">XL</option>
                                            <option value="XXL">XXL</option>
                                        </select>
                                        <div class="invalid-feedback">Ukuran kaos wajib dipilih</div>
                                    </div>

                                    <div class="form-group">
                                        <label for="nomor_hp" class="form-label">Nomor HP <span
                                                class="text-danger">*</span></label>
                                        <input type="tel" class="form-control" id="nomor_hp" name="nomor_hp"
                                            placeholder="Contoh: 08123456789" required>
                                        <div class="invalid-feedback">Nomor HP wajib diisi</div>
                                    </div>

                                    <div class="form-actions">
                                        <button type="button"
                                            class="btn btn-outline-secondary prev-step">Sebelumnya</button>
                                        <button type="button" class="btn btn-primary next-step">Selanjutnya</button>
                                    </div>
                                </div>

                                <!-- Step 3: Konfirmasi -->
                                <div class="form-step" data-step="3">
                                    <h4 class="mb-3">Konfirmasi Data</h4>

                                    <div class="summary-item">
                                        <div class="summary-label">NIM:</div>
                                        <div id="summary-nim"></div>
                                    </div>

                                    <div class="summary-item">
                                        <div class="summary-label">Nama:</div>
                                        <div id="summary-nama"></div>
                                    </div>

                                    <div class="summary-item">
                                        <div class="summary-label">Program Studi:</div>
                                        <div id="summary-prodi"></div>
                                    </div>

                                    <div class="summary-item">
                                        <div class="summary-label">Fakultas:</div>
                                        <div id="summary-fakultas"></div>
                                    </div>

                                    <div class="summary-item">
                                        <div class="summary-label">Jenis Kelamin:</div>
                                        <div id="summary-jenis_kelamin"></div>
                                    </div>

                                    <div class="summary-item">
                                        <div class="summary-label">Ukuran Kaos:</div>
                                        <div id="summary-ukuran_kaos"></div>
                                    </div>

                                    <div class="summary-item">
                                        <div class="summary-label">Nomor HP:</div>
                                        <div id="summary-nomor_hp"></div>
                                    </div>

                                    <div class="form-actions">
                                        <button type="button"
                                            class="btn btn-outline-secondary prev-step">Sebelumnya</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>

                                <!-- Step 4: Success -->
                                <div class="form-step text-center" data-step="4">
                                    <div class="success-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <h3 class="mb-2">Pendaftaran Berhasil!</h3>
                                    <p class="mb-4">Data Anda telah berhasil terdaftar.</p>
                                    <button type="button" class="btn btn-primary" id="reset-form">Daftar
                                        Lagi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- / Multi Steps Registration -->
        </div>
    </div>

    <script>
        // Check selected custom option
        window.Helpers.initCustomOptionCheck();
    </script>

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="{{ URL::to('/') }}/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ URL::to('/') }}/assets/vendor/libs/popper/popper.js"></script>
    <script src="{{ URL::to('/') }}/assets/vendor/js/bootstrap.js"></script>
    <script src="{{ URL::to('/') }}/assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="{{ URL::to('/') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ URL::to('/') }}/assets/vendor/libs/hammer/hammer.js"></script>
    <script src="{{ URL::to('/') }}/assets/vendor/libs/i18n/i18n.js"></script>
    <script src="{{ URL::to('/') }}/assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="{{ URL::to('/') }}/assets/vendor/js/menu.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- endbuild -->

    <!-- Main JS -->
    <script src="{{ URL::to('/') }}/assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="{{ URL::to('/') }}/assets/js/pages-auth-multisteps.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle next step button
            $('.next-step').click(function() {
                const currentStep = $('.form-step.active');
                const currentStepNumber = parseInt(currentStep.data('step'));
                const inputs = currentStep.find('input, select');
                let isValid = true;

                // Validate all inputs in current step
                inputs.each(function() {
                    const input = $(this);
                    if (input.prop('required') && !input.val()) {
                        input.addClass('is-invalid');
                        isValid = false;
                    } else {
                        input.removeClass('is-invalid');
                    }
                });

                // Special validation for phone number
                const nomorHp = $('#nomor_hp').val();
                if (currentStepNumber === 2 && nomorHp && !/^[0-9]{10,13}$/.test(nomorHp)) {
                    $('#nomor_hp').addClass('is-invalid');
                    $('#nomor_hp').next('.invalid-feedback').text('Nomor HP harus 10-13 digit angka');
                    isValid = false;
                }

                if (isValid) {
                    // Move to next step
                    currentStep.removeClass('active');
                    $(`.form-step[data-step="${currentStepNumber + 1}"]`).addClass('active');

                    // Update step indicators
                    $('.step').removeClass('active');
                    $(`.step[data-step="${currentStepNumber + 1}"]`).addClass('active');

                    // If moving to confirmation step, update summary
                    if (currentStepNumber + 1 === 3) {
                        updateSummary();
                    }
                }
            });

            // Handle previous step button
            $('.prev-step').click(function() {
                const currentStep = $('.form-step.active');
                const currentStepNumber = parseInt(currentStep.data('step'));

                currentStep.removeClass('active');
                $(`.form-step[data-step="${currentStepNumber - 1}"]`).addClass('active');

                // Update step indicators
                $('.step').removeClass('active');
                $(`.step[data-step="${currentStepNumber - 1}"]`).addClass('active');
            });

            // Handle form submission
            $('#registrationForm').submit(function(e) {
                e.preventDefault();

                formData = $(this).serialize();

                // Disable submit button to prevent multiple submissions
                $('button[type="submit"]').prop('disabled', true).text('Mengirim...');

                $.ajax({
                    type: "POST",
                    url: "{{ URL::to('store-register') }}",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        // Show success step
                        $('.form-step.active').removeClass('active');
                        $('.form-step[data-step="4"]').addClass('active');

                        // Update step indicators
                        $('.step').removeClass('active');
                        $('.step[data-step="3"]').addClass('completed');
                    },
                    error: function(xhr, status, error) {
                        $('button[type="submit"]').prop('disabled', false).text('Submit');
                        // Handle errors
                        console.error(xhr);
                        toastr.error(xhr.responseJSON.message, 'Error');
                    }
                });
            });

            // Handle reset form button
            $('#reset-form').click(function() {
                // Reset form
                $('#registrationForm')[0].reset();

                // Go back to first step
                $('.form-step.active').removeClass('active');
                $('.form-step[data-step="1"]').addClass('active');

                // Reset step indicators
                $('.step').removeClass('active completed');
                $('.step[data-step="1"]').addClass('active');
            });

            // Function to update confirmation summary
            function updateSummary() {
                $('#summary-nim').text($('#nim').val());
                $('#summary-nama').text($('#nama').val());
                $('#summary-prodi').text($('#prodi option:selected').text());
                $('#summary-fakultas').text($('#fakultas option:selected').text());
                $('#summary-jenis_kelamin').text($('#jenis_kelamin option:selected').text());
                $('#summary-ukuran_kaos').text($('#ukuran_kaos').val());
                $('#summary-nomor_hp').text($('#nomor_hp').val());
            }

            // Real-time validation for phone number
            $('#nomor_hp').on('input', function() {
                const input = $(this);
                if (!/^[0-9]{0,13}$/.test(input.val())) {
                    input.addClass('is-invalid');
                    input.next('.invalid-feedback').text('Hanya boleh mengandung angka');
                } else if (input.val().length < 10 && input.val().length > 0) {
                    input.addClass('is-invalid');
                    input.next('.invalid-feedback').text('Nomor HP minimal 10 digit');
                } else {
                    input.removeClass('is-invalid');
                }
            });

            $("#nim").on('keyup', function() {
                const nim = $(this).val();
                console.log(nim);

                // Clear previous timeout if it exists
                if (typeof this.delayTimer !== 'undefined') {
                    clearTimeout(this.delayTimer);
                }

                // Set new timeout
                this.delayTimer = setTimeout(() => {
                    $.ajax({
                        type: "POST",
                        url: "{{ URL::to('api/siakadu/get-data/mahasiswa') }}",
                        // send the NIM as data
                        data: {
                            nim: nim,
                            _token: "{{ csrf_token() }}"
                        },
                        dataType: "json", // expected response type
                        success: function(response) {
                            // Handle successful response
                            console.log(response);

                            // Update form fields with response data                            
                            $("#nama").val(response.data.name);
                            $("#prodi").val(response.data.prodi_kode);
                            $("#fakultas").val(response.data.fakultas_kode);
                            $("#fakultas_kode").val(response.data.fakultas_kode);
                            $("#prodi_kode").val(response.data.prodi_kode);
                        },
                        error: function(xhr, status, error) {
                            // Handle errors
                            console.error(xhr);
                            toastr.error(xhr.responseJSON.message, 'Error');
                            $("#nama").val('');
                            $("#prodi").val('');
                            $("#fakultas").val('');
                            $("#fakultas_kode").val('');
                            $("#prodi_kode").val('');
                        }
                    });
                }, 1000); // 2000ms = 2 seconds delay
            });
        });
    </script>
</body>

</html>