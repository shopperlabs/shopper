<!-- begin::Aside Secondary -->
<div class="aside__secondary">
    <div class="aside__secondary-top">
        <h3 class="aside__secondary-title"></h3>
    </div>

    <!-- To hide the secondary menu remove "aside--secondary-enabled" class from the body tag and remove below "aside__secondary-bottom" block-->
    <div class="aside__secondary-bottom">

        <!-- begin:: Aside Menu -->
        <div id="aside_menu" class="aside-menu  aziko" data-ktmenu-vertical="1" data-ktmenu-scroll="1">
            <ul class="menu__nav">
                <li class="menu__section menu__section--first">
                    <h4 class="menu__section-text">Main</h4>
                    <i class="menu__section-icon flaticon-more-v2"></i>
                </li>
                <li class="menu__item menu__item--active" aria-haspopup="true"><a href="{{ route('shopper.dashboard') }}" class="menu__link "><span class="menu__link-text">{{ __('Dashboard') }}</span></a></li>
                <li class="menu__section">
                    <h4 class="menu__section-text">Components</h4>
                    <i class="menu__section-icon flaticon-more-v2"></i>
                </li>
                <li class="menu__item  menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="menu__link menu__toggle"><span class="menu__link-text">Charts</span><i class="menu__ver-arrow la la-angle-right"></i></a>
                    <div class="menu__submenu"><span class="menu__arrow"></span>
                        <ul class="menu__subnav">
                            <li class="menu__item menu__item--parent" aria-haspopup="true"><span class="menu__link"><span class="menu__link-text">Charts</span></span></li>
                            <li class="menu__item" aria-haspopup="true"><a href="components/charts/flotcharts.html" class="menu__link "><i class="menu__link-bullet menu__link-bullet--dot"><span></span></i><span class="menu__link-text">Flot Charts</span></a></li>
                            <li class="menu__item" aria-haspopup="true"><a href="components/charts/google-charts.html" class="menu__link "><i class="menu__link-bullet menu__link-bullet--dot"><span></span></i><span class="menu__link-text">Google Charts</span></a></li>
                            <li class="menu__item" aria-haspopup="true"><a href="components/charts/morris-charts.html" class="menu__link "><i class="menu__link-bullet menu__link-bullet--dot"><span></span></i><span class="menu__link-text">Morris Charts</span></a></li>
                            <li class="menu__item" aria-haspopup="true"><a href="components/charts/chart-js.html" class="menu__link "><i class="menu__link-bullet menu__link-bullet--dot"><span></span></i><span class="menu__link-text">Chart JS</span></a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>

        <!-- end:: Aside Menu -->
    </div>
</div>

<!-- end::Aside Secondary -->
