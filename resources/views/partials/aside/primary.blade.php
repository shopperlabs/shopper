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
                <a class="aside__nav-link active" href="?page=index" data-toggle="tooltip" data-title="Dashboard" data-placement="right" data-container="body" data-boundary="window">
                    <i class="flaticon2-protection"></i>
                </a>
            </li>
            <li class="aside__nav-item">
                <a class="aside__nav-link" href="?page=index" data-toggle="tooltip" data-title="Latest orders" data-placement="right" data-container="body" data-boundary="window">
                    <i class="flaticon2-hourglass-1"></i>
                </a>
            </li>
            <li class="aside__nav-item">
                <a class="aside__nav-link" href="?page=index" data-toggle="tooltip" data-title="User feedbacks" data-placement="right" data-container="body" data-boundary="window">
                    <i class="flaticon2-schedule"></i>
                </a>
            </li>
            <li class="aside__nav-item">
                <a class="aside__nav-link" href="?page=index" data-toggle="tooltip" data-title="System settings" data-placement="right" data-container="body" data-boundary="window">
                    <i class="flaticon2-shopping-cart-1"></i>
                </a>
            </li>
            <li class="aside__nav-item">
                <a class="aside__nav-link" href="?page=index" data-toggle="tooltip" data-title="Finance reports" data-placement="right" data-container="body" data-boundary="window">
                    <i class="flaticon2-list-3"></i>
                </a>
            </li>
            <li class="aside__nav-item">
                <a class="aside__nav-link" href="?page=index" data-toggle="tooltip" data-title="Membership reports" data-placement="right" data-container="body" data-boundary="window">
                    <i class="flaticon2-drop"></i>
                </a>
            </li>
            <li class="aside__nav-item">
                <a class="aside__nav-link" href="?page=index" data-toggle="tooltip" data-title="Notifications" data-placement="right" data-container="body" data-boundary="window">
                    <i class="flaticon2-delivery-truck"></i>
                </a>
            </li>
        </ul>
    </div>
    <!-- end::Aside Middle -->

    <!-- begin::Aside Bottom -->
    <div class="aside__bottom">
        <ul class="aside__nav">
            <li class="aside__nav-item">
                <a href="#" class="aside__nav-link" id="kt_offcanvas_toolbar_search_toggler_btn">
                    <i class="flaticon2-search-1"></i>
                </a>
            </li>
            <li class="aside__nav-item">
                <a href="#" class="aside__nav-link" id="kt_offcanvas_toolbar_notifications_toggler_btn" data-toggle="tooltip" data-title="Recent notifications" data-placement="right" data-container="body" data-boundary="window">
                    <i class="flaticon2-bell-alarm-symbol"></i>
                    <span class="badge badge--danger">3</span>
                </a>
            </li>
            <li class="aside__nav-item">
                <a href="#" class="aside__nav-link" id="kt_quick_panel_toggler_btn">
                    <i class="flaticon2-grids"></i>
                </a>
            </li>
            <li class="aside__nav-item dropdown">
                <a href="#" class="aside__nav-link" data-toggle="dropdown" data-offset="100px, -50px">
                    <img class="aside__nav-langs" src="assets/media/flags/020-flag.svg" alt="">
                </a>
                <div class="dropdown-menu dropdown-menu-fit dropdown-menu-left dropdown-menu-anim">
                    <ul class="nav margin-t-10 margin-b-10">
                        <li class="nav__item nav__item--active">
                            <a href="#" class="nav__link">
                                <span class="nav__link-icon"><img src="assets/media/flags/020-flag.svg" alt="" /></span>
                                <span class="nav__link-text">English</span>
                            </a>
                        </li>
                        <li class="nav__item">
                            <a href="#" class="nav__link">
                                <span class="nav__link-icon"><img src="assets/media/flags/016-spain.svg" alt="" /></span>
                                <span class="nav__link-text">Spanish</span>
                            </a>
                        </li>
                        <li class="nav__item">
                            <a href="#" class="nav__link">
                                <span class="nav__link-icon"><img src="assets/media/flags/017-germany.svg" alt="" /></span>
                                <span class="nav__link-text">German</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="aside__nav-item">
                <a href="#" class="aside__nav-link" id="kt_offcanvas_toolbar_profile_toggler_btn">
                    <i class="flaticon2-hourglass-1 hidden"></i>
                    <img class="hidden-" alt="" src="assets/media/users/300_21.jpg">

                    <!--use below badge element instead the user avatar to display username's first letter(remove hidden class to display it) -->
                    <span class="aside__nav-username bg-brand hidden">S</span>
                </a>
            </li>
        </ul>
    </div>

</div>
