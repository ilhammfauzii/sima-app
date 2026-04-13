<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex flex-column align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="text-white" style="font-size: 1.5rem; font-weight: bold;">
            SIMA
        </div>
        <div class="text-white" style="font-size: 0.5rem;">
            (SISTEM INVENTARIS MANAJEMEN ASET)
        </div>
    </a>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">Dashboard</div>
    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-chart-pie"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">Inventaris</div>
    
    @can('manage-barang')
    <li class="nav-item {{ request()->routeIs('master_barang.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('master_barang.menu') }}">
            <i class="fas fa-fw fa-box"></i>
            <span>Master Barang</span>
        </a>
    </li>
    @endcan

    <li class="nav-item {{ request()->routeIs('barang_engineer.*') || request()->routeIs('pengeluaran_barang_engineer.*') || request()->routeIs('pengadaan_barang_engineer.*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseEngineer" aria-controls="collapseEngineer">
            <i class="fas fa-fw fa-tools"></i>
            <span>Alat Engineer</span>
        </a>

        <div id="collapseEngineer" class="collapse" aria-labelledby="headingEngineer" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Engineer</h6>
                @can('manage-barang')
                <a class="collapse-item {{ request()->routeIs('pengadaan_barang_engineer.*') ? 'active' : '' }}" href="{{ route('pengadaan_barang_engineer.menu') }}">
                    <i class="fas fa-cart-plus fa-fw me-2"></i>Pengadaan Alat
                </a>
                @endcan
                <a class="collapse-item {{ request()->routeIs('barang_engineer.index') ? 'active' : '' }}" href="{{ route('barang_engineer.index') }}">
                    <i class="fas fa-tools fa-fw me-2"></i>Stok Alat
                </a>
                <a class="collapse-item {{ request()->routeIs('pengeluaran_barang_engineer.*') ? 'active' : '' }}" href="{{ route('pengeluaran_barang_engineer.menu') }}">
                    <i class="fas fa-dolly fa-fw me-2"></i>Pengeluaran Alat
                </a>
            </div>
        </div>
    </li>

    <li class="nav-item {{ request()->routeIs('barang_gudang.*') || request()->routeIs('pengeluaran_barang_gudang.*') || request()->routeIs('pengadaan_barang_gudang.*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseGudang" aria-controls="collapseGudang">
            <i class="fas fa-fw fa-warehouse"></i>
            <span>Material Instalasi</span>
        </a>

        <div id="collapseGudang" class="collapse" aria-labelledby="headingGudang" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Gudang</h6>
                @can('manage-barang')
                <a class="collapse-item {{ request()->routeIs('pengadaan_barang_gudang.*') ? 'active' : '' }}" href="{{ route('pengadaan_barang_gudang.menu') }}">
                    <i class="fas fa-cart-plus fa-fw me-2"></i>Pengadaan Material
                </a>
                @endcan
                <a class="collapse-item {{ request()->routeIs('barang_gudang.index') ? 'active' : '' }}" href="{{ route('barang_gudang.index') }}">
                    <i class="fas fa-boxes fa-fw me-2"></i>Stok Material
                </a>
                <a class="collapse-item {{ request()->routeIs('pengeluaran_barang_gudang.*') ? 'active' : '' }}" href="{{ route('pengeluaran_barang_gudang.menu') }}">
                    <i class="fas fa-dolly fa-fw me-2"></i>Pengeluaran Material
                </a>
            </div>
        </div>
    </li>

    <li class="nav-item {{ request()->routeIs('barang_safety.*') || request()->routeIs('pengeluaran_barang_safety.*') || request()->routeIs('pengadaan_barang_safety.*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSafety" aria-controls="collapseSafety">
            <i class="fas fa-hard-hat"></i>
            <span>Alat Safety</span>
        </a>

        <div id="collapseSafety" class="collapse" aria-labelledby="headingSafety" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Safety</h6>
                @can('manage-barang')
                <a class="collapse-item {{ request()->routeIs('pengadaan_barang_safety.*') ? 'active' : '' }}" href="{{ route('pengadaan_barang_safety.menu') }}">
                    <i class="fas fa-cart-plus fa-fw me-2"></i>Pengadaan Alat
                </a>
                @endcan
                <a class="collapse-item {{ request()->routeIs('barang_safety.index') ? 'active' : '' }}" href="{{ route('barang_safety.index') }}">
                    <i class="fas fa-hard-hat fa-fw me-2"></i>Stok Alat
                </a>
                <a class="collapse-item {{ request()->routeIs('pengeluaran_barang_safety.*') ? 'active' : '' }}" href="{{ route('pengeluaran_barang_safety.menu') }}">
                    <i class="fas fa-dolly fa-fw me-2"></i>Pengeluaran Alat
                </a>
            </div>
        </div>
    </li>

    @can('manage-barang')
    <li class="nav-item {{ request()->is('surat/pengeluaran*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCetakSurat" aria-expanded="true" aria-controls="collapseCetakSurat">
            <i class="fas fa-print"></i>
            <span>Cetak Surat</span>
        </a>

        <div id="collapseCetakSurat" class="collapse" aria-labelledby="headingCetakSurat" data-parent="#accordionSidebar">

            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header font-weight-bold text-dark">Pengeluaran</h6>

                <a class="collapse-item {{ request()->is('surat/pengeluaran/engineer*') ? 'active' : '' }}" href="{{ route('surat.pengeluaran.engineer.index') }}">
                    <i class="fas fa-tools fa-fw mr-2"></i>Alat Engineer
                </a>

                <a class="collapse-item {{ request()->is('surat/pengeluaran/gudang*') ? 'active' : '' }}" href="{{ route('surat.pengeluaran.gudang.index') }}">
                    <i class="fas fa-warehouse fa-fw mr-2"></i>Material Instalasi
                </a>

                <a class="collapse-item {{ request()->is('surat/pengeluaran/safety*') ? 'active' : '' }}" href="{{ route('surat.pengeluaran.safety.index') }}">
                    <i class="fas fa-hard-hat fa-fw mr-2"></i>Alat Safety
                </a>

                <div class="dropdown-divider my-2"></div>

                <a class="collapse-item {{ request()->is('pengeluaran/gabungan*') ? 'active' : '' }}" href="{{ route('pengeluaran.gabungan.index') }}">
                    <i class="fas fa-layer-group fa-fw mr-2"></i>Gabungan
                </a>
            </div>
        </div>
    </li>
    @endcan

    <hr class="sidebar-divider">
    <div class="sidebar-heading">Administrasi</div>

    <li class="nav-item {{ request()->routeIs('master.*') || request()->routeIs('customers.*') || request()->routeIs('sla.*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAdministrasi" 
        aria-controls="collapseAdministrasi">
            <i class="fas fa-fw fa-cogs"></i>
            <span>Administrasi</span>
        </a>

        <div id="collapseAdministrasi" class="collapse" aria-labelledby="headingAdministrasi" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Administrasi</h6>
                
                @can('manage-master')
                <a class="collapse-item {{ request()->routeIs('master.*') ? 'active' : '' }}" href="{{ route('master.index') }}">
                    <i class="fas fa-database fa-fw me-2"></i>Master Data
                </a>
                @endcan
                
                @can('manage-customer')
                <a class="collapse-item {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.menu') }}">
                    <i class="fas fa-users fa-fw me-2"></i>Customer
                </a>
                @endcan
                
                <a class="collapse-item {{ request()->routeIs('sla.*') ? 'active' : '' }}" href="{{ route('sla.menu') }}">
                    <i class="fas fa-file-contract fa-fw me-2"></i>SLA
                </a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">Arsip</div>

    <li class="nav-item {{ request()->routeIs('arsip.*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseArsip" aria-controls="collapseArsip">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>Arsip Dokumen</span>
        </a>
        
        <div id="collapseArsip" class="collapse {{ request()->routeIs('arsip.*') ? 'active' : '' }}" aria-labelledby="headingArsip" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Arsip Dokumen</h6>
                
                <a class="collapse-item {{ request()->routeIs('arsip.upload-menu') ? 'active' : '' }}" href="{{ route('arsip.upload-menu') }}">
                    <i class="fas fa-upload fa-fw me-2"></i>Upload Dokumen
                </a>
                
                <a class="collapse-item {{ request()->routeIs('arsip.index') ? 'active' : '' }}" href="{{ route('arsip.index') }}">
                    <i class="fas fa-eye fa-fw me-2"></i>Lihat Arsip Dokumen
                </a>
                
                <a class="collapse-item {{ request()->routeIs('arsip.dekripsi-form') ? 'active' : '' }}" href="{{ route('arsip.dekripsi-form') }}">
                    <i class="fas fa-unlock fa-fw me-2"></i>Dekripsi Dokumen
                </a>
            </div>
        </div>
    </li>

    @can('view-user-management')
    <hr class="sidebar-divider">
    <div class="sidebar-heading">Admin</div>
    <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.users.menu') }}">
            <i class="fas fa-fw fa-users-cog"></i>
            <span>Manajemen Pengguna</span>
        </a>
    </li>
    @endcan

    {{-- <hr class="sidebar-divider">
    <div class="sidebar-heading">PANDUAN</div>
    <li class="nav-item {{ request()->routeIs('user_manual') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('user_manual') }}">
            <i class="fas fa-book"></i>
            <span>Panduan Pengguna</span>
        </a>
    </li> --}}
</ul>