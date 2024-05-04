@extends('layout.adminLayout')
@section('title') {{ucwords(__('cp.dashboard'))}}
@endsection
@section('css')

@endsection
@section('content')
    @php
        $admin = auth('admin')->user();
    @endphp
    @if($admin->id== 1)

    <div class="row widget-row">
        <div class="col-md-4">
            <!-- BEGIN WIDGET THUMB -->
            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                <h4 class="widget-thumb-heading">{{__('cp.users')}}</h4>
                <div class="widget-thumb-wrap">
                    <i class="widget-thumb-icon bg-blue fa fa-user"></i>
                    <div class="widget-thumb-body">
                        <span class="widget-thumb-subtitle"></span>
                        <span class="widget-thumb-body-stat" data-counter="counterup"
                              data-value="{{$users_count}}">{{$users_count+10000}}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                <h4 class="widget-thumb-heading">{{__('cp.orders')}}</h4>
                <div class="widget-thumb-wrap">
                    <i class="widget-thumb-icon bg-red fa fa-server"></i>
                    <div class="widget-thumb-body">
                        <span class="widget-thumb-subtitle"></span>
                        <span class="widget-thumb-body-stat" data-counter="counterup"
                              data-value="{{$count_orders}}">{{$count_orders}}</span>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <!-- BEGIN WIDGET THUMB -->
            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                <h4 class="widget-thumb-heading">{{__('cp.chat')}}</h4>
                <div class="widget-thumb-wrap">
                    <i class="widget-thumb-icon bg-purple fa fa-comments-o"></i>
                    <div class="widget-thumb-body">
                        <span class="widget-thumb-subtitle"></span>
                        <span class="widget-thumb-body-stat" data-counter="counterup"
                              data-value="{{$count_chat}}">{{$count_chat}}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <!-- BEGIN WIDGET THUMB -->
            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                <h4 class="widget-thumb-heading">{{__('cp.contact')}}</h4>
                <div class="widget-thumb-wrap">
                    <i class="widget-thumb-icon bg-blue fa fa-envelope-o"></i>
                    <div class="widget-thumb-body">
                        <span class="widget-thumb-subtitle"></span>
                        <span class="widget-thumb-body-stat" data-counter="counterup"
                              data-value="{{$contact}}">{{$contact}}</span>
                    </div>
                </div>
            </div>
            <!-- END WIDGET THUMB -->
        </div>
        <div class="col-md-4">
            <!-- BEGIN WIDGET THUMB -->
            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                <h4 class="widget-thumb-heading">{{__('cp.requestMobileBalance')}}</h4>
                <div class="widget-thumb-wrap">
                    <i class="widget-thumb-icon bg-purple fa fa-mobile"></i>
                    <div class="widget-thumb-body">
                        <span class="widget-thumb-subtitle"></span>
                        <span class="widget-thumb-body-stat" data-counter="counterup"
                              data-value="{{$request_mobil_count}}">{{$request_mobil_count}}</span>
                    </div>
                </div>
            </div>
            <!-- END WIDGET THUMB -->
        </div>
        <div class="col-md-4">
            <!-- BEGIN WIDGET THUMB -->
            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                <h4 class="widget-thumb-heading">{{__('cp.requestRenewCard')}}</h4>
                <div class="widget-thumb-wrap">
                    <i class="widget-thumb-icon bg-green fa fa-mobile"></i>
                    <div class="widget-thumb-body">
                        <span class="widget-thumb-subtitle"></span>
                        <span class="widget-thumb-body-stat" data-counter="counterup"
                              data-value="{{$renew_card_count}}">{{$renew_card_count}}</span>
                    </div>
                </div>
            </div>
            <!-- END WIDGET THUMB -->
        </div>
        <div class="col-md-4">
            <!-- BEGIN WIDGET THUMB -->
            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                <h4 class="widget-thumb-heading">{{__('cp.gameRequest')}}</h4>
                <div class="widget-thumb-wrap">
                    <i class="widget-thumb-icon bg-green fa fa-mobile"></i>
                    <div class="widget-thumb-body">
                        <span class="widget-thumb-subtitle"></span>
                        <span class="widget-thumb-body-stat" data-counter="counterup"
                              data-value="{{$gameRequest}}">{{$gameRequest}}</span>
                    </div>
                </div>
            </div>
            <!-- END WIDGET THUMB -->
        </div>
        <div class="col-md-4">
            <!-- BEGIN WIDGET THUMB -->
            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                <h4 class="widget-thumb-heading">{{__('cp.publicServicesRequest')}}</h4>
                <div class="widget-thumb-wrap">
                    <i class="widget-thumb-icon bg-green fa fa-mobile"></i>
                    <div class="widget-thumb-body">
                        <span class="widget-thumb-subtitle"></span>
                        <span class="widget-thumb-body-stat" data-counter="counterup"
                              data-value="{{$publicServicesRequest}}">{{$publicServicesRequest}}</span>
                    </div>
                </div>
            </div>
            <!-- END WIDGET THUMB -->
        </div>
        <div class="col-md-4">
            <!-- BEGIN WIDGET THUMB -->
            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                <h4 class="widget-thumb-heading">{{__('cp.courseRequest')}}</h4>
                <div class="widget-thumb-wrap">
                    <i class="widget-thumb-icon bg-green fa fa-mobile"></i>
                    <div class="widget-thumb-body">
                        <span class="widget-thumb-subtitle"></span>
                        <span class="widget-thumb-body-stat" data-counter="counterup"
                              data-value="{{$courseRequest}}">{{$courseRequest}}</span>
                    </div>
                </div>
            </div>
            <!-- END WIDGET THUMB -->
        </div>
    </div>

    <div class="row widget-row">

        <div class="col-md-6">
            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                <div class="widget-thumb-wrap">
                    <i class="widget-thumb-icon bg-red fa fa-money"></i>
                    <div class="widget-thumb-body">
                        <span class="widget-thumb-body-stat" data-value="">{{__('cp.UsedBalanceCard')}}</span>
                 <span>
                 
          <table class="table table-striped table-bordered table-hover" >
                <thead>
               <tr>
                    <th style="width:40%" > {{ucwords(__('cp.date'))}}</th>
                    <th> {{ucwords(__('cp.price'))}}</th>
                    <th> {{ucwords(__('cp.user'))}}</th>
                    <th> {{ucwords(__('cp.cards'))}}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($newbalanceCards as $item)
                       </tr>
                        <td style="width:20%"> {{@$item->created_at}}</td>
                        <td> {{@$item->total_price}}</td>
                        <td> {{@$item->user->name}}</td>
                         <td>{{isset(explode(':',@$item->details)[1])? explode(':',@$item->details)[1]:""}} </td>
                    </tr>

                @empty
                    {{__('cp.no')}}
                @endforelse
                </tbody>
            </table>

                 </span>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                <div class="widget-thumb-wrap">
                    <i class="widget-thumb-icon bg-blue fa fa-server"></i>
                    <div class="widget-thumb-body">
                        <span class="widget-thumb-body-stat" data-value="">إحصائيات</span>
                                <span>
                 
          <table class="table table-striped table-bordered table-hover" >
                <thead>
               <tr>
                    <th style="width:40%" > اسم الخدمة </th>
                    <th> العدد المتوفر</th>
                </tr>
                </thead>
                <tbody>
                                                        @forelse($service_count as $item2)
                            
                       <tr>
                        <td style="width:60%"> {{@$item2->productService->name}} - {{@$item2->name}} <br> </td>
                        <td>{{@$item2->service_cards_count}} </td>
                    </tr>
                    
                @empty
                    {{__('cp.no')}}
                @endforelse
                                    @forelse($networks_count as $item)
                                    @if($item->networks_cards_count < 10)
                       <tr>
                        <td style="width:60%"> {{@$item->wifiName->name}} <br> {{@$item->name}}</td>
                        <td>{{@$item->networks_cards_count}} </td>
                    </tr>
                                @endIf
                @empty
                    {{__('cp.no')}}
                @endforelse

                </tbody>
            </table>

                 </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endif
@endsection
@section('js')

@endsection

@section('script')

@endsection
