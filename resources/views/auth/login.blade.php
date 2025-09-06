<!doctype html>

<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ URL::to('/') }}/assets/" data-template="horizontal-menu-template-no-customizer">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Login | POSMARU</title>

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
</head>

<body>
    <!-- Content -->

    <div class="authentication-wrapper authentication-cover authentication-bg">
        <div class="authentication-inner row">
            <!-- /Left Text -->
            <div class="d-none d-lg-flex col-lg-7 p-0">
                <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
                    <img src="{{ URL::to('/') }}/assets/img/illustrations/402891-PDEK8O-761-removebg-preview.png"
                        alt="auth-login-cover" class="img-fluid my-5 auth-illustration"
                        data-app-light-img="illustrations/402891-PDEK8O-761-removebg-preview.png"
                        data-app-dark-img="illustrations/402891-PDEK8O-761-removebg-preview.png" />

                    {{-- <img src="{{ URL::to('/') }}/assets/img/illustrations/bg-shape-image-light.png"
                        alt="auth-login-cover" class="platform-bg"
                        data-app-light-img="illustrations/bg-shape-image-light.png"
                        data-app-dark-img="illustrations/bg-shape-image-dark.png" /> --}}
                </div>
            </div>
            <!-- /Left Text -->

            <!-- Login -->
            <div class="d-flex col-12 col-lg-5 align-items-center p-sm-5 p-4">
                <div class="w-px-400 mx-auto">
                    <!-- Logo -->
                    <div class="app-brand mb-4">

                    </div>
                    <!-- /Logo -->
                    <h3 class="mb-1">Welcome to POSMARU 👋</h3>
                    <p class="mb-4">Please sign-in to your account and start the adventure</p>

                    <div class="mt-3">
                        <form id="formAuthentication">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">NIM / NIP</label>
                                <input type="text" class="form-control" id="email" name="no_induk"
                                    placeholder="Masukkan NIM / NIP" autofocus />
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">Password</label>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                            </div>
                            <div class="mb-3">
                            </div>
                            <button type="submit" class="btn btn-primary d-grid w-100">Sign in</button>
                        </form>
                    </div>

                    <div class="mt-3">
                        <p class="text-center mt-3">
                            <span>Anda belum terdaftar?</span>
                            <a href="{{ URL::to('register') }}">
                                <span>Daftar</span>
                            </a>
                        </p>
                    </div>

                    <div class="divider my-6">
                        <div class="divider-text">Panduan Penggunaan</div>
                        <div class="d-flex justify-content-between">
                            <a href="https://intip.in/PanduanPOSMARU" target="_blank" class="btn btn-primary d-grid w-100 mx-1">Dokumentasi</a>
                            <a href="https://intip.in/videotutorposmaru" target="_blank" class="btn btn-primary d-grid w-100 mx-1">Video</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Login -->
        </div>
    </div>

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

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ URL::to('/') }}/assets/vendor/libs/@form-validation/popular.js"></script>
    <script src="{{ URL::to('/') }}/assets/vendor/libs/@form-validation/bootstrap5.js"></script>
    <script src="{{ URL::to('/') }}/assets/vendor/libs/@form-validation/auto-focus.js"></script>

    <!-- Main JS -->
    <script src="{{ URL::to('/') }}/assets/js/main.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Page JS -->
    {{-- <script src="{{ URL::to('/') }}/assets/js/pages-auth.js"></script> --}}

    <script>
        $(document).ready(function() {
            $("#formAuthentication").on("submit", function(e) {
                e.preventDefault();
                var formData = $(this).serialize();

                toastr.info('Otentikasi sedang diproses', 'Mohon tunggu...');

                $.ajax({
                    type: "POST",
                    url: "{{ URL::to('auth') }}",
                    data: formData,
                    dataType: "JSON",
                    success: function(response) {
                        console.log(response);

                        if (response.status == 'success') {
                            toastr.success(response.message, 'Sukses!');
                            window.location.href = "{{ URL::to('beranda') }}";
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseJSON.message);
                        toastr.error(xhr.responseJSON.message, 'Oops!');
                    }
                });

            });
        });
    </script>
</body>

</html>
