<!-- begin::Offcanvas Toolbar Search -->
<div id="offcanvas_toolbar_search" class="offcanvas-panel">
    <div class="offcanvas-panel__head">
        <h3 class="offcanvas-panel__title">
            {{ __('Search') }}
        </h3>
        <a href="#" class="offcanvas-panel__close" id="offcanvas_toolbar_search_close"><i class="flaticon2-delete"></i></a>
    </div>
    <div class="offcanvas-panel__body">

        <!-- begin:: Quick Search -->
        <div class="quick-search quick-search--offcanvas" id="quick_search_offcanvas">
            <form method="get" action="#" class="quick-search__form">
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="flaticon2-search-1"></i></span></div>
                    <input type="text" class="form-control quick-search__input" placeholder="{{ __('Type here...') }}" name="query">
                    <div class="input-group-append"><span class="input-group-text"><i class="la la-close quick-search__close"></i></span></div>
                </div>
            </form>
            <div class="quick-search__wrapper">
                <div class="quick-search__result">

                </div>
            </div>
        </div>

        <!-- end:: Quick Search -->
    </div>
</div>
<!-- end::Offcanvas Toolbar Search -->
