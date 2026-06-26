<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- PWA  -->
        <meta name="theme-color" content="#6777ef"/>
        <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
        <link rel="manifest" href="{{ asset('/manifest.json') }}">
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>MKM Asset Management</title>
        <link href="{{asset('assets/css/styles.css')}}" rel="stylesheet" />
        <link rel="icon" href="{{ asset('assets/img/logo_kop2.gif') }}">
        <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
        <script src={{ url("https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js") }}></script>
        <script src="{{ url('https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ url('https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap.min.js') }}"></script>
        <!-- DataTables -->
        <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
         <!-- DataTables  & Plugins -->
        <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
        <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
        <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
        <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
        <script src="{{ url('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js') }}"></script>
        <script src="{{ url('https://cdn.datatables.net/datetime/1.1.1/js/dataTables.dateTime.min.js') }}"></script>

         <!-- Include cleave.js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js"></script>

         <!-- Include Chosen CSS -->
         <link href="{{asset('chosen/chosen.min.css')}}" rel="stylesheet" />

         <!-- Include Chart CSS -->
         <script src="{{asset('canvasjs.min.js')}}"></script>

         <!-- Include Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

<!-- Include Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>


         <!-- Include Chosen JS -->
        <script src="{{asset('chosen/chosen.jquery.min.js')}}"></script>

        <style>
            body.topnav-only #layoutSidenav #layoutSidenav_content {
                margin-left: 0 !important;
                padding-left: 0;
                top: 3.625rem;
            }

            body.topnav-only #layoutSidenav #layoutSidenav_content:before {
                display: none !important;
            }

            .asset-topbar .navbar-brand img {
                max-width: 180px;
                object-fit: contain;
            }

            .asset-topbar .topbar-link {
                border-bottom: 3px solid transparent;
                color: #4a5568;
                font-weight: 600;
                padding: 1rem .9rem;
            }

            .asset-topbar .topbar-link:hover,
            .asset-topbar .topbar-link.active {
                border-bottom-color: #0061f2;
                color: #0061f2;
            }

            .asset-actionbar {
                background: #f8fafc;
                border: 1px solid #e5e7eb;
                border-radius: 8px;
                display: flex;
                flex-wrap: wrap;
                gap: .75rem;
                justify-content: space-between;
                padding: .85rem;
            }

            .asset-actionbar__group {
                display: flex;
                flex-wrap: wrap;
                gap: .5rem;
            }

            .asset-actionbar .btn {
                align-items: center;
                display: inline-flex;
                gap: .45rem;
                min-height: 34px;
            }

            .asset-table-filters th {
                background: #f8fafc;
                padding: .35rem .5rem !important;
            }

            .asset-table-filters .form-control {
                min-width: 92px;
            }

            @media (max-width: 991.98px) {
                body.topnav-only #layoutSidenav #layoutSidenav_content {
                    top: 3.625rem;
                }

                .asset-topbar .topbar-link {
                    border-bottom: 0;
                    border-left: 3px solid transparent;
                    padding: .75rem 1rem;
                }

                .asset-topbar .topbar-link:hover,
                .asset-topbar .topbar-link.active {
                    border-left-color: #0061f2;
                }
            }
        </style>

    </head>
    <body class="nav-fixed topnav-only">
        @include('layouts.includes._topbar')
            <div id="layoutSidenav">
                    <div id="layoutSidenav_content">
                        @yield('content')
                        <footer class="footer-admin mt-auto footer-light">
                            <div class="container-xl px-4">
                                <div class="row">
                                    <div class="col-md-6 small"></div>
                                    <div class="col-md-6 text-md-end small">
                                     Copyright PT Mitsubishi Krama Yudha Motors and Manufacturing&copy; 2023
                                    </div>
                                </div>
                            </div>
                        </footer>
                    </div>
            </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src={{asset('assets/js/scripts.js')}} ></script>
        <script src="{{ asset('/sw.js') }}"></script>
<script>
   if ("serviceWorker" in navigator) {
      // Register a service worker hosted at the root of the
      // site using the default scope.
      navigator.serviceWorker.register("/sw.js").then(
      (registration) => {
         console.log("Service worker registration succeeded:", registration);
      },
      (error) => {
         console.error(`Service worker registration failed: ${error}`);
      },
    );
  } else {
     console.error("Service workers are not supported.");
  }
</script>
    </body>
</html>
