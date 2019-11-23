@extends('shopper::layouts.master')
@section('title', __('Dashboard'))

@section('content')

    <div class="subheader grid__item" id="subheader">
        <div class="container  container--fluid ">
            <div class="subheader__main">
                <h3 class="subheader__title">
                    Edit User
                </h3>
                <span class="subheader__separator subheader__separator--v"></span>
                <div class="subheader__toolbar" id="subheader_search">
                    <span class="subheader__desc" id="subheader_total">Aaron Sterling </span>
                </div>
            </div>
            <div class="subheader__toolbar">
                <a href="#" class="btn btn-default btn-bold">Back </a>
                <div class="btn-group">
                    <button type="button" class="btn btn-brand btn-bold">Save Changes </button>
                    <button type="button" class="btn btn-brand btn-bold dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <ul class="nav">
                            <li class="nav__item">
                                <a href="#" class="nav__link">
                                    <i class="nav__link-icon flaticon2-writing"></i>
                                    <span class="nav__link-text">Save &amp; continue</span>
                                </a>
                            </li>
                            <li class="nav__item">
                                <a href="#" class="nav__link">
                                    <i class="nav__link-icon flaticon2-medical-records"></i>
                                    <span class="nav__link-text">Save &amp; add new</span>
                                </a>
                            </li>
                            <li class="nav__item">
                                <a href="#" class="nav__link">
                                    <i class="nav__link-icon flaticon2-hourglass-1"></i>
                                    <span class="nav__link-text">Save &amp; exit</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="alert alert-light alert-elevate fade show" role="alert">
                <div class="alert-icon"><i class="flaticon-warning font-brand"></i></div>
                <div class="alert-text">
                    Keen extends <code>Bootstrap Collapse</code> component with a variety of options to provide uniquely looking Collapse component that matches the Keen's design standards.
                    <br>
                    For more info please visit the plugin's <a class="link font-bold" href="https://getbootstrap.com/docs/4.3/components/collapse/#accordion-example" target="_blank">Documentation</a>.
                </div>
            </div>
        </div>
    </div>

    <!--begin::Row-->
    <div class="row">
        <div class="col-xl-6">

            <!--begin::Portlet-->
            <div class="portlet">
                <div class="portlet__head">
                    <div class="portlet__head-label">
                        <h3 class="portlet__head-title">Basic Examples</h3>
                    </div>
                </div>
                <div class="portlet__body">
                    <div class="section">
                        <h3 class="section__title">Link or Buttons</h3>
                        <div class="section__info">
                            Click the buttons below to show and hide another element via class changes:
                            <ul>
                                <li><code>.collapse</code> hides content</li>
                                <li><code>.collapsing</code> is applied during transitions</li>
                                <li><code>.collapse.show</code> shows content</li>
                            </ul>
                            You can use a link with the href attribute, or a button with the <code>data-target</code> attribute. In both cases, the <code>data-toggle="collapse"</code> is required.
                        </div>
                        <div class="section__content section__content--border">
                            <p>
                                <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                    Link with href
                                </a>
                                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                    Button with data-target
                                </button>
                            </p>
                            <div class="collapse" id="collapseExample">
                                <div class="card card-body">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="separator separator--space-lg separator--border-dashed"></div>
                </div>
            </div>

            <!--end::Portlet-->
        </div>
        <div class="col-xl-6">

            <!--begin::Portlet-->
            <div class="portlet">
                <div class="portlet__head">
                    <div class="portlet__head-label">
                        <h3 class="portlet__head-title">Accordions</h3>
                    </div>
                </div>
                <div class="portlet__body">
                    <div class="accordion" id="accordionExample">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <div class="card-title" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Collapsible Group Item #1
                                </div>
                            </div>
                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                <div class="card-body">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Collapsible Group Item #2
                                </div>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                <div class="card-body">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingThree">
                                <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Collapsible Group Item #3
                                </div>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                <div class="card-body">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--end::Portlet-->
        </div>
    </div>

@endsection
