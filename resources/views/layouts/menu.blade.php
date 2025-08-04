<aside id="layout-menu" class="layout-menu-horizontal menu-horizontal menu bg-menu-theme flex-grow-0">
    <div class="container-xxl d-flex h-100">
        <ul class="menu-inner">
            <!-- Dashboards -->
            <li class="menu-item active">
                <a href="index.html" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-home"></i>
                    <div data-i18n="Beranda">Beranda</div>
                </a>
            </li>

            {{-- Start POSMARU --}}
            <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-calendar-bolt"></i>
                    <div data-i18n="POSMARU">POSMARU</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="layouts-without-menu.html" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-menu-2"></i>
                            <div data-i18n="Kegiatan">Kegiatan</div>
                        </a>
                    </li>
                </ul>
            </li>
            {{-- End POSMARU --}}

            {{-- Master --}}
            <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-database"></i>
                    <div data-i18n="Master">Master</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="layouts-without-menu.html" class="menu-link">
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