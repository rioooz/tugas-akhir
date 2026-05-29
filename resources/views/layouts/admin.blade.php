<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Mahesty Mebel Admin - @yield('title')</title>

    <!-- Custom fonts for this template-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"
        type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Custom styles for this template-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/css/sb-admin-2.min.css"
        rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="{{ asset('css/admin-custom.css') }}" rel="stylesheet">

    <style>
        .notification-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            font-size: 0.75rem;
            font-weight: 700;
            margin-left: 8px;
            animation: pulse 2s infinite;
        }

        .notification-badge.warning {
            background: #ffc107;
            color: #333;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
            }
        }

        .nav-link {
            position: relative;
        }

        /* Consistent Sidebar Color with User Panel */
        .bg-gradient-primary.sidebar {
            background: linear-gradient(180deg, #0e8f2c 0%, #0e8f2c 100%) !important;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
            border-left: 3px solid #DBCEA5;
        }

        .sidebar .sidebar-brand {
            color: #fff;
        }

        .sidebar .sidebar-brand-text {
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .sidebar .sidebar-heading {
            color: rgba(255, 255, 255, 0.7);
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sidebar hr.sidebar-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.15);
        }
    </style>

    @yield('extra_css')

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul style="background-color: #8A7650;" class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.index') }}">
                <div class="sidebar-brand-icon">
                    <img src="/assets/logoo.png" style="width:120px; height:auto">
                </div>
                <div class="sidebar-brand-text mx-3 w-full">Mahesty Mebel</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ request()->routeIs('admin.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.index') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Manajemen
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item {{ request()->routeIs('admin.daftar-barang.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.daftar-barang.index') }}">
                    <i class="fas fa-fw fa-list"></i>
                    <span>Daftar Barang</span>
                </a>
            </li>

            <li class="nav-item {{ request()->routeIs('admin.stock-in.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.stock-in.index') }}">
                    <i class="fas fa-fw fa-arrow-down"></i>
                    <span>Barang Masuk</span>
                </a>
            </li>

            <li class="nav-item {{ request()->routeIs('admin.barang.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.barang.index') }}">
                    <i class="fas fa-fw fa-box-open"></i>
                    <span>Stock Barang</span>
                    @if ($sidebarCriticalStockCount > 0)
                        <span class="notification-badge">{{ $sidebarCriticalStockCount }}</span>
                    @endif
                </a>
            </li>

            <li class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.orders.index') }}">
                    <i class="fas fa-fw fa-shopping-basket"></i>
                    <span>Transaksi</span>
                    @if ($sidebarUnprocessedCount > 0)
                        <span class="notification-badge">{{ $sidebarUnprocessedCount }}</span>
                    @elseif ($sidebarBestsellersCount > 0)
                        <span class="notification-badge warning">{{ $sidebarBestsellersCount }}</span>
                    @endif
                </a>
            </li>
            </a>
            </li>

            <li class="nav-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.customers.index') }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Pelanggan</span>
                </a>
            </li>

            <li class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.reports.index') }}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Laporan</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item - Logout -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); if(confirm('Yakin ingin keluar?')) { document.getElementById('admin-logout-form').submit(); }">
                    <i class="fas fa-fw fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">



        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content" style="background-color: #ECE7D1;">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <span
                                    class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                                <i class="fas fa-user-circle fa-2x"></i>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('admin.profile.index') }}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2"></i>
                                    Profil
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); if(confirm('Yakin ingin keluar?')) { document.getElementById('admin-logout-form').submit(); }">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Flash Messages -->
                    @if (Session::has('success'))
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                toastr.success('{{ Session::get('success') }}');
                            });
                        </script>
                    @endif
                    @if (Session::has('error'))
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                toastr.error('{{ Session::get('error') }}');
                            });
                        </script>
                    @endif
                    @if (Session::has('warning'))
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                toastr.warning('{{ Session::get('warning') }}');
                            });
                        </script>
                    @endif
                    @if (Session::has('info'))
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                toastr.info('{{ Session::get('info') }}');
                            });
                        </script>
                    @endif

                    <!-- Page Title & Breadcrumb -->
                    @if (View::hasSection('page_title') || View::hasSection('breadcrumb'))
                        <div style="margin-bottom: 20px; padding: 15px 0; border-bottom: 1px solid #e9ecef;">
                            @if (View::hasSection('page_title'))
                                <h2 style="margin: 0; font-size: 1.5rem; font-weight: 600; color: #333;">
                                    @yield('page_title')</h2>
                            @endif
                            @if (View::hasSection('breadcrumb'))
                                <nav style="margin-top: 8px; font-size: 0.9rem; color: #666;">
                                    <a href="{{ route('admin.index') }}"
                                        style="color: #ff0000; text-decoration: none;">Dashboard</a>
                                    / <span style="color: #666;">@yield('breadcrumb')</span>
                                </nav>
                            @endif
                        </div>
                    @endif

                    @yield('content')

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Mahesty Mebel {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Form -->
    <form id="admin-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Bootstrap core JavaScript-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/js/sb-admin-2.min.js"></script>

    <script>
        // Toastr Configuration
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "7000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // Show Toastr notifications for session messages
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                toastr.success('{{ session('success') }}', 'Berhasil!', {
                    timeOut: 5000
                });
            @endif

            @if (session('error'))
                toastr.error('{{ session('error') }}', 'Gagal!', {
                    timeOut: 5000
                });
            @endif

            @if (session('warning'))
                toastr.warning('{{ session('warning') }}', 'Perhatian!', {
                    timeOut: 5000
                });
            @endif

            @if (session('info'))
                toastr.info('{{ session('info') }}', 'Informasi', {
                    timeOut: 5000
                });
            @endif
        });

        // Show critical stock notification on all admin pages
        @if ($sidebarCriticalStockCount > 0)
            console.log('✅ CRITICAL STOCK ALERT ACTIVE');
            console.log('Critical stock count: {{ $sidebarCriticalStockCount }}');
            console.log('Toastr available:', typeof toastr !== 'undefined');

            function showCriticalStockAlert() {
                let criticalProducts = [
                    @foreach ($sidebarCriticalStockProducts as $product)
                        '{{ $product->name }} ({{ $product->stock }} pcs)',
                    @endforeach
                ];

                console.log('✅ Critical products array:', criticalProducts);

                if (criticalProducts.length === 0) {
                    console.warn('⚠️ No critical products in array');
                    return;
                }

                let criticalMessage = '<strong>🚨 STOK KRITIS - PERLU RESTOK SEGERA!</strong><br>';
                criticalMessage += '<ul style="margin-top: 10px; margin-bottom: 5px; padding-left: 20px;">';
                criticalProducts.forEach(function(product) {
                    criticalMessage += '<li style="margin: 5px 0;">' + product + '</li>';
                });
                criticalMessage += '</ul>';
                criticalMessage +=
                    '<a href="{{ route('admin.barang.index') }}" style="color: white; font-weight: bold; text-decoration: underline;">⚡ Restok Sekarang →</a>';

                console.log('✅ Calling toastr.error()');
                toastr.error(criticalMessage, '', {
                    timeOut: 0,
                    extendedTimeOut: 0,
                    closeButton: true,
                    allowHtml: true,
                    positionClass: "toast-top-right"
                });
            }

            // Show immediately
            if (document.readyState === 'loading') {
                console.log('Document still loading, waiting for DOMContentLoaded');
                document.addEventListener('DOMContentLoaded', showCriticalStockAlert);
            } else {
                console.log('Document already loaded, showing alert now');
                showCriticalStockAlert();
            }

            // Backup: Show after 500ms
            setTimeout(function() {
                console.log('✅ Timeout callback - showing alert again');
                showCriticalStockAlert();
            }, 500);
        @else
            console.log('⚠️ No critical stock products (count: {{ $sidebarCriticalStockCount }})');
        @endif
    </script>

    @yield('scripts')

</body>

</html>
