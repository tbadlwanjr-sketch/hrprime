@php
    use Illuminate\Support\Facades\Route;
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu"
    style="background-image: url('{{ asset('assets/img/dswd-bg.png') }}');
           background-size: cover;
           background-repeat: no-repeat;
           color: #fff;">

    {{-- Logo --}}
    <div class="app-brand demo mt-4">
        <a href="{{ url('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo me-1">
                <img src="{{ asset('assets/img/logo-dswd.png') }}" alt="DSWD Logo" height="68" style="margin: 30px 0;" />
            </span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="menu-toggle-icon d-xl-block align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    {{-- Main Menu --}}
    <ul class="menu-inner py-1">
        @foreach ($menuData[0]->menu as $index => $menu)
            @if (!userCanView($menu))
                @continue
            @endif

            {{-- Menu Header --}}
            @if (isset($menu->menuHeader))
                <li class="menu-header mt-7">
                    <span class="menu-header-text">{{ __($menu->menuHeader) }}</span>
                </li>
            @else
                @php
                    $menuId = 'menu-' . $index; // unique id for localStorage
                @endphp

                <li id="{{ $menuId }}" class="menu-item">
                    <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}"
                        class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}"
                        @if(isset($menu->target)) target="{{ $menu->target }}" @endif>
                        @isset($menu->icon)
                            <i class="{{ $menu->icon }}"></i>
                        @endisset
                        <div>{{ $menu->name }}</div>
                        @isset($menu->badge)
                            <div class="badge bg-{{ $menu->badge[0] }} rounded-pill ms-auto">{{ $menu->badge[1] }}</div>
                        @endisset
                    </a>

                    {{-- Submenu --}}
                    @if (isset($menu->submenu))
                        <ul class="menu-sub">
                            @foreach ($menu->submenu as $subIndex => $submenu)
                                @php
                                    $submenuId = $menuId . '-sub-' . $subIndex;
                                @endphp
                                <li id="{{ $submenuId }}" class="menu-item">
                                    <a href="{{ isset($submenu->url) ? url($submenu->url) : 'javascript:void(0);' }}"
                                        class="menu-link"
                                        @if(isset($submenu->target)) target="{{ $submenu->target }}" @endif>
                                        @isset($submenu->icon)
                                            <i class="{{ $submenu->icon }}"></i>
                                        @endisset
                                        <div>{{ $submenu->name }}</div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endif
        @endforeach
    </ul>
</aside>

{{-- ================================
  ✅ SIDEBAR MENU STYLES
================================ --}}
<style>
    .layout-menu .menu-inner .menu-item a,
    .layout-menu .menu-inner .menu-header-text,
    .layout-menu .menu-inner .menu-item i {
        color: #ffffff !important;
    }

    .layout-menu .menu-inner .menu-item a:hover {
        background-color: rgba(255, 255, 255, 0.2);
        color: #ffffff !important;
    }

    .layout-menu .menu-toggle::after {
        content: "▶";
        font-size: 12px;
        margin-left: 5px;
        transition: transform 0.3s ease;
        color: #ffffff !important;
    }

    .menu-item.open > .menu-link.menu-toggle::after {
        transform: rotate(90deg);
    }

    .layout-menu .menu-item.active > .menu-link {
        background-color: rgba(255, 255, 255, 0.25);
        border-left: 4px solid #ffffff;
        font-weight: bold;
    }

    .menu-sub .menu-item.active > .menu-link {
        background-color: rgba(255, 255, 255, 0.3);
        font-weight: bold;
        padding-left: 2rem;
    }

    .menu-sub .menu-item.active > .menu-link div {
        color: #ffffff !important;
    }
</style>

{{-- ================================
  ✅ JS FOR LAST CLICKED MENU
================================ --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const menuLinks = document.querySelectorAll('.layout-menu .menu-link');

    // Load last clicked menu
    const lastMenuId = localStorage.getItem('lastMenu');
    if (lastMenuId) {
        const lastEl = document.getElementById(lastMenuId);
        if (lastEl) {
            lastEl.classList.add('active');
            // If submenu, expand parent
            const parentMenu = lastEl.closest('.menu-sub')?.closest('.menu-item');
            if (parentMenu) parentMenu.classList.add('open', 'active');
        }
    }

    // Add click listener to save clicked menu
    menuLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Remove active/open from all
            document.querySelectorAll('.layout-menu .menu-item').forEach(i => i.classList.remove('active', 'open'));

            // Set active on clicked menu item
            const parentLi = this.closest('.menu-item');
            parentLi.classList.add('active');

            // If submenu, also expand parent
            const parentMenu = parentLi.closest('.menu-sub')?.closest('.menu-item');
            if (parentMenu) parentMenu.classList.add('open', 'active');

            // Save ID to localStorage
            localStorage.setItem('lastMenu', parentLi.id);
        });
    });
});
</script>