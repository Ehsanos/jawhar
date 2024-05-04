@extends('layout.adminLayout')
@section('title')   {{__('cp.stores')}}

@endsection
@section('css')

@endsection
@section('content')


    <div class="row ">
        <div class=" light bordered col-md-2 ">
            <div class="portlet-body ">
                <a href="{{url(getLocal().'/admin/stores/'.$store->id.'/edit')}}" class="icon-btn">
                    <i class="fa fa-edit"></i>
                    <div> {{ucwords(__('cp.edit'))}} </div>
                    <!--<span class="badge badge-danger"> 2 </span>-->
                </a>
                <a href="{{url(getLocal().'/admin/users/'.$store->user_id.'/edit_password')}}" class="icon-btn">
                    <i class="fa fa-edit"></i>
                    <div> {{ucwords(__('cp.edit_password'))}}  </div>
                    <!--<span class="badge badge-success"> 4 </span>-->
                </a>
                <a href="{{url(getLocal().'/admin/storeWallet/'.$store->id)}}" class="icon-btn">
                    <i class="fa fa-usd"></i>
                    <div> {{ucwords(__('cp.wallet'))}} </div>
                </a>
                @if($store->type == 1)
                    <a href="{{url(getLocal().'/admin/storeOrders/'.$store->id)}}" class="icon-btn">
                        <i class="fa fa-sitemap"></i>
                        <div> {{ucwords(__('cp.orders'))}} </div>
                    </a>


                    <a href="{{url(getLocal().'/admin/storeCategories/'.$store->id)}}" class="icon-btn">
                        <i class="fa fa-users"></i>
                        <div> {{ucwords(__('cp.categories'))}} </div>
                        <!--<span class="badge badge-success"> 4 </span>-->
                    </a>
                    <a href="{{url(getLocal().'/admin/storeProducts/'.$store->id)}}" class="icon-btn">
                        <i class="fa fa-users"></i>
                        <div> {{ucwords(__('cp.products'))}} </div>
                        <!--<span class="badge badge-success"> 4 </span>-->
                    </a>
                @else
                    @if(can('wifi'))
{{--                        <li class="nav-item {{(explode("/", request()->url())[5] == "wifi") ? "active open" : ''}} ">--}}
                            <a href="{{url(getLocal().'/admin/wifi/'.$store->id)}}" class="icon-btn">
                                <i class="fa fa-wifi"></i>
                                <div> {{ucwords(__('cp.wifi'))}} </div>
{{--                            <span class="title">{{__('cp.wifi')}}</span>--}}
                            </a>
{{--                        </li>--}}
                    @endif

                    @if(can('networks'))
{{--                        <li class="nav-item {{(explode("/", request()->url())[5] == "networks") ? "active open" : ''}} ">--}}
                            <a href="{{url(getLocal().'/admin/networks/'.$store->id)}}" class="icon-btn">
                                <i class="fa fa-transgender"></i>
                                <div> {{ucwords(__('cp.networks'))}} </div>
{{--                                <span class="title">{{__('cp.networks')}}</span>--}}
                            </a>
{{--                        </li>--}}
                    @endif

                        @if(can('NetworkSections'))

