<aside id="layout-menu" class="layout-menu-horizontal menu-horizontal menu bg-menu-theme flex-grow-0">
    <div class="container-xxl d-flex h-100">
        <ul class="menu-inner">
            <!-- Dashboards -->
            <li class="menu-item {{ request()->is('beranda') ? 'active' : '' }}">
                <a href="{{ URL::to('beranda') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-home"></i>
                    <div data-i18n="Beranda">Beranda</div>
                </a>
            </li>

            {{-- Start POSMARU --}}
             <li class="menu-item {{ request()->is('daftar-kegiatan') ? 'active' : '' }}">
                <a href="{{ URL::to('daftar-kegiatan') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-calendar-check"></i>
                    <div data-i18n="Kegiatan">Kegiatan</div>
                </a>
            </li>
            {{-- End POSMARU --}}

            {{-- Master --}}
            <li class="menu-item {{ request()->is('master*') ? 'active' : '' }}">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-database"></i>
                    <div data-i18n="Master">Master</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->is('master/pengguna') ? 'active' : '' }}">
                        <a href="{{ URL::to('master/pengguna') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-menu-2"></i>
                            <div data-i18n="Pengguna">Pengguna</div>
                        </a>
                    </li>
                </ul>
            </li>
            {{-- End Master --}}
        </ul>
    </div>
</aside>