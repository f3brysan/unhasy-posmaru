<!doctype html>
<html lang="en" class=" layout-wide  customizer-hide" dir="ltr" data-skin="default" data-bs-theme="light"
    data-assets-path="{{ URL::to('/') }}/assets/" data-template="horizontal-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />
    <title>Registrasi | POSMARU</title>

    <meta name="description" content="POSMARU" />
    <!-- Canonical SEO -->
    <meta name="keywords"
        content="Vuexy bootstrap dashboard, vuexy bootstrap 5 dashboard, themeselection, html dashboard, web dashboard, frontend dashboard, responsive bootstrap theme" />
    <meta property="og:title" content="POSMARU" />
    <meta property="og:type" content="product" />
    <meta property="og:url"
        content="https://themeforest.net/item/vuexy-vuejs-html-laravel-admin-dashboard-template/23328599" />
    <meta property="og:image" content="https://pixinvent.com/wp-content/uploads/2023/06/vuexy-hero-image.png" />
    <meta property="og:description"
        content="Vuexy is the best bootstrap 5 dashboard for responsive web apps. Streamline your app development process with ease." />
    <meta property="og:site_name" content="Pixinvent" />
    <link rel="canonical"
        href="https://themeforest.net/item/vuexy-vuejs-html-laravel-admin-dashboard-template/23328599" />



    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ URL::to('/') }}/assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/vendor/fonts/iconify-icons.css" />



    <script src="{{ URL::to('/') }}/assets/vendor/libs/@algolia/autocomplete-js.js"></script>

    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css  -->

    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/vendor/libs/node-waves/node-waves.css" />


    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/vendor/libs/pickr/pickr-themes.css" />

    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/vendor/css/core.css" />
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/demo.css" />


    <!-- Vendors CSS -->

    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- endbuild -->




    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/vendor/css/pages/page-misc.css" />

    <!-- Helpers -->
    <script src="{{ URL::to('/') }}/assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="{{ URL::to('/') }}/assets/vendor/js/template-customizer.js"></script>

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->

    <script src="{{ URL::to('/') }}/assets/js/config.js"></script>

</head>

<body>



    <!-- Content -->

    <!--Under Maintenance -->
    <div class="container-xxl container-p-y">
        <div class="misc-wrapper">
            <h3 class="mb-2 mx-2">{{ $checkActivity->name }}</h3>
            <p class="mb-6 mx-2">Batas Pendaftaran : {{ Carbon\Carbon::parse($checkActivity->registration_start_date)->format('d F Y') }} - {{ Carbon\Carbon::parse($checkActivity->registration_end_date)->format('d F Y') }}</p>
            <h1 class="mb-2 mx-2">Registrasi Ditutup! 🚧</h1>
            
            <a href="{{ URL::to('/') }}" class="btn btn-primary">Back to home</a>
            <div class="mt-12">
                <img src="{{ URL::to('/') }}/assets/img/illustrations/page-misc-under-maintenance.png"
                    alt="page-misc-under-maintenance" width="550" class="img-fluid" />
            </div>
        </div>
    </div>
    <div class="container-fluid misc-bg-wrapper misc-under-maintenance-bg-wrapper mt-5">
        <img src="{{ URL::to('/') }}/assets/img/illustrations/bg-shape-image-light.png" height="355"
            alt="page-misc-under-maintenance" data-app-light-img="illustrations/bg-shape-image-light.png"
            data-app-dark-img="illustrations/bg-shape-image-dark.png" class="mt-5" />
    </div>
    <!-- /Under Maintenance -->
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/theme.js  -->


    <script src="{{ URL::to('/') }}/assets/vendor/libs/jquery/jquery.js"></script>

    <script src="{{ URL::to('/') }}/assets/vendor/libs/popper/popper.js"></script>
    <script src="{{ URL::to('/') }}/assets/vendor/js/bootstrap.js"></script>
    <script src="{{ URL::to('/') }}/assets/vendor/libs/node-waves/node-waves.js"></script>



    <script src="{{ URL::to('/') }}/assets/vendor/libs/pickr/pickr.js"></script>



    <script src="{{ URL::to('/') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>


    <script src="{{ URL::to('/') }}/assets/vendor/libs/hammer/hammer.js"></script>

    <script src="{{ URL::to('/') }}/assets/vendor/libs/i18n/i18n.js"></script>


    <script src="{{ URL::to('/') }}/assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->



    <!-- Main JS -->

    <script src="{{ URL::to('/') }}/assets/js/main.js"></script>


    <!-- Page JS -->



</body>

</html>

<!-- beautify ignore:end -->