{{--                            <li class="nav-item">--}}
                                <a href="{{url(getLocal().'/admin/NetworkSections/'.$store->id)}}" class="icon-btn">
                                    <i class="fa fa-shopping-bag"></i>
                                    <div> {{ucwords(__('cp.NetworkSections'))}} </div>
                                </a>
{{--                            </li>--}}
                        @endif

                        @if(can('requestRenewCard'))
{{--                            <li class="nav-item {{(explode("/", request()->url())[5] == "requestRenewCard") ? "active open" : ''}} ">--}}
                                <a href="{{url(getLocal().'/admin/requestRenewCard/'.$store->id)}}" class="icon-btn">
                                    <i class="fa fa-bag"></i>
                                    <div> {{ucwords(__('cp.requestRenewCard'))}} </div>
{{--                                    <span class="title">{{__('cp.requestRenewCard')}}</span>--}}
                                    <span style="" class="badge badge-danger">{{@App\Models\RequestRenewCard::where('action',0)->count()}}</span>

                                </a>
{{--                            </li>--}}
                        @endif



                @endIf
                @if(can('news'))
                    <a href="{{url(getLocal().'/admin/news/'.$store->id)}}" class="icon-btn">
                        <i class="fa fa-file"></i>
                        <div> {{ucwords(__('cp.news'))}} </div>
                    </a>
                @endif
                @if(can('sliders'))
                    <a href="{{url(getLocal().'/admin/sliders/'.$store->id)}}" class="icon-btn">
                        <i class="fa fa-list"></i>
                        <div> {{ucwords(__('cp.sliders'))}} </div>
                    </a>
                @endif




            </div>
        </div>
        <div class="portlet light bordered col-md-10">

            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-settings font-dark"></i>
                        <span class=" font-dark sbold uppercase"
                              style="color: #e02222 !important;"> {{__('cp.storeInformation')}}</span>
                    </div>
                    <div class="col-md-12">
                        <!-- BEGIN PROFILE SIDEBAR -->
                        <!-- BEGIN PROFILE SIDEBAR -->
                        <div class="col-md-3">
                            <!-- PORTLET MAIN -->
                            <div class="portlet light profile-sidebar-portlet bordered">
                                <!-- SIDEBAR USERPIC -->
                                <div class="profile-userpic">
                                    <img src="{{@$store->logo}}" class="img-responsive" alt=""></div>
                                <!-- END SIDEBAR USERPIC -->
                                <!-- SIDEBAR USER TITLE -->
                                <div class="profile-usertitle">
                                    <h4 class="profile-desc-title font-blue-madison bold uppercase">{{$store->store_name}}</h4>
                                    <div class="profile-usertitle-job"></div>
                                </div>
                                <!-- END SIDEBAR USER TITLE -->
                                <!-- SIDEBAR BUTTONS -->
                                <div class="margin-top-20 profile-userbuttons">

                                    @if($store->type == 2)
                                        <form  method="post" action="{{url(getLocal().'/admin/NetworkSections/update_status/'.$store->id)}}"
                                               enctype="multipart/form-data" class="form-horizontal" role="form" id="form">

                                            {{ csrf_field() }}
                                            {{ method_field('PATCH')}}
                                            <h6>تحديد الموقع</h6>
                                        @if($store->status_network == 1)
                                            <button class="btn btn-circle green btn-sm" type="submit">{{ucwords(__('cp.active'))}}</button>
                                        @else
                                            <button class="btn btn-circle red btn-sm" type="submit">{{ucwords(__('cp.not_active'))}}</button>
                                        @endif
                                        </form>
                                    @endif



                                        @if($store->type == 1)
                                            <form  method="post" action="{{url(getLocal().'/admin/stores/update_status_cart/'.$store->id)}}"
                                                   enctype="multipart/form-data" class="form-horizontal" role="form" id="form">

                                                {{ csrf_field() }}
                                                {{ method_field('PATCH')}}
                                                <h6>خدمة توصيل الطلابات  </h6>
                                                @if($store->status_cart == 0)
                                                    <button class="btn btn-circle green btn-sm" type="submit">{{ucwords(__('cp.active'))}}</button>
                                                @else
                                                    <button class="btn btn-circle red btn-sm" type="submit">{{ucwords(__('cp.not_active'))}}</button>
                                                @endif
                                            </form>
                                        @endif







                                    <hr>
                                    <h6>حالة المتجر</h6>
                                    @if (@$store->status == 'active')
                                        <button class="btn btn-circle green btn-sm">{{ucwords(__('cp.active'))}}</button>
                                    @endif
                                    @if (@$store->status == 'not_active')
                                        <button class="btn btn-circle red btn-sm">{{ucwords(__('cp.not_active'))}}</button>
                                    @endif
                                    <button class="btn btn-circle blue btn-sm">{{@$store->category->name}}</button>
                                    <br>

                                </div>
                                <!-- SIDEBAR MENU -->
                                <div class="profile-usermenu">
                                    <ul class="nav">
                                        <li>
                                            <i class="margin-top-20 icon-calendar"></i> {{@$store->created_at}}
                                        </li>
                                    </ul>
                                </div>
                                <!-- END MENU -->
                            </div>
                            <!-- END PORTLET MAIN -->
                            <!-- PORTLET MAIN -->
                            <!-- END PORTLET MAIN -->
                        </div>
                        <!-- END BEGIN PROFILE SIDEBAR -->        <!-- END BEGIN PROFILE SIDEBAR -->
                        <!-- BEGIN PROFILE CONTENT -->
                        <div class="profile-content">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="portlet light bordered">
                                        <div class="portlet-body">
                                            <div class="tab-content">
                                                <!-- GENERAL QUESTION TAB -->
                                                <div class="tab-pane active" id="tab_1_1">
                                                    <div id="accordion1" class="panel-group">
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading">
                                                                <h4 class="panel-title">
                                                                    <a class="accordion-toggle accordion-toggle-styled"
                                                                       data-toggle="collapse" data-parent="#accordion1"
                                                                       href="#accordion1_1"> {{ucwords(__('cp.details'))}} {{ucwords(__('cp.store'))}}</a>
                                                                </h4>
                                                            </div>
                                                            <div lass="panel-collapse collapse in">


                                                                <div class="form-group" style="padding: 10px;">
                                                                    <div class="col-md-3 bold"> {{__('cp.mobile')}} </div>
                                                                    <div class="col-md-3"> {{@$store->mobile}} </div>

                                                                </div>

                                                                <div class="form-group" style="padding: 10px;">


                                                                    <div class="col-md-3 bold"> {{__('cp.city')}} </div>
                                                                    <div class="col-md-3">{{@$store->city->name}} </div>


                                                                    <div class="col-md-3 bold"> {{__('cp.address')}} </div>
                                                                    <div class="col-md-3">{{@$store->address}} </div>

                                                                </div>

                                                                <div class="form-group" style="padding: 10px;">


                                                                    <div class="col-md-3 bold"> {{__('cp.details')}} </div>
                                                                    <div class="col-md-6">{{@$store->details}} </div>


                                                                </div>


                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                                <!-- END GENERAL QUESTION TAB -->
                                                <!-- MEMBERSHIP TAB -->


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END PROFILE CONTENT -->
                    </div>

                </div>

            </div>


        </div>

    </div>


    <div class="row">
    </div>

@endsection
@section('js')
@endsection
@section('script')

@endsection
