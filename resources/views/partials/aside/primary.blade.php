<div class="aside__primary">

    <!-- begin::Aside Top -->
    <div class="aside__top">
        <a class="aside__brand" href="{{ route('shopper.dashboard') }}">
            <img alt="Logo" src="{{ asset('shopper/images/logo-white.svg') }}" width="30">
        </a>
    </div>
    <!-- end:: Aside Top -->

    <!-- begin::Aside Middle -->
    <div class="aside__middle">
        <ul class="aside__nav">
            <li class="aside__nav-item">
                <a class="aside__nav-link active" href="{{ route('shopper.dashboard') }}" data-toggle="tooltip" data-title="{{ __('Dashboard') }}" data-placement="right" data-container="body" data-boundary="window">
                    <i class="flaticon2-protection"></i>
                </a>
            </li>
            <li class="aside__nav-item">
                <a class="aside__nav-link" href="{{ url('/') }}" target="_blank" data-toggle="tooltip" data-title="{{ __('Open site') }}" data-placement="right" data-container="body" data-boundary="window">
                    <i class="flaticon2-browser-1"></i>
                </a>
            </li>
            <li class="aside__nav-item">
                <a class="aside__nav-link" href="#" data-toggle="tooltip" data-title="{{ __('System settings') }}" data-placement="right" data-container="body" data-boundary="window">
                    <i class="flaticon2-gear"></i>
                </a>
            </li>
            <li class="aside__nav-item">
                <a class="aside__nav-link" href="#" data-toggle="tooltip" data-title="{{ __('Access') }}" data-placement="right" data-container="body" data-boundary="window">
                    <i class="flaticon2-lock"></i>
                </a>
            </li>
            <li class="aside__nav-item">
                <a class="aside__nav-link" href="#" data-toggle="tooltip" data-title="{{ __('Logs reports') }}" data-placement="right" data-container="body" data-boundary="window">
                    <i class="flaticon2-list-3"></i>
                </a>
            </li>
        </ul>
    </div>
    <!-- end::Aside Middle -->

    <!-- begin::Aside Bottom -->
    <div class="aside__bottom">
        <ul class="aside__nav">
            <li class="aside__nav-item">
                <a href="#" class="aside__nav-link" id="offcanvas_toolbar_search_toggler_btn">
                    <i class="flaticon2-search-1"></i>
                </a>
            </li>
            <li class="aside__nav-item">
                <a href="#" class="aside__nav-link" id="offcanvas_toolbar_notifications_toggler_btn" data-toggle="tooltip" data-title="Recent notifications" data-placement="right" data-container="body" data-boundary="window">
                    <i class="flaticon2-bell-alarm-symbol"></i>
                    <span class="badge badge--danger">3</span>
                </a>
            </li>
            <li class="aside__nav-item dropdown">
                <a href="#" class="aside__nav-link" data-toggle="dropdown" data-offset="100px, -50px">
                    <i class="flaticon2-hourglass-1 hidden"></i>
                    <img class="hidden-" alt="" src="https://placehold.it/200">
                </a>
                <div class="dropdown-menu dropdown-menu-fit dropdown-menu-left dropdown-menu-anim">
                    <ul class="nav margin-t-10 margin-b-10">
                        <li class="nav__item">
                            <a href="#" class="nav__link">
                                <i class="nav__link-icon flaticon-avatar"></i>
                                <span class="nav__link-text">{{ __('Profile') }}</span>
                            </a>
                        </li>
                        <li class="nav__item">
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav__link">
                                <i class="nav__link-icon flaticon-logout"></i>
                                <span class="nav__link-text">{{ __('Logout') }}</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>

</div>
