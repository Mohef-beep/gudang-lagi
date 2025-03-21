<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/dashboard" class="brand-link">
        <img src="{{ asset('AdminLTE/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Sistem Gudang</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">


        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="/dashboard" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <!-- Master Data Label -->
                <li class="nav-header">
                                        <span>Masterdata</span>
                </li>

                <!-- Barang Menu -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-box"></i>
                        <p>
                            Barang
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/masterdata/nama-barang" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Nama Barang</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/masterdata/jenis-barang" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Jenis Barang</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/masterdata/satuan-barang" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Satuan Barang</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Cabang Menu -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-building"></i>
                        <p>
                            Cabang
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/masterdata/nama-cabang" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Nama Cabang</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/masterdata/alamat-cabang" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Alamat Cabang</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/masterdata/penanggung-jawab-cabang" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Penanggung Jawab</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Transaksi Menu Items -->
                <li class="nav-header">
                                        <span>Transaksi</span>
                </li>
                <li class="nav-item">
                    <a href="/transaksi/barang-masuk" class="nav-link">
                        <i class="nav-icon fas fa-sign-in-alt"></i>
                        <p>Barang Masuk</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/transaksi/barang-keluar" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Barang Keluar</p>
                    </a>
                </li>

                <!-- Laporan Menu Items -->
                <li class="nav-header">
                    <span>Laporan</span>
                </li>
                <li class="nav-item">
                    <a href="/laporan/laporan-stock" class="nav-link">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>Laporan Stock</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/laporan/laporan-barang-masuk" class="nav-link">
                        <i class="nav-icon fas fa-file-import"></i>
                        <p>Laporan Barang Masuk</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/laporan/laporan-barang-keluar" class="nav-link">
                        <i class="nav-icon fas fa-file-export"></i>
                        <p>Laporan Barang Keluar</p>
                    </a>
                </li>

                {{-- <!-- Logout -->
                <li class="nav-item">
                    <a href="/logout" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li> --}}
            </ul>
        </nav>
    </div>
</aside>