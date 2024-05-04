<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <title>
        @yield('title')
    </title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="Preview page of Metronic Admin Theme #4 for statistics, charts, recent events and reports"
          name="description"/>
    <meta content="" name="author"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Cairo:300,400,600,700" rel="stylesheet"
          type="text/css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="{{admin_assets('src/leaflet-src.js')}}"></script>
    <link rel="stylesheet" href="{{admin_assets('src/leaflet.css')}}" />

    <script src="{{admin_assets('src/Leaflet.draw.js')}}"></script>
    <script src="{{admin_assets('src/Leaflet.Draw.Event.js')}}"></script>
    <link rel="stylesheet" href="{{admin_assets('src/leaflet.draw.css')}}" />

    <script src="{{admin_assets('src/Toolbar.js')}}"></script>
    <script src="{{admin_assets('src/Tooltip.js')}}"></script>

    <script src="{{admin_assets('src/ext/GeometryUtil.js')}}"></script>
    <script src="{{admin_assets('src/ext/LatLngUtil.js')}}"></script>
    <script src="{{admin_assets('src/ext/LineUtil.Intersect.js')}}"></script>
    <script src="{{admin_assets('src/ext/Polygon.Intersect.js')}}"></script>
    <script src="{{admin_assets('src/ext/Polyline.Intersect.js')}}"></script>
    <script src="{{admin_assets('src/ext/TouchEvents.js')}}"></script>

    <script src="{{admin_assets('src/draw/DrawToolbar.js')}}"></script>
    <script src="{{admin_assets('src/draw/handler/Draw.Feature.js')}}"></script>
    <script src="{{admin_assets('src/draw/handler/Draw.SimpleShape.js')}}"></script>
    <script src="{{admin_assets('src/draw/handler/Draw.Polyline.js')}}"></script>
    <script src="{{admin_assets('src/draw/handler/Draw.Marker.js')}}"></script>
    <script src="{{admin_assets('src/draw/handler/Draw.Circle.js')}}"></script>
    <script src="{{admin_assets('src/draw/handler/Draw.CircleMarker.js')}}"></script>
    <script src="{{admin_assets('src/draw/handler/Draw.Polygon.js')}}"></script>
    <script src="{{admin_assets('src/draw/handler/Draw.Rectangle.js')}}"></script>


    <script src="{{admin_assets('src/edit/EditToolbar.js')}}"></script>
    <script src="{{admin_assets('src/edit/handler/EditToolbar.Edit.js')}}"></script>
    <script src="{{admin_assets('src/edit/handler/EditToolbar.Delete.js')}}"></script>

    <script src="{{admin_assets('src/Control.Draw.js')}}"></script>

    <script src="{{admin_assets('src/edit/handler/Edit.Poly.js')}}"></script>
    <script src="{{admin_assets('src/edit/handler/Edit.SimpleShape.js')}}"></script>
    <script src="{{admin_assets('src/edit/handler/Edit.Rectangle.js')}}"></script>
    <script src="{{admin_assets('src/edit/handler/Edit.Marker.js')}}"></script>
    <script src="{{admin_assets('src/edit/handler/Edit.CircleMarker.js')}}"></script>
    <script src="{{admin_assets('src/edit/handler/Edit.Circle.js')}}"></script>
    @if(app()->getLocale() == 'ar')


        <link href="{{admin_assets('/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet"
              type="text/css"/>
        <link href="{{admin_assets('/global/plugins/simple-line-icons/simple-line-icons.min.css')}}" rel="stylesheet"
              type="text/css"/>
        <link href="{{admin_assets('/global/plugins/bootstrap/css/bootstrap-rtl.min.css')}}" rel="stylesheet"
              type="text/css"/>
        <link href="{{admin_assets('/global/plugins/bootstrap-switch/css/bootstrap-switch-rtl.min.css')}}" rel="stylesheet"
              type="text/css"/>

        @yield('css_file_upload')

        <link href="{{admin_assets('global/plugins/datatables/datatables.min.css')}}" rel="stylesheet"
              type="text/css"/>
        <link href="{{admin_assets('global/plugins/bootstrap-editable/bootstrap-editable/css/bootstrap-editable-rtl.css')}}" rel="stylesheet"
              type="text/css"/>

        <link href="{{admin_assets('/global/css/components-rtl.min.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{admin_assets('/global/css/plugins-rtl.min.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{admin_assets('/layouts/layout4/css/layout-rtl.min.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{admin_assets('/layouts/layout4/css/themes/default-rtl.min.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{admin_assets('/layouts/layout4/css/custom-rtl.min.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{admin_assets('global/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{admin_assets('global/plugins/select2/css/select2-bootstrap.min.css')}}" rel="stylesheet"
              type="text/css"/>
        <link href="{{admin_assets('global/plugins/bootstrap-sweetalert/sweetalert.css')}}" rel="stylesheet"
              type="text/css"/>
        <link href="{{admin_assets('layouts/layout4/css/customize-style.css')}}" rel="stylesheet"
              type="text/css"/>

        <link href="{{admin_assets('global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}" rel="stylesheet"
              type="text/css"/>

        <style type="text/css">
            .page-breadcrumb{
                direction: rtl;
            }
            .widget-row{
                margin-top: 45px;
            }
            .btn-group{
                float: right;
            }
            body{
                direction: rtl;
            }


            .btn-group .btn+.btn, .btn-group .btn+.btn-group, .btn-group .btn-group+.btn, .btn-group .btn-group+.btn-group {
                margin-right: 4px;
            }

            .mt-checkbox>span:after {

                border-width: 0 2px 2px 0;
                transform: rotate(45deg);
            }



            .fa {
                transform: rotate(180edg);
            }
            
            .select2-results__option[aria-selected]{cursor:pointer; text-align:right;}
            
            .select2-container--bootstrap .select2-selection--multiple .select2-search--inline .select2-search__field {background:transparent;padding:0 12px;height:32px;line-height:1.42857;margin-top:0;min-width:5em;text-align:right;}
            .select2-container--bootstrap .select2-selection--multiple .select2-selection__choice{color:#555;background:#fff;border:1px solid #ccc;border-radius:4px;cursor:default;float:right;margin:5px 0 0 6px;padding:0 6px}


        </style>

        @yield('css')





    @else



        <link href="{{admin_assets('/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet"
              type="text/css"/>
        <link href="{{admin_assets('/global/plugins/simple-line-icons/simple-line-icons.min.css')}}" rel="stylesheet"
              type="text/css"/>
        <link href="{{admin_assets('/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet"
              type="text/css"/>
        <link href="{{admin_assets('/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}" rel="stylesheet"
              type="text/css"/>

        @yield('css_file_upload')


        <link href="{{admin_assets('/global/css/components.min.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{admin_assets('/global/css/plugins.min.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{admin_assets('/layouts/layout4/css/layout.min.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{admin_assets('/layouts/layout4/css/themes/default.min.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{admin_assets('/layouts/layout4/css/custom.min.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{admin_assets('global/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{admin_assets('global/plugins/select2/css/select2-bootstrap.min.css')}}" rel="stylesheet"
              type="text/css"/>
        <link href="{{admin_assets('global/plugins/bootstrap-sweetalert/sweetalert.css')}}" rel="stylesheet"
              type="text/css"/>
        <link href="{{admin_assets('layouts/layout4/css/customize-style.css')}}" rel="stylesheet"
              type="text/css"/>
        <link href="{{admin_assets('global/plugins/datatables/datatables.min.css')}}" rel="stylesheet"
              type="text/css"/>
        <link href="{{admin_assets('global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}" rel="stylesheet"
              type="text/css"/>

        <style type="text/css">

            .page-sidebar .page-sidebar-menu li>a>.arrow.open:before, .page-sidebar .page-sidebar-menu li>a>.arrow:before, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu li>a>.arrow.open:before, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu li>a>.arrow:before {
                color: #b1c4d2;
                transform: rotate(180deg);
            }
        </style>


        @yield('css')

    @endif
    <link rel="icon" href="{{url('login/images/favicon.png')}}">
    <style type="text/css">

        input[type=file]{

            display: inline;

        }

        #image_preview{
            display: inline;
            padding: 10px;

        }

        #image_preview img{
            display: inline;
            width: 200px;

            padding: 5px;

        }

    </style>

    <style>
        .checked {
            color: orange;
        }
        .unchecked {
            color: silver;
        }
    </style>
</head>

<body class="page-container-bg-solid page-header-fixed page-sidebar-closed-hide-logo">

<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="{{url('/admin/home')}}">
                <img src="{{$setting->logo}}"
                     style="margin: 3px 10px 0 !important; height: 60px;" alt="logo" class="logo-default"/>
            </a>
            <div class="menu-toggler sidebar-toggler">
                <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
            </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
           data-target=".navbar-collapse"> </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN PAGE TOP -->
        <div class="page-top">
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                    <li class="separator hide"></li>
                    <li class="dropdown dropdown-user dropdown-dark">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                           data-close-others="true">
                            <span class="username username-hide-on-mobile"> {{auth()->guard('admin')->user()->name}} </span>
                            <!-- DOC: Do not remove below empty space(&nbsp;) as its purposely used -->
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                            <li>
                                <a href="{{ route('admin.admins.edit',auth()->guard('admin')->user()->id) }}">
                                    {{__('cp.edit_my_profile')}}
                                </a>


                            </li>
                            <li>
                                <a href="{{route('admin.admins.edit_password',auth()->guard('admin')->user()->id)}}">
                                    {{__('cp.Change Password')}}

                                </a>
                            </li>
                           <li>

                                <a href="{{ route('admin.logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{__('cp.logout')}}
                                </a>

                                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST"
                                      style="display: none;">
                                    {{ csrf_field() }}
                                </form>

                            </li>

                        </ul>
                    </li>

                    <li class="" style="padding: 10px">
                        @if(app()->getLocale() == 'en')
                            <?php
                            $lang = LaravelLocalization::getSupportedLocales()['ar']
                            ?>
                            <a href="{{ LaravelLocalization::getLocalizedURL('ar', null, [], true) }}" class="">
                                <div style="color: #abafc1; font-size: 13px"><b>{{ $lang['native'] }}</b></div>
                            </a>
                        @else
                            <?php
                            $lang = LaravelLocalization::getSupportedLocales()['en']
                            ?>
                            <a href="{{ LaravelLocalization::getLocalizedURL('en', null, [], true) }}" class="">
                                <div style="color: #abafc1; font-size: 13px"><b>{{ $lang['native'] }}</b></div>
                            </a>
                        @endif
                    </li>


                </ul>
            </div>
            <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END PAGE TOP -->
    </div>
    <!-- END HEADER INNER -->
</div>



<!-- BEGIN HEADER & CONTENT DIVIDER -->
<div class="clearfix"></div>
<!-- END HEADER & CONTENT DIVIDER -->
<!-- BEGIN CONTAINER -->
<div class="page-container">

    <div class="page-sidebar-wrapper">
        <div class="page-sidebar navbar-collapse collapse">
            <ul class="page-sidebar-menu   " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">


                {{--                <!--<div class="page-sidebar navbar-collapse collapse">--}}
                {{--                  <ul class="page-sidebar-menu   " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">-->--}}
                @php
                    $admin = auth('admin')->user();
                @endphp
                @if($admin->id== 1)
                    <li class="nav-item {{(explode("/", request()->url())[5] == "home") ? "active open" : ''}} start">
                        <a href="{{url(getLocal().'/admin/home')}}" class="nav-link">
                            <i class="icon-home"></i>
                            <span class="title">{{__('cp.home')}}</span>
                        </a>
                    </li>
                @else
                @endif

                <li class="nav-item">
                    <a contacts="javascript:;" class="nav-link nav-toggle"><i class="fa fa-usd"></i>
                        <span class="title">{{__('cp.financial')}}</span>
                        <span class="arrow  {{(in_array(explode("/", request()->url())[5],[ 'financial','profit','Expenses','capital','profit_tr','Recharge','user_prof'] )) ? "open" : ''}}"></span>
                    </a>
                    <ul class="sub-menu" style=" {{(in_array(explode("/", request()->url())[5],[ 'financial', 'profit','Expenses','capital','profit_tr','Recharge','user_prof'] )) ? "display:block" : ''}}">
                    @if(can('financial'))
                    <li class="nav-item {{(explode("/", request()->url())[5] == "financial") ? "active open" : ''}}">
                        <a href="{{url(getLocal().'/admin/financial')}}" class="nav-link nav-toggle">
                            <i class="fa fa-usd"></i>
                            <span class="title">{{__('cp.financial')}}</span>
                        </a>
                    </li>
                @endif
                    @if(can('profit'))
                <li class="nav-item {{(explode("/", request()->url())[5] == "profit") ? "active open" : ''}} start">
                    <a href="{{url(getLocal().'/admin/profit?type=0')}}" class="nav-link nav-toggle">
                        <i class="fa fa-money"></i>
                        <span class="title">{{__('كشف الارباح و المصاريف')}}</span>
                    </a>
                </li>
                <li class="nav-item {{(explode("/", request()->url())[5] == "profit_tr") ? "active open" : ''}} start">
                    <a href="{{url(getLocal().'/admin/profit_tr')}}" class="nav-link nav-toggle">
                        <i class="fa fa-money"></i>
                        <span class="title">{{__(' ربح يدوي')}}</span>
                    </a>
                </li>

                    <li class="nav-item {{(explode("/", request()->url())[5] == "capital") ? "active open" : ''}} start">
                        <a href="{{url(getLocal().'/admin/capital')}}" class="nav-link nav-toggle">
                            <i class="fa fa-money"></i>
                            <span class="title">{{__('راس المال')}}</span>
                        </a>
                    </li>
                    <li class="nav-item {{(explode("/", request()->url())[5] == "user_prof") ? "active open" : ''}} start">
                        <a href="{{url(getLocal().'/admin/user_prof')}}" class="nav-link nav-toggle">
                            <i class="fa fa-money"></i>
                            <span class="title">{{__('أرباح اللاعبين')}}</span>
                        </a>
                    </li>

                    @endif
                    @if(can('Expenses'))

                    <li class="nav-item {{(explode("/", request()->url())[5] == "Expenses") ? "active open" : ''}} start">
                        <a href="{{url(getLocal().'/admin/Expenses')}}" class="nav-link nav-toggle">
                            <i class="fa fa-usd"></i>
                            <span class="title">{{__(' مصاريف')}}</span>
                        </a>
                    </li>
                @endif
                @if(can('Recharge'))
                    <li class="nav-item {{(explode("/", request()->url())[5] == "Recharge") ? "active open" : ''}} start">
                        <a href="{{url(getLocal().'/admin/Recharge')}}" class="nav-link nav-toggle">
                            <i class="fa fa-usd"></i>
                            <span class="title">{{__(' شحن الرصيد')}}</span>
                        </a>
                    </li>
                @endif
                    </ul>
                </li>
                <li class="nav-item">
                    <a contacts="javascript:;" class="nav-link nav-toggle"><i class="fa fa-send"></i>
                        <span class="title">{{__('خدمات الخارجية ')}}</span>
                        <span class="arrow  {{(in_array(explode("/", request()->url())[5],[ 'ashab','AshabRequest' ] )) ? "open" : ''}}"></span>
                    </a>
                    <ul class="sub-menu" style=" {{(in_array(explode("/", request()->url())[5],[ 'ashab', 'AshabRequest'] )) ? "display:block" : ''}}">
                    @if(can('AshabGames'))
                <li class="nav-item {{(explode("/", request()->url())[5] == 'ashab') ? 'active open' : ''}} ">
                    <a href="/admin/ashab" class="nav-link nav-toggle">
                        <i class="fa fa-shopping-bag"></i>
                        <span class="title">{{__('منتجات اصحاب')}}</span>
                    </a>
                </li>
                @endif
                    @if(can('AshabRequest'))
                <li class="nav-item {{(explode("/", request()->url())[5] == 'AshabRequest') ? 'active open' : ''}} ">
                    <a href="/admin/AshabRequest" class="nav-link nav-toggle">
                        <i class="fa fa-shopping-cart"></i>
                        <span class="title">{{__('طلبات  اصحاب')}}</span>
                    </a>
                </li>
                <li class="nav-item {{(explode("/", request()->url())[5] == 'AshabLog') ? 'active open' : ''}} ">
                    <a href="/admin/AshabLog" class="nav-link nav-toggle">
                        <i class="fa fa-shopping-cart"></i>
                        <span class="title">{{__('سجل  اصحاب')}}</span>
                    </a>
                </li>
                <li class="nav-item {{(explode("/", request()->url())[5] == 'nagma_ashab') ? 'active open' : ''}} ">
                    <a href="/admin/nagma_ashab" class="nav-link nav-toggle">
                        <i class="fa fa-shopping-cart"></i>
                        <span class="title">{{__('اصحاب نجمة')}}</span>
                    </a>
                </li>
                @endif
                    </ul>
                </li>
                <li class="nav-item">
                    <a contacts="javascript:;" class="nav-link nav-toggle"><i class="fa fa-user-circle-o"></i>
                        <span class="title">{{__('ادارت الحسبات')}}</span>
                        <span class="arrow  {{(in_array(explode("/", request()->url())[5],[ 'admins','users' ] )) ? "open" : ''}}"></span>
                    </a>
                    <ul class="sub-menu" style=" {{(in_array(explode("/", request()->url())[5],[ 'admins', 'users'] )) ? "display:block" : ''}}">
                    @if(can('admins'))
                    <li class="nav-item {{(explode("/", request()->url())[5] == "admins") ? "active open" : ''}}">
                        <a href="{{url(getLocal().'/admin/admins')}}" class="nav-link nav-toggle">
                            <i class="fa fa-user"></i>
                            <span class="title">{{__('cp.admins')}}</span>

                        </a>
                    </li>
                @endif
                    @if(can('users'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == "users") ? "active open" : ''}}">
                                <a href="{{url(getLocal().'/admin/users')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-users"></i>
                                    <span class="title">{{__('cp.users')}}</span>

                                </a>

                            </li>
                        @endif
                    </ul>
                </li>
                <li class="nav-item">
                    <a contacts="javascript:;" class="nav-link nav-toggle"><i class="fa fa-server"></i>
                        <span class="title">{{__('cp.stores')}}</span>
                        <span class="arrow  {{(in_array(explode("/", request()->url())[5],[ 'stores','orders' ] )) ? "open" : ''}}"></span>
                    </a>
                    <ul class="sub-menu" style=" {{(in_array(explode("/", request()->url())[5],[ 'stores', 'orders'] )) ? "display:block" : ''}}">
                    @if(can('stores'))
                    <li class="nav-item {{(explode("/", request()->url())[5] == "stores") ? "active open" : ''}}">
                        <a href="{{url(getLocal().'/admin/stores')}}" class="nav-link nav-toggle">
                            <i class="fa fa-shopping-bag"></i>
                            <span class="title">{{__('cp.stores')}}</span>

                        </a>

                    </li>
                @endif
                    @if(can('orders'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == "orders") ? "active open" : ''}}">
                                <a href="{{url(getLocal().'/admin/orders')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span class="title">{{__('cp.orders')}}</span>
                                    <span style="" class="badge badge-danger">{{@App\Models\Order::where('status',-1)->count()}}</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                <li class="nav-item">
                    <a contacts="javascript:;" class="nav-link nav-toggle"><i class="fa fa-feed"></i>
                        <span class="title">{{__('قطع شبكات الانترنيت')}}</span>
                        <span class="arrow  {{(in_array(explode("/", request()->url())[5],[ 'person_mac','mac' ] )) ? "open" : ''}}"></span>
                    </a>
                    <ul class="sub-menu" style=" {{(in_array(explode("/", request()->url())[5],[ 'person_mac', 'mac'] )) ? "display:block" : ''}}">
                        @if(can('person_mac'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == "person_mac") ? "active open" : ''}}">
                             <a href="{{url(getLocal().'/admin/person_mac')}}" class="nav-link nav-toggle">
                             <i class="fa fa-user-o"></i>
                            <span class="title">اصحاب قطع الشبكات</span>
                        </a>
                            </li>
                        @endif
                        @if(can('mac'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == "mac") ? "active open" : ''}}">
                            <a href="{{url(getLocal().'/admin/mac')}}" class="nav-link nav-toggle">
                           <i class="fa fa-feed"></i>
                        <span class="title">  قطع الشبكات</span>
                            </a>
                         </li>
                    @endif
                    </ul>
                </li>
                <li class="nav-item">
                    <a contacts="javascript:;" class="nav-link nav-toggle"><i class="fa fa-wifi"></i>
                        <span class="title">{{__(' شبكات الانترنيت')}}</span>
                        <span class="arrow  {{(in_array(explode("/", request()->url())[5],[ 'NetworkSections','wifi','networks','requestRenewCard' ] )) ? "open" : ''}}"></span>
                    </a>
                    <ul class="sub-menu" style=" {{(in_array(explode("/", request()->url())[5],[ 'NetworkSections', 'wifi','networks','requestRenewCard'] )) ? "display:block" : ''}}">
                        @if(can('wifi'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == "wifi") ? "active open" : ''}} ">
                                <a href="{{url(getLocal().'/admin/wifi')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-wifi"></i>
                                    <span class="title">{{__('cp.wifi')}}</span>
                                </a>
                            </li>
                        @endif
                        @if(can('networks'))
                                <li class="nav-item {{(explode("/", request()->url())[5] == "networks") ? "active open" : ''}} ">
                                    <a href="{{url(getLocal().'/admin/networks')}}" class="nav-link nav-toggle">
                                        <i class="fa fa-diamond"></i>
                                        <span class="title">{{__('cp.networks')}}</span>
                                    </a>
                                </li>
                            @endif
                        @if(can('requestRenewCard'))
                                <li class="nav-item {{(explode("/", request()->url())[5] == "requestRenewCard") ? "active open" : ''}} ">
                                    <a href="{{url(getLocal().'/admin/requestRenewCard')}}" class="nav-link nav-toggle">
                                        <i class="fa fa-deviantart"></i>
                                        <span class="title">{{__('cp.requestRenewCard')}}</span>
                                        <span style="" class="badge badge-danger">{{@App\Models\RequestRenewCard::where('action',0)->count()}}</span>

                                    </a>
                                </li>
                            @endif
                        @if(can('NetworkSections'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == "NetworkSections") ? "active open" : ''}}">
                            <a href="{{url(getLocal().'/admin/NetworkSections')}}" class="nav-link nav-toggle">
                            <i class="fa fa-adjust"></i>
                            <span class="title">  اقسام الشبكات</span>
                         </a>
                        </li>
                       @endif
                    </ul>
                </li>

                <li class="nav-item">
                    <a contacts="javascript:;" class="nav-link nav-toggle"><i class="fa fa-hacker-news"></i>
                        <span class="title">{{__(' الاخبار والسلايدر')}}</span>
                        <span class="arrow  {{(in_array(explode("/", request()->url())[5],[ 'news','sliders' ] )) ? "open" : ''}}"></span>
                    </a>
                    <ul class="sub-menu" style=" {{(in_array(explode("/", request()->url())[5],[ 'news', 'sliders'] )) ? "display:block" : ''}}">
                        @if(can('news'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == "news") ? "active open" : ''}} ">
                                <a href="{{url(getLocal().'/admin/news')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-newspaper-o"></i>
                                    <span class="title">{{__('cp.news')}}</span>
                                </a>
                            </li>
                        @endif
                        @if(can('sliders'))
                                <li class="nav-item {{(explode("/", request()->url())[5] == "sliders") ? "active open" : ''}} ">
                                    <a href="{{url(getLocal().'/admin/sliders')}}" class="nav-link nav-toggle">
                                        <i class="fa fa-newspaper-o"></i>
                                        <span class="title">{{__('cp.sliders')}}</span>
                                    </a>
                                </li>
                            @endif
                    </ul>
                </li>

                <li class="nav-item">
                    <a contacts="javascript:;" class="nav-link nav-toggle"><i class="fa fa-send-o"></i>
                        <span class="title">{{__(' الاعلانات والاشعارات')}}</span>
                        <span class="arrow  {{(in_array(explode("/", request()->url())[5],[ 'ads','notifications' ] )) ? "open" : ''}}"></span>
                    </a>
                    <ul class="sub-menu" style=" {{(in_array(explode("/", request()->url())[5],[ 'ads', 'notifications'] )) ? "display:block" : ''}}">
                        @if(can('ads'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == "ads") ? "active open" : ''}} ">
                                <a href="{{url(getLocal().'/admin/ads')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-suitcase"></i>
                                    <span class="title">{{__('cp.ads')}}</span>
                                </a>
                            </li>
                        @endif
                        @if(can('notifications'))
                                <li class="nav-item  {{(explode("/", request()->url())[5] == "notifications") ? "active open" : ''}}">
                                    <a href="{{url(getLocal().'/admin/notifications')}}" class="nav-link nav-toggle">
                                        <i class="fa fa-bell"></i>
                                        <span class="title">{{__('cp.notifications')}}</span>

                                    </a>

                                </li>
                            @endif
                    </ul>
                </li>
                <li class="nav-item">
                    <a contacts="javascript:;" class="nav-link nav-toggle"><i class="fa fa-laptop"></i>
                        <span class="title">{{__('cp.categories')}}</span>
                        <span class="arrow  {{(in_array(explode("/", request()->url())[5],[ 'allStoreCategories','categories','subcategories','contacts' ] )) ? "open" : ''}}"></span>
                    </a>
                    <ul class="sub-menu" style=" {{(in_array(explode("/", request()->url())[5],[ 'allStoreCategories', 'categories','subcategories'] )) ? "display:block" : ''}}">
                        @if(can('allStoreCategories'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == "allStoreCategories") ? "active open" : ''}} ">
                                <a href="{{url(getLocal().'/admin/allStoreCategories')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-suitcase"></i>
                                    <span class="title">{{__('cp.allStoreCategories')}}</span>
                                </a>
                            </li>
                        @endif
                            @if(can('categories'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == "categories") ? "active open" : ''}} ">
                                <a href="{{url(getLocal().'/admin/categories')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-suitcase"></i>
                                    <span class="title">{{__('cp.categories')}}</span>
                                </a>
                            </li>
                        @endif
                        @if(can('subcategories'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == "subcategories") ? "active open" : ''}} ">
                                <a href="{{url(getLocal().'/admin/subcategories')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-bars"></i>
                                    <span class="title">{{__('cp.subcategory')}}</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                <li class="nav-item">
                    <a contacts="javascript:;" class="nav-link nav-toggle">
                        <i class="fa fa-cubes"></i>
                        <span class="title">{{__('cp.products')}}</span>
                        <span class="arrow  {{(in_array(explode("/", request()->url())[5],[ 'products', 'importProducts','productImages' ] )) ? "open" : ''}}"></span>
                    </a>
                    <ul class="sub-menu" style=" {{(in_array(explode("/", request()->url())[5],[ 'products', 'importProducts','productImages'] )) ? "display:block" : ''}}">
                        @if(can('products'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == "products") ? "active open" : ''}} ">
                                <a href="{{url(getLocal().'/admin/products')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-cubes" aria-hidden="true"></i>
                                    <span class="title">{{__('cp.products')}}</span>
                                </a>
                            </li>
                            <li class="nav-item {{(explode("/", request()->url())[5] == "importProducts") ? "active open" : ''}} ">
                                <a href="{{url(getLocal().'/admin/importProducts')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-cogs"></i><span class="title">{{__('cp.importProducts')}}</span>
                                </a>
                            </li>
                            <li class="nav-item {{(explode("/", request()->url())[5] == "productImages") ? "active open" : ''}} ">
                                <a href="{{url(getLocal().'/admin/productImages')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-image"></i><span class="title">{{__('cp.productImages')}}</span>
                                </a>
                            </li>

                        @endif
                    </ul>
                </li>
                @if(can('azkar'))
                    <li class="nav-item">
                        <a contacts="javascript:;" class="nav-link nav-toggle">
                            <i
                                    class="fa fa-first-order"></i>
                            <span class="title">{{__('cp.azkar')}}</span>
                            <span class="arrow  {{(in_array(explode("/", request()->url())[5],[ 'azkar','azkarDetails' ] )) ? "open" : ''}}"></span>
                        </a>
                        <ul class="sub-menu" style=" {{(in_array(explode("/", request()->url())[5],[ 'azkar','azkarDetails'] )) ? "display:block" : ''}}">
                            <li class="nav-item {{(explode("/", request()->url())[5] == "azkar") ? "active open" : ''}} ">
                                <a href="{{url(getLocal().'/admin/azkar')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-first-order"></i>
                                    <span class="title">{{__('cp.azkar')}}</span>
                                </a>
                            </li>
                            <li class="nav-item {{(explode("/", request()->url())[5] == "azkarDetails") ? "active open" : ''}} ">
                                <a href="{{url(getLocal().'/admin/azkarDetails')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-first-order"></i>
                                    <span class="title">{{__('cp.azkarDetails')}}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                <li class="nav-item ">
                    <a contacts="javascript:;" class="nav-link nav-toggle">
                        <i class="fa fa-gamepad"></i>
                        <span class="title">{{__('cp.games')}}</span>
                        <span class="arrow  {{(in_array(explode("/", request()->url())[5],[ 'games','gameServies','gameRequest' ] )) ? ' open' : ''}}"></span>
                    </a>
                    <ul class="sub-menu" style=" {{(in_array(explode("/", request()->url())[5],[ 'games','gameServies','gameRequest'] )) ? "display:block" : ''}}">
                        @if(can('games'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == 'games') ? 'active open' : ''}} ">
                                <a href="{{url(getLocal().'/admin/games')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-gamepad"></i>
                                    <span class="title">{{__('cp.games')}}</span>
                                </a>
                            </li>
                            <li class="nav-item {{(explode("/", request()->url())[5] == 'gameServies') ? 'active open' : ''}} ">
                                <a href="{{url(getLocal().'/admin/gameServies')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-gamepad"></i>
                                    <span class="title">{{__('cp.gameServies')}}</span>
                                </a>
                            </li>
                            <li class="nav-item {{(explode("/", request()->url())[5] == 'nagma_game') ? 'active open' : ''}} ">
                                <a href="{{url(getLocal().'/admin/nagma_game')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-gamepad"></i>
                                    <span class="title">{{__('أصحاب نجمة ألعاب')}}</span>
                                </a>
                            </li>
                        @endif
                        @if(can('gameRequest'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == 'gameRequest') ? 'active open' : ''}} ">
                                <a href="{{url(getLocal().'/admin/gameRequest')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-gamepad"></i>
                                    <span class="title">{{__('cp.gameRequest')}}</span>
                                </a>
                            </li>
                        @endif
                        @if(can('apis'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == 'apis') ? 'active open' : ''}} ">
                                <a href="{{url(getLocal().'/admin/apis')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-gamepad"></i>
                                    <span class="title">API's</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                <li class="nav-item">
                    <a contacts="javascript:;" class="nav-link nav-toggle">
                        <i class="fa fa-xing-square"></i>
                        <span class="title">{{__('cp.publicServices')}}</span>
                        <span class="arrow  {{(in_array(explode("/", request()->url())[5],[ 'publicServices','publicServicesRequest' ] )) ? "open" : ''}}"></span>
                    </a>
                    <ul class="sub-menu" style=" {{(in_array(explode("/", request()->url())[5],[ 'publicServices', 'publicServicesRequest'] )) ? "display:block" : ''}}">
                        @if(can('publicServices'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == 'publicServices') ? "active open" : ''}} ">
                                <a href="{{url(getLocal().'/admin/publicServices')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-xing-square"></i>
                                    <span class="title">{{__('cp.publicServices')}}</span>
                                </a>
                            </li>
                        @endif
                        @if(can('publicServicesRequest'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == 'publicServicesRequest') ? "active open" : ''}} ">
                                <a href="{{url(getLocal().'/admin/publicServicesRequest')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-xing-square"></i>
                                    <span class="title">{{__('cp.publicServicesRequest')}}</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>

                <li class="nav-item">
                    <a contacts="javascript:;" class="nav-link nav-toggle">
                        <i class="fa fa-dedent"></i>
                        <span class="title">{{__('cp.services')}}</span>
                        <span class="arrow  {{(in_array(explode("/", request()->url())[5],[ 'services','productServices'] )) ? "open" : ''}}"></span>
                    </a>
                    <ul class="sub-menu" style=" {{(in_array(explode("/", request()->url())[5],[ 'services', 'productServices'] )) ? "display:block" : ''}}">
                        @if(can('service'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == "service") ? "active open" : ''}} ">
                                <a href="{{url(getLocal().'/admin/services')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-shopping-basket"></i>
                                    <span class="title">{{__('cp.service')}}</span>
                                </a>
                            </li>
                        @endif
                        @if(can('productServices'))
                            <li class="nav-item"{{(explode("/", request()->url())[5] == "productServices") ? "active open" : ''}} >
                                <a href="{{url(getLocal().'/admin/productServices')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-shopping-bag"></i>
                                    <span class="title">{{__('cp.productServices')}}</span>
                                </a>
                            </li>
                        @endif
                        @if(can('soldServices'))
                                <li class="nav-item {{(explode("/", request()->url())[5] == "soldServices") ? "active open" : ''}}">
                                    <a href="{{url(getLocal().'/admin/soldServices')}}" class="nav-link nav-toggle">
                                        <i class="fa fa-shopping-cart"></i>
                                        <span class="title">{{__('cp.soldServices')}}</span>
                                    </a>
                                </li>
                            @endif
                    </ul>
                </li>

                <li class="nav-item">
                    <a contacts="javascript:;" class="nav-link nav-toggle">
                        <i class="fa fa-dashboard"></i>
                        <span class="title">{{__('cp.institutes')}}</span>
                        <span class="arrow  {{(in_array(explode("/", request()->url())[5],[ 'institutes','instituteCourses','courseRequest' ] )) ? "open" : ''}}"></span>
                    </a>
                    <ul class="sub-menu" style=" {{(in_array(explode("/", request()->url())[5],[ 'institutes', 'instituteCourses','courseRequest'] )) ? "display:block" : ''}}">
                        @if(can('institutes'))

                            @if(is_one_institute())
                                <li class="nav-item {{(explode("/", request()->url())[5] == "institutes") ? "active open" : ''}} ">
                                    <a href="{{url(getLocal().'/admin/institutes')}}" class="nav-link nav-toggle">
                                        <i class="fa fa-dashboard"></i>
                                        <span class="title">{{__('cp.institutes')}}</span>
                                    </a>
                                </li>
                            @endif

                            <li class="nav-item {{(explode("/", request()->url())[5] == "instituteCourses") ? "active open" : ''}} ">
                                <a href="{{url(getLocal().'/admin/instituteCourses')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-dashboard"></i>
                                    <span class="title">{{__('cp.instituteCourses')}}</span>
                                </a>
                            </li>
                        @endif
                        @if(can('courseRequest'))

                            <li class="nav-item {{(explode("/", request()->url())[5] == "courseRequest") ? "active open" : ''}} ">
                                <a href="{{url(getLocal().'/admin/courseRequest')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-dashboard"></i>
                                    <span class="title">{{__('cp.courseRequest')}}</span>
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>
                <li class="nav-item">
                    <a contacts="javascript:;" class="nav-link nav-toggle">
                        <i class="fa fa-laptop"></i>
                        <span class="title">{{__('cp.constants')}}</span>
                        <span class="arrow  {{(in_array(explode("/", request()->url())[5],[ 'cities','sizes','colors','countrie' ] )) ? "open" : ''}}"></span>
                    </a>
                    <ul class="sub-menu" style=" {{(in_array(explode("/", request()->url())[5],[ 'cities', 'sizes','colors','countrie'] )) ? "display:block" : ''}}">

                        @if(can('countrie'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == 'countries') ? "active open" : ''}} ">
                                <a href="{{url(getLocal().'/admin/countries')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-globe"></i>
                                    <span class="title">{{__('ادارة الدول')}}</span>
                                </a>
                            </li>
                        @endif

                        @if(can('cities'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == 'cities') ? "active open" : ''}} ">
                                <a href="{{url(getLocal().'/admin/cities')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-globe"></i>
                                    <span class="title">{{__('cp.cities')}}</span>
                                </a>
                            </li>
                        @endif
                        @if(can('sizes'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == 'sizes') ? "active open" : ''}} ">
                                <a href="{{url(getLocal().'/admin/sizes')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-arrows"></i>
                                    <span class="title">{{__('cp.sizes')}}</span>
                                </a>
                            </li>
                        @endif
                        @if(can('colors'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == "colors") ? "active open" : ''}} ">
                                <a href="{{url(getLocal().'/admin/colors')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-eyedropper"></i>
                                    <span class="title">{{__('cp.colors')}}</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>

                <li class="nav-item">
                    <a contacts="javascript:;" class="nav-link nav-toggle">
                        <i class="fa fa-whatsapp"></i>
                        <span class="title">{{__('واتس اب')}}</span>
                        <span class="arrow  {{(in_array(explode("/", request()->url())[5],[ 'Whatsapp','ProductServiceRequests'] )) ? "open" : ''}}"></span>
                    </a>
                    <ul class="sub-menu" style=" {{(in_array(explode("/", request()->url())[5],[ 'Whatsapp', 'ProductServiceRequests'] )) ? "display:block" : ''}}">
                        @if(can('Whatsapp'))
                            <li class="nav-item" {{(explode("/", request()->url())[5] == "Whatsapp") ? "active open" : ''}}>
                                <a href="{{url(getLocal().'/admin/Whatsapp')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-whatsapp"></i>
                                    <span class="title">ارقام الواتس </span>
                                </a>
                            </li>
                        @endif
                        @if(can('WhatsAppProductServiceRequests'))
                            <li class="nav-item"{{(explode("/", request()->url())[5] == "ProductServiceRequests") ? "active open" : ''}}>
                                <a href="{{url(getLocal().'/admin/ProductServiceRequests')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-whatsapp"></i>
                                    <span class="title">طلبات ارقام الوتس</span>
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>

                <li class="nav-item">
                    <a contacts="javascript:;" class="nav-link nav-toggle">
                        <i class="fa fa-mobile"></i>
                        <span class="title">{{__('شبكات الموبيل')}}</span>
                        <span class="arrow  {{(in_array(explode("/", request()->url())[5],[ 'MobileCompany','MobileNetworkPackages','requestMobileBalance'] )) ? "open" : ''}}"></span>
                    </a>
                    <ul class="sub-menu" style=" {{(in_array(explode("/", request()->url())[5],[ 'MobileCompany', 'MobileNetworkPackages','requestMobileBalance'] )) ? "display:block" : ''}}">
                        @if(can('MobileCompany'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == 'MobileCompany') ? 'active open' : ''}} ">
                                <a href="/admin/MobileCompany" class="nav-link nav-toggle">
                                    <i class="fa fa-mobile-phone"></i>
                                    <span class="title">{{__('شبكات الموبيل')}}</span>
                                </a>
                            </li>
                        @endif
                        @if(can('MobileNetworkPackages'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == 'MobileNetworkPackages') ? 'active open' : ''}} ">
                                <a href="/admin/MobileNetworkPackages" class="nav-link nav-toggle">
                                    <i class="fa fa-mobile"></i>
                                    <span class="title">{{__('باقات شبكات الموبيل')}}</span>
                                </a>
                            </li>
                        @endif
                        @if(can('requestMobileBalance'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == "requestMobileBalance") ? "active open" : ''}} ">
                                <a href="{{url(getLocal().'/admin/requestMobileBalance')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-mobile"></i>
                                    <span class="title">{{__('cp.requestMobileBalance')}}</span>
                                    <span style="" class="badge badge-danger">{{@App\Models\RequestMobileBalance::where('action',0)->count()}}</span>

                                </a>
                            </li>
                        @endif
                    </ul>
                </li>

                <li class="nav-item">
                    <a contacts="javascript:;" class="nav-link nav-toggle">
                        <i class="fa fa-ravelry"></i>
                        <span class="title">{{__('cp.balanceCards')}}</span>
                        <span class="arrow  {{(in_array(explode("/", request()->url())[5],[ 'balanceCards','newbalanceCards'] )) ? "open" : ''}}"></span>
                    </a>
                    <ul class="sub-menu" style=" {{(in_array(explode("/", request()->url())[5],[ 'balanceCards', 'newbalanceCards'] )) ? "display:block" : ''}}">
                        @if(can('balanceCards'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == "balanceCards") ? "active open" : ''}} ">
                                <a href="{{url(getLocal().'/admin/balanceCards')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-ravelry"></i>
                                    <span class="title">{{__('cp.balanceCards')}}</span>
                                </a>
                            </li>
                        @endif
                            @if(can('newbalanceCards'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == "newbalanceCards") ? "active open" : ''}} ">
                                <a href="{{url(getLocal().'/admin/newbalanceCards')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-ravelry"></i>
                                    <span class="title">{{__('cp.UsedBalanceCard')}}</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                <li class="nav-item">
                    <a contacts="javascript:;" class="nav-link nav-toggle">
                        <i class="fa fa-comments-o"></i>
                        <span class="title">{{__('رسال التواصل والدردشة')}}</span>
                        <span class="arrow  {{(in_array(explode("/", request()->url())[5],[ 'chat','contact'] )) ? "open" : ''}}"></span>
                    </a>
                    <ul class="sub-menu" style=" {{(in_array(explode("/", request()->url())[5],[ 'chat', 'contact'] )) ? "display:block" : ''}}">
                        @if(can('chat'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == "chat") ? "active open" : ''}}">
                                <a href="{{url(getLocal().'/admin/chat')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-comments-o"></i>
                                    <span class="title">{{__('cp.chat')}}</span>
                                    <span style="" class="badge badge-danger">{{@App\Models\Chat::where('read',0)->where('sender','0')->count()}}</span>
                                </a>
                            </li>
                        @endif
                        @if(can('contact-us'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == "contact") ? "active open" : ''}}">
                                <a href="{{url(getLocal().'/admin/contact')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-envelope-o"></i>
                                    <span class="title">{{__('cp.contact')}}</span>
                                    <span style="" class="badge badge-danger">{{@App\Models\Contact::where('read',0)->count()}}</span>

                                </a>
                            </li>
                        @endif

                    </ul>
                </li>

                <li class="nav-item">
                    <a contacts="javascript:;" class="nav-link nav-toggle">
                        <i class="fa fa-cogs"></i>
                        <span class="title">{{__('الاعدادات والصفحات')}}</span>
                        <span class="arrow  {{(in_array(explode("/", request()->url())[5],[ 'pages','apk','settings','roles'] )) ? "open" : ''}}"></span>
                    </a>
                    <ul class="sub-menu" style=" {{(in_array(explode("/", request()->url())[5],[ 'pages', 'apk','settings','roles'] )) ? "display:block" : ''}}">
                        @if(can('pages'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == "pages") ? "active open" : ''}} ">
                                <a href="{{url(getLocal().'/admin/pages')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-clone"></i>
                                    <span class="title">{{__('cp.pages')}}</span>
                                </a>

                            </li>
                        @endif
                        @if(can('apk'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == "apk") ? "active open" : ''}} ">
                                <a href="{{url(getLocal().'/admin/apk')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-download"></i>
                                    <span class="title">ملف التحميل المباشر</span>
                                </a>

                            </li>
                        @endif
                        @if(can('settings'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == "settings") ? "active open" : ''}} ">
                                <a href="{{url(getLocal().'/admin/settings')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-cogs"></i>
                                    <span class="title">{{__('cp.setting')}}</span>
                                </a>

                            </li>
                        @endif
{{--                            @if(can('roles'))--}}
{{--                                <li class="nav-item {{(explode("/", request()->url())[5] == "roles") ? "active open" : ''}}">--}}
{{--                                    <a href="{{url(getLocal().'/admin/roles')}}" class="nav-link nav-toggle">--}}
{{--                                        <i class="fa fa-adjust"></i>--}}
{{--                                        <span class="title">{{__('الصلاحيات')}}</span>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                            @endif--}}
                    </ul>
                </li>

                <li class="nav-item">
                    <a contacts="javascript:;" class="nav-link nav-toggle">
                        <i class="fa fa-percent"></i>
                        <span class="title">{{__(' نقاط التوزيع والخصم')}}</span>
                        <span class="arrow  {{(in_array(explode("/", request()->url())[5],[ 'promotions','distribution_points'] )) ? "open" : ''}}"></span>
                    </a>
                    <ul class="sub-menu" style=" {{(in_array(explode("/", request()->url())[5],[ 'promotions', 'distribution_points'] )) ? "display:block" : ''}}">
                        @if(can('promotions'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == "promotions") ? "active open" : ''}}">
                                <a href="{{url(getLocal().'/admin/promotions')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-percent"></i>
                                    <span class="title">{{__('cp.promotions')}}</span>

                                </a>
                            </li>
                        @endif
                        @if(can('distribution_points'))
                            <li class="nav-item {{(explode("/", request()->url())[5] == "distribution_points") ? "active open" : ''}} ">
                                <a href="{{url(getLocal().'/admin/distribution_points')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-newspaper-o"></i>
                                    <span class="title">{{__('cp.distribution_points')}}</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                @if(can('questions'))
                    <li class="nav-item {{(explode("/", request()->url())[5] == "questions") ? "active open" : ''}} ">
                        <a href="{{url(getLocal().'/admin/questions')}}" class="nav-link nav-toggle">
                            <i class="fa fa-question-circle"></i>
                            <span class="title">{{__('cp.questions')}}</span>
                        </a>

                    </li>
                @endif
                
                @if(can('promo_codes'))
                    <li class="nav-item {{(explode("/", request()->url())[5] == "promo_codes") ? "active open" : ''}} ">
                        <a href="{{url(getLocal().'/admin/promo_codes')}}" class="nav-link nav-toggle">
                            <i class="fa fa-question-circle"></i>
                            <span class="title">{{__('cp.promo_codes')}}</span>
                        </a>

                    </li>
                @endif
            </ul>
        </div>
    </div>
    {{--Begin Content--}}
    <div class="page-content-wrapper">
    <div class="page-content">
            <div class="page-head">
                <div class="page-title">
                    <h1>@yield('title')
                    </h1>
                </div>
                <div class="page-toolbar" style="display: none;">
                    <div class="btn-group btn-theme-panel">
                        <a href="javascript:;" class="btn dropdown-toggle" data-toggle="dropdown">
                            <i class="icon-settings"></i>
                        </a>
                        <div class="dropdown-menu theme-panel pull-right dropdown-custom hold-on-click">
                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <h3>HEADER</h3>
                                    <ul class="theme-colors">
                                        <li class="theme-color theme-color-default active" data-theme="default">
                                            <span class="theme-color-view"></span>
                                            <span class="theme-color-name">Dark Header</span>
                                        </li>
                                        <li class="theme-color theme-color-light " data-theme="light">
                                            <span class="theme-color-view"></span>
                                            <span class="theme-color-name">Light Header</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-8 col-sm-8 col-xs-12 seperator">
                                    <h3>LAYOUT</h3>
                                    <ul class="theme-settings">
                                        <li> Theme Style
                                            <select class="layout-style-option form-control input-small input-sm">
                                                <option value="square">Square corners</option>
                                                <option value="rounded" selected="selected">Rounded corners</option>
                                            </select>
                                        </li>
                                        <li> Layout
                                            <select class="layout-option form-control input-small input-sm">
                                                <option value="fluid" selected="selected">Fluid</option>
                                                <option value="boxed">Boxed</option>
                                            </select>
                                        </li>
                                        <li> Header
                                            <select class="page-header-option form-control input-small input-sm">
                                                <option value="fixed" selected="selected">Fixed</option>
                                                <option value="default">Default</option>
                                            </select>
                                        </li>
                                        <li> Top Dropdowns
                                            <select class="page-header-top-dropdown-style-option form-control input-small input-sm">
                                                <option value="light">Light</option>
                                                <option value="dark" selected="selected">Dark</option>
                                            </select>
                                        </li>
                                        <li> Sidebar Mode
                                            <select class="sidebar-option form-control input-small input-sm">
                                                <option value="fixed">Fixed</option>
                                                <option value="default" selected="selected">Default</option>
                                            </select>
                                        </li>
                                        <li> Sidebar Menu
                                            <select class="sidebar-menu-option form-control input-small input-sm">
                                                <option value="accordion" selected="selected">Accordion</option>
                                                <option value="hover">Hover</option>
                                            </select>
                                        </li>
                                        <li> Sidebar Position
                                            <select class="sidebar-pos-option form-control input-small input-sm">
                                                <option value="left" selected="selected">Left</option>
                                                <option value="right">Right</option>
                                            </select>
                                        </li>
                                        <li> Footer
                                            <select class="page-footer-option form-control input-small input-sm">
                                                <option value="fixed">Fixed</option>
                                                <option value="default" selected="selected">Default</option>
                                            </select>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <ul class="page-breadcrumb breadcrumb">
                @if($admin->id == 1)
                <li>
                    <a href="{{url('/admin/home')}}">{{__('cp.statistics')}}</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span class="active">@yield('title')</span>
                </li>
                @else

                @endif
            </ul>
            @if (count($errors) > 0)
                <ul style="border: 1px solid #e02222; background-color: white">
                    @foreach ($errors->all() as $error)
                        <li style="color: #e02222; margin: 15px">{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            @if (session('status'))
                <ul style="border: 1px solid #01b070; background-color: white">
                    <li style="color: #01b070; margin: 15px">{{  session('status')  }}</li>
                </ul>
            @endif
            @if (session('error'))
                <ul style="border: 1px solid #e02222; background-color: white">
                    <li style="color: #e02222; margin: 15px">{{  session('error')  }}</li>
                </ul>
            @endif

            @yield('content')
        </div>

    </div>

    <!-- END CONTENT -->

    <div class="page-footer">
        <div class="page-footer-inner"> {{date("Y").' Powered By'}}
            <a target="_blank" href="http://hexacit.com/">{{'HEXA'}}</a>
        </div>
        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
    </div>


</div>

<div id="deleteAll" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">{{__('cp.delete')}}</h4>
            </div>
            <div class="modal-body">
                <p>{{__('cp.confirmDeleteAll')}} </p>
            </div>
            <div class="modal-footer">
                <button class="btn default" data-dismiss="modal" aria-hidden="true" >{{__('cp.cancel')}}</button>
                <a href="#" class="confirmAll" data-action="delete"><button class="btn btn-danger">{{__('cp.delete')}}</button></a>
            </div>
        </div>
    </div>
</div>

<div id="activation" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">{{__('cp.activation')}}</h4>
            </div>
            <div class="modal-body">
                <p>{{__('cp.confirmActiveAll')}} </p>
            </div>
            <div class="modal-footer">
                <button class="btn default" data-dismiss="modal" aria-hidden="true">{{__('cp.cancel')}}</button>
                <a href="#" class="confirmAll" data-action="active">
                    <button class="btn btn-success">{{__('cp.Yes')}}</button>
                </a>
            </div>
        </div>
    </div>
</div>

<div id="cancel_activation" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">{{__('cp.cancel_activation')}}</h4>
            </div>
            <div class="modal-body">
                <p>{{__('cp.confirmNotActiveAll')}} </p>
            </div>
            <div class="modal-footer">
                <button class="btn default" data-dismiss="modal" aria-hidden="true">{{__('cp.cancel')}}</button>
                <a href="#" class="confirmAll" data-action="not_active"><button class="btn btn-default">{{__('cp.Yes')}}</button></a>
            </div>
        </div>
    </div>
</div>

<script src="{{admin_assets('/global/plugins/jquery.min.js')}}" type="text/javascript"></script>
<script src="{{admin_assets('/global/plugins/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
@yield('js_file_upload')

<script src="{{admin_assets('/global/plugins/moment.min.js')}}" type="text/javascript"></script>




<script src="{{admin_assets('/global/plugins/counterup/jquery.waypoints.min.js')}}" type="text/javascript"></script>
<script src="{{admin_assets('/global/plugins/counterup/jquery.counterup.min.js')}}" type="text/javascript"></script>
<script src="{{admin_assets('/global/scripts/app.min.js')}}" type="text/javascript"></script>
<script src="{{admin_assets('/layouts/layout4/scripts/layout.min.js')}}" type="text/javascript"></script>
<script src="{{admin_assets('/global/plugins/bootstrap-sweetalert/sweetalert.min.js')}}" type="text/javascript"></script>
<script src="{{admin_assets('/global/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
<script src="{{admin_assets('/pages/scripts/components-select2.min.js')}}" type="text/javascript"></script>
<script src="{{admin_assets('/pages/scripts/ui-sweetalert.min.js')}}" type="text/javascript"></script>
<script src="{{admin_assets('global/scripts/datatable.js')}}" type="text/javascript"></script>
@if(app()->getLocale() == "ar")
    <script src="{{admin_assets('global/plugins/datatables/datatables.min.js')}}" type="text/javascript"></script>
@else

    <script src="{{admin_assets('global/plugins/datatables/datatables.min.js')}}" type="text/javascript"></script>
@endif
<script src="{{admin_assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js')}}" type="text/javascript"></script>
<script src="{{admin_assets('pages/scripts/table-datatables-managed.min.js')}}" type="text/javascript"></script>
<script src="{{admin_assets('global/plugins/ckeditor/ckeditor.js')}}" type="text/javascript"></script>

<script src="{{admin_assets('/global/plugins/jquery-validation/js/jquery.validate.js')}}" type="text/javascript"></script>
<script src="{{admin_assets('/global/plugins/jquery-validation/js/additional-methods.min.js')}}" type="text/javascript"></script>
<script src="{{admin_assets('/global/scripts/app.min.js')}}" type="text/javascript"></script>


<script src="{{admin_assets('sweet_alert/sweetalert.min.js')}}" type="text/javascript"></script>
<script src="{{admin_assets('global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}" type="text/javascript"></script>

<script src="{{admin_assets('global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{admin_assets('global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js')}}" type="text/javascript"></script>
<script src="{{admin_assets('global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
<script src="{{admin_assets('pages/scripts/components-date-time-pickers.min.js')}}" type="text/javascript"></script>


@yield('js')

<script type="text/javascript">
    var FormValidation = function () {

        // basic validation
        var handleValidation1 = function() {
            // for more info visit the official plugin documentation:
            // http://docs.jquery.com/Plugins/Validation

            var form1 = $('#form');
            var error1 = $('.alert-danger', form1);
            var success1 = $('.alert-success', form1);

            form1.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                messages: {
                    select_multi: {
                        maxlength: jQuery.validator.format("Max {0} items allowed for selection"),
                        minlength: jQuery.validator.format("At least {0} items must be selected"),
                    },
                },
                rules: {
                    name: {
                        minlength: 2,
                        required: true
                    },
                    email: {
                        required: true,
                        email: true
                    },

                    mobile: {
                        required: true,
                        minlength: 8
                    },

                    password: { required: true },
                    confirm_password: { required: true , equalTo: '[name="password"]'},
                    admin_type: {
                        required: true
                    },

                    title: {required: true},
                    logo: {required: true},
                },

                invalidHandler: function (event, validator) { //display error alert on form submit
                    success1.hide();
                    error1.show();
                    App.scrollTo(error1, -200);
                },




                //     errorPlacement: function (error, element) { // render error placement for each input typeW
                //     if (element.parent(".input-group").size() > 0)
                //     {
                //         error.insertAfter(element.parent(".input-group"));
                //     }
                //     else if (element.attr("data-error-container"))
                //     {
                //         error.appendTo(element.attr("data-error-container"));
                //     }
                //     else
                //     {
                //         error.insertAfter(element); // for other inputs, just perform default behavior
                //     }
                // },

                highlight: function (element) { // hightlight error inputs

                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },

                submitHandler: function (form) {
                    success1.show();
                    error1.hide
                    e.submit()                }
            });


        };



        return {
            //main function to initiate the module
            init: function () {

                handleValidation1();

            }

        };

    }();

    jQuery(document).ready(function() {
        FormValidation.init();
    });



    $r = '{{app()->getLocale()}}' ;
    if($r == 'ar'){



        //overright messsages
        $.extend( $.validator, {

            defaults: {
                messages: {},
                groups: {},
                rules: {},
                errorClass: "error",
                validClass: "valid",
                errorElement: "label",
                focusCleanup: false,
                focusInvalid: true,
                errorContainer: $( [] ),
                errorLabelContainer: $( [] ),
                onsubmit: true,
                ignore: ":hidden",
                ignoreTitle: false,
                onfocusin: function( element ) {
                    this.lastActive = element;

                    // Hide error label and remove error class on focus if enabled
                    if ( this.settings.focusCleanup ) {
                        if ( this.settings.unhighlight ) {
                            this.settings.unhighlight.call( this, element, this.settings.errorClass, this.settings.validClass );
                        }
                        this.hideThese( this.errorsFor( element ) );
                    }
                },
                onfocusout: function( element ) {
                    if ( !this.checkable( element ) && ( element.name in this.submitted || !this.optional( element ) ) ) {
                        this.element( element );
                    }
                },
                onkeyup: function( element, event ) {
                    // Avoid revalidate the field when pressing one of the following keys
                    // Shift       => 16
                    // Ctrl        => 17
                    // Alt         => 18
                    // Caps lock   => 20
                    // End         => 35
                    // Home        => 36
                    // Left arrow  => 37
                    // Up arrow    => 38
                    // Right arrow => 39
                    // Down arrow  => 40
                    // Insert      => 45
                    // Num lock    => 144
                    // AltGr key   => 225
                    var excludedKeys = [
                        16, 17, 18, 20, 35, 36, 37,
                        38, 39, 40, 45, 144, 225
                    ];

                    if ( event.which === 9 && this.elementValue( element ) === "" || $.inArray( event.keyCode, excludedKeys ) !== -1 ) {
                        return;
                    } else if ( element.name in this.submitted || element === this.lastElement ) {
                        this.element( element );
                    }
                },
                onclick: function( element ) {
                    // click on selects, radiobuttons and checkboxes
                    if ( element.name in this.submitted ) {
                        this.element( element );

                        // or option elements, check parent select in that case
                    } else if ( element.parentNode.name in this.submitted ) {
                        this.element( element.parentNode );
                    }
                },
                highlight: function( element, errorClass, validClass ) {
                    if ( element.type === "radio" ) {
                        this.findByName( element.name ).addClass( errorClass ).removeClass( validClass );
                    } else {
                        $( element ).addClass( errorClass ).removeClass( validClass );
                    }
                },
                unhighlight: function( element, errorClass, validClass ) {
                    if ( element.type === "radio" ) {
                        this.findByName( element.name ).removeClass( errorClass ).addClass( validClass );
                    } else {
                        $( element ).removeClass( errorClass ).addClass( validClass );
                    }
                }
            },

            // http://jqueryvalidation.org/jQuery.validator.setDefaults/
            setDefaults: function( settings ) {
                $.extend( $.validator.defaults, settings );
            },



            messages: {

                required: "هذا الحقل مطلوب",
                remote: "Please fix this field.",
                email: "الرجاء ادخال عنوان بريد الكتروني صحيح .",
                date: "الرجاء ادخال تاريخ صحيح.",
                dateISO: "Please enter a valid date ( ISO ).",
                number: "Please enter a valid number.",
                digits: "Please enter only digits.",
                creditcard: "Please enter a valid credit card number.",
                equalTo: "الرجاء ادخال نفس قيمة الباسوورد.",
                maxlength: $.validator.format( "Please enter no more than {0} characters." ),
                minlength: $.validator.format( "Please enter at least {0} characters." ),
                rangelength: $.validator.format( "Please enter a value between {0} and {1} characters long." ),
                range: $.validator.format( "Please enter a value between {0} and {1}." ),
                max: $.validator.format( "الرجاء ادخال قيمة اقل او تساوي {0}." ),
                min: $.validator.format( "الرجاء ادخال قيمة اكبر او تساوي {0}." ) ,
                category_id: "حقل التصنيف مطلوب"
            },

        });
    }






</script>
<script>


    $(document).ready(function () {
        $('#clickmewow').click(function () {
            $('#radio1003').attr('checked', 'checked');
        });
    });


</script>
<script>

    var IDArray = [];
    $("input:checkbox[name=chkBox]:checked").each(function () {
        IDArray.push($(this).val());
    });
    if (IDArray.length == 0) {
        $('.event').attr('disabled', 'disabled');
    }
    $('.chkBox').on('change', function () {
        var IDArray = [];
        $("input:checkbox[name=chkBox]:checked").each(function () {
            IDArray.push($(this).val());
        });
        if (IDArray.length == 0) {
            $('.event').attr('disabled', 'disabled');
        } else {
            $('.event').removeAttr('disabled');
        }
    });
    $('.confirmAll').on('click', function (e) {
        e.preventDefault();
        var action = $(this).data('action');

        var url = "{{ url(getLocal().'/admin/changeStatus/'.Request::segment(3)) }}";
        var csrf_token = '{{csrf_token()}}';
        var IDsArray = [];
        $("input:checkbox[name=chkBox]:checked").each(function () {
            IDsArray.push($(this).val());
        });

        if (IDsArray.length > 0) {
            $.ajax({
                type: 'POST',
                headers: {'X-CSRF-TOKEN': csrf_token},
                url: url,
                data: {action: action, IDsArray: IDsArray, _token: csrf_token},
                success: function (response) {
                    if (response === 'active') {
                        //alert('fsvf');
                        $.each(IDsArray, function (index, value) {
                            $('#label-' + value).removeClass('label-danger');
                            $('#label-' + value).addClass('label-info');
                            $r = '{{app()->getLocale()}}';
                            if($r == 'ar'){
                                $('#label-' + value).text('فعال ');
                            }else{
                                $('#label-' + value).text('active');

                            }
                        });
                        $('#activation').modal('hide');
                    } else if (response === 'not_active') {
                        //alert('fg');
                        $.each(IDsArray, function (index, value) {
                            $('#label-' + value).removeClass('label-info');
                            $('#label-' + value).addClass('label-danger');
                            $r = '{{app()->getLocale()}}';
                            if($r == 'ar'){
                                $('#label-' + value).text('غير فعال');
                            }else{
                                $('#label-' + value).text('Not Active');

                            }
                        });
                        $('#cancel_activation').modal('hide');
                    } else if (response === 'delete') {
                        $.each(IDsArray, function (index, value) {
                            $('#tr-' + value).hide(2000);
                        });
                        $('#deleteAll').modal('hide');
                    }

                    IDArray = [];
                    $("input:checkbox[name=chkBox]:checked").each(function () {
                        $(this).prop('checked', false);
                    });
                    $('.event').attr('disabled', 'disabled');

                },
                fail: function (e) {
                    alert(e);
                }
            });
        } else {
            alert('{{__('cp.not_selected')}}');
        }
    });

    function readURL(input, target) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                target.attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }


    $('#toolsTable').DataTable({
        pageLength: 20,
        dom: 'Bfrtip',
        searching: true,
        "oLanguage": {
            "sSearch": "{{__('cp.search')}}"
        },
        bInfo: true, //Dont display info e.g. "Showing 1 to 4 of 4 entries"
        paging: true,//Dont want paging
        bPaginate: true,//Dont want paging
        responsive: true,
        buttons: [
            'copy', 'excel', 'pdf', 'print'
        ]
    });
    
    $('#toolsTable1').DataTable({
        dom: 'Bfrtip',
        searching: false,
        "oLanguage": {
            "sSearch": "{{__('cp.search')}}"
        },
        bInfo: true, //Dont display info e.g. "Showing 1 to 4 of 4 entries"
        paging: false,//Dont want paging
        bPaginate: false,//Dont want paging
        buttons: [
           
        ]
    });
    
    $('.btn--filter').click(function () {
        $('.box-filter-collapse').slideToggle(500);
    });

  function readURLMultiple(input, target) {
        if (input.files) {
            var filesAmount = input.files.length;
            for (var i = 0; i < filesAmount; i++){
                var reader = new FileReader();
                reader.onload = function (event){
                    target.append('<div class="imageBox text-center" style="width:150px;height:190px;margin:5px"><img src="'+event.target.result+'" style="width:150px;height:150px"><button class="btn btn-danger deleteImage" type="button">{{__("cp.remove")}}</button><input class="attachedValues" type="hidden" name="filename[]" value="'+event.target.result+'"></div>');
                };
                reader.readAsDataURL(input.files[i]);
            }
        }
    }
    
    $(document).on("click", ".deleteImage", function () {
        $(this).parent().remove();
    });

</script>
<script>
$("[name=checkAll]").click(function(){
       $('.event').removeAttr('disabled');
    $('.checkboxes').not(this).prop('checked', this.checked);
});
</script>

@yield('script')
</body>

</html>
