<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('template/css/sb-admin-2.min.css') }}" rel="stylesheet">
</head>
<body>
        <!-- Page Wrapper -->
        <div id="wrapper">

            <!-- Sidebar -->
            <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    
                <!-- Sidebar - Brand -->
                <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                    <div class="sidebar-brand-icon rotate-n-15">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="sidebar-brand-text mx-3">Kasir<sup>Pro</sup></div>
                </a>

    
                <!-- Divider -->
                <hr class="sidebar-divider my-0">
    
               <!-- Nav Item - Dashboard -->
               <li class="nav-item">
                    @if(auth()->user()->role === 'admin')
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    @elseif(auth()->user()->role === 'petugas')
                        <a class="nav-link" href="{{ route('petugas.dashboard') }}">
                    @endif
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>


    
                <!-- Divider -->
                <hr class="sidebar-divider">
    
                <!-- Heading -->
                <div class="sidebar-heading">
                    Master
                </div>
    
                <!-- Nav Item - Produk -->
                <li class="nav-item">
                    @if(auth()->user()->role == 'admin')
                        <a class="nav-link" href="{{ route('admin.products.index') }}">
                            <i class="fas fa-fw fa-box"></i>
                            <span>Produk</span>
                        </a>
                    @elseif(auth()->user()->role == 'petugas')
                        <a class="nav-link" href="{{ route('petugas.products.index') }}">
                            <i class="fas fa-fw fa-box"></i>
                            <span>Produk</span>
                        </a>
                    @endif
                </li>

               <!-- Nav Item - Pembelian -->
               <li class="nav-item">
                @if(auth()->user()->role == 'admin')
                <a class="nav-link" href="{{route('admin.sales.index') }}">
                        <i class="fas fa-fw fa-shopping-cart"></i>
                        <span>Pembelian</span>
                    </a>
                @elseif(auth()->user()->role == 'petugas')
                    <a class="nav-link" href="{{route('petugas.sales.index') }}">
                        <i class="fas fa-fw fa-shopping-cart"></i>
                        <span>Pembelian</span>
                    </a>
                @endif
             </li>

                <!-- Nav Item - Pengguna -->
                <li class="nav-item">
                    @if (auth()->user()->role == 'admin')
                        <a class="nav-link" href="{{ route('admin.users.index') }}">
                            <i class="fas fa-fw fa-users"></i>
                            <span>Pengguna</span>
                        </a>
                    @endif
                </li>

  
    
                <!-- Divider -->
                <hr class="sidebar-divider d-none d-md-block">
    
                <!-- Sidebar Toggler (Sidebar) -->
                <div class="text-center d-none d-md-inline">
                    <button class="rounded-circle border-0" id="sidebarToggle"></button>
                </div>
        
            </ul>
            <!-- End of Sidebar -->
    
            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">
    
                <!-- Main Content -->
                <div id="content">
    
                    <!-- Topbar -->
                    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    
                        <!-- Sidebar Toggle (Topbar) -->
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
    
                        <!-- Topbar Search -->
                        <form
                            class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                            <div class="input-group">
                                <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                    aria-label="Search" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fas fa-search fa-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
    
                        <!-- Topbar Navbar -->
                        <ul class="navbar-nav ml-auto">
    
                            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                            <li class="nav-item dropdown no-arrow d-sm-none">
                                <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-search fa-fw"></i>
                                </a>
                                <!-- Dropdown - Messages -->
                                <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                    aria-labelledby="searchDropdown">
                                    <form class="form-inline mr-auto w-100 navbar-search">
                                        <div class="input-group">
                                            <input type="text" class="form-control bg-light border-0 small"
                                                placeholder="Search for..." aria-label="Search"
                                                aria-describedby="basic-addon2">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button">
                                                    <i class="fas fa-search fa-sm"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </li>
    
                            <div class="topbar-divider d-none d-sm-block"></div>
    
                            <!-- Nav Item - User Information -->
                            <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" 
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img class="img-profile rounded-circle" src="{{ asset('template/img/undraw_profile.svg') }}">
                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                        {{ ucfirst(Auth::user()->name) }}
                                    </span>
                                </a>
                                <!-- Dropdown - User Information -->
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                    aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                        {{ ucfirst(string: Auth::user()->role) }}
                                    </a>

                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Logout
                                    </a>
                                </div>
                            </li>
    
                        </ul>
    
                    </nav>
                    <!-- End of Topbar -->
    
                    <!-- Begin Page Content -->
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                    <!-- /.container-fluid -->
    
                </div>
                <!-- End of Main Content -->
    

                <!-- End of Footer -->
                <!-- Logout Modal -->
                <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Yakin ingin keluar?</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Tutup">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">Pilih "Keluar" di bawah ini jika Anda yakin ingin mengakhiri sesi saat ini.</div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                                <a class="btn btn-primary" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Keluar
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Content Wrapper -->
    
        </div>
    <!-- Your existing body content -->


    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>
    

    <!-- Page level plugins -->
    <script src="{{ asset('template/vendor/chart.js/Chart.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('template/js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('template/js/demo/chart-pie-demo.js') }}"></script>
    @stack('scripts')
</body>
</html>