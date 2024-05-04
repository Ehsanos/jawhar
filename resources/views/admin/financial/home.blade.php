@extends('layout.adminLayout')
@section('title') {{ucwords(__('cp.financial'))}}
@endsection
@section('css')

@endsection
@section('content')
    <div class="row">

        <div class="col-sm-12" style="text-align: center;padding: 10px">
        <a href="
        @if(isset(request()->currency) && request()->currency == "dollar")
            {{url(app()->getLocale().'/admin/financial')}}
        @else
            {{url(app()->getLocale().'/admin/financial?currency=dollar')}}
        @endif
        " type="submit"
       class="btn sbold btn-default ">
        @if(isset(request()->currency) && request()->currency == "dollar")
            العملة تركي
        @else
            العملة دولار
        @endif
        <i class="fa fa-refresh"></i>
    </a>
        </div>
    </div>
                <div class="table-toolbar" style="background: white;">

                <div class="row">

                    <div class="col-sm-9">

                        <div class="btn-group">

                            <button class="btn sbold blue btn--filter">{{__('cp.filter')}}

                                <i class="fa fa-search"></i>

                            </button>
                            

                        </div>

                    </div>



                </div>

                <div class="box-filter-collapse">

                    <form class="form-horizontal" method="get" action="{{url(getLocal().'/admin/financial')}}">

                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group row">
                                        <label class="col-md-3 control-label">{{__('cp.date_from')}}</label>
                                        <div class="col-md-9">
                                                   <div class="input-group  date date-picker" data-date-format="yyyy-mm-dd">
                                            <input type="text"  class="form-control" name="date_from" value="{{ old('date_from',request()->get('date_from')) }}">
                                            <span class="input-group-btn">
                                                <button class="btn default" type="button">
                                                    <i class="fa fa-calendar"></i>
                                                </button>
                                            </span>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group row">
                                        <label class="col-md-3 control-label">{{__('cp.date_to')}}</label>
                                        <div class="col-md-9">
                                                   <div class="input-group  date date-picker" data-date-format="yyyy-mm-dd">
                                            <input type="text"  class="form-control" name="date_to" value="{{ old('date_to',request()->get('date_to')) }}">
                                            <span class="input-group-btn">
                                                <button class="btn default" type="button">
                                                    <i class="fa fa-calendar"></i>
                                                </button>
                                            </span>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="submit" class="btn sbold blue">{{__('cp.search')}}
                                                <i class="fa fa-search"></i>
                                            </button>
                                            <a href="{{url(app()->getLocale().'/admin/financial')}}" type="submit"
                                               class="btn sbold btn-default ">{{__('cp.reset')}}
                                                <i class="fa fa-refresh"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>


                    </form>

                </div>

            </div>
         <div class="row widget-row">
             <div class="row">
                 <div class="col-md-4">
                     <a href="{{url(getLocal().'/admin/users/1/wallet')}}">
                         <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                             <h4 class="widget-thumb-heading">رصيد جوهر</h4>
                             <div class="widget-thumb-wrap">
                                 <i class="widget-thumb-icon bg-red fa fa-money"></i>
                                 <div class="widget-thumb-body">
                                     <span class="widget-thumb-subtitle"></span>
                                     <span class="widget-thumb-body-stat" data-counter="counterup"
                                           data-value="{{$jawharstores}}">0</span>
                                 </div>
                             </div>
                         </div>
                     </a>
                 </div>
                 <div class="col-md-4">
                     <a href="{{url(getLocal().'/admin/users')}}">
                         <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                             <h4 class="widget-thumb-heading">رصيد المستخدمين</h4>
                             <div class="widget-thumb-wrap">
                                 <i class="widget-thumb-icon bg-red fa fa-users"></i>
                                 <div class="widget-thumb-body">
                                     <span class="widget-thumb-subtitle"></span>
                                     <span class="widget-thumb-body-stat" data-counter="counterup"
                                           data-value="{{$jawharUsers}}">0</span>
                                 </div>
                             </div>
                         </div>
                     </a>
                 </div>
                 <div class="col-md-4">
                     <a href="{{url(getLocal().'/admin/users/2212/wallet')}}">
                         <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                             <h4 class="widget-thumb-heading">رصيد الحساب الوهمي</h4>
                             <div class="widget-thumb-wrap">
                                 <i class="widget-thumb-icon bg-red fa fa-users"></i>
                                 <div class="widget-thumb-body">
                                     <span class="widget-thumb-subtitle"></span>
                                     <span class="widget-thumb-body-stat" data-counter="counterup"
                                           data-value="{{$ohmeuser}}">0</span>
                                 </div>
                             </div>
                         </div>
                     </a>
                 </div>
             </div>
        <div class="row">
            <div class="col-md-4">
                <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                    <h4 class="widget-thumb-heading">مجموع رصيد جوهر والمستخدمين</h4>
                    <div class="widget-thumb-wrap">
                        <i class="widget-thumb-icon bg-red fa fa-plus"></i>
                        <div class="widget-thumb-body">
                            <span class="widget-thumb-subtitle"></span>
                            <span class="widget-thumb-body-stat" data-counter="counterup"
                                  data-value="{{$userandjawhar}}">0</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                    <h4 class="widget-thumb-heading">مجموع رصيد جوهر والمستخدمين والحساب الوهمي</h4>
                    <div class="widget-thumb-wrap">
                        <i class="widget-thumb-icon bg-red fa fa-plus"></i>
                        <div class="widget-thumb-body">
                            <span class="widget-thumb-subtitle"></span>
                            <span class="widget-thumb-body-stat" data-counter="counterup"
                                  data-value="{{$userandjawhar + $ohmeuser}}">0</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                    <h4 class="widget-thumb-heading">اجمالي قيمة البطاقات المستخدمة</h4>
                    <div class="widget-thumb-wrap">
                        <i class="widget-thumb-icon bg-red fa fa-credit-card"></i>
                        <div class="widget-thumb-body">
                            <span class="widget-thumb-subtitle"></span>
                            <span class="widget-thumb-body-stat" data-counter="counterup"
                                  data-value="{{$balance_cards}}">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       <div class="row">
           <div class="col-md-4">
               <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                   <h4 class="widget-thumb-heading">قيمة الرصيد المخزن في البطقات</h4>
                   <div class="widget-thumb-wrap">
                       <i class="widget-thumb-icon bg-red fa fa-credit-card"></i>
                       <div class="widget-thumb-body">
                           <span class="widget-thumb-subtitle"></span>
                           <span class="widget-thumb-body-stat" data-counter="counterup"
                                 data-value="{{$balance_cards_price}}">0</span>
                       </div>
                   </div>
               </div>
           </div>
           <div class="col-md-4">
               <a href="{{url(getLocal().'/admin/requestRenewCard')}}">
                   <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                       <h4 class="widget-thumb-heading">{{__('cp.networksCardsRequestCount')}}</h4>
                       <div class="widget-thumb-wrap">
                           <i class="widget-thumb-icon bg-red fa fa-wifi"></i>
                           <div class="widget-thumb-body">
                               <span class="widget-thumb-subtitle"></span>
                               <span class="widget-thumb-body-stat" data-counter="counterup"
                                     data-value="{{$networksCardsRequestCount}}">0</span>
                           </div>
                       </div>
                   </div>
               </a>
           </div>
           <div class="col-md-4">
               <a href="{{url(getLocal().'/admin/gameRequest')}}">
                   <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                       <h4 class="widget-thumb-heading">{{__('cp.GameRequestCount')}}</h4>
                       <div class="widget-thumb-wrap">
                           <i class="widget-thumb-icon bg-blue fa fa-gamepad"></i>
                           <div class="widget-thumb-body">
                               <span class="widget-thumb-subtitle"></span>
                               <span class="widget-thumb-body-stat" data-counter="counterup"
                                     data-value="{{$GameRequestCount}}">0</span>
                           </div>
                       </div>
                   </div>
               </a>
           </div>
       </div>
       <div class="row">
           <div class="col-md-4">
               <a href="{{url(getLocal().'/admin/publicServicesRequest')}}">
                   <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                       <h4 class="widget-thumb-heading">{{__('cp.publicServicesRequestCount')}}</h4>
                       <div class="widget-thumb-wrap">
                           <i class="widget-thumb-icon bg-blue fa fa-life-ring"></i>
                           <div class="widget-thumb-body">
                               <span class="widget-thumb-subtitle"></span>
                               <span class="widget-thumb-body-stat" data-counter="counterup"
                                     data-value="{{$publicServicesRequest}}">0</span>
                           </div>
                       </div>
                   </div>
               </a>
           </div>
           <div class="col-md-4">
               <a href="{{url(getLocal().'/admin/courseRequest')}}">
                   <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                       <h4 class="widget-thumb-heading">{{__('cp.courseRequestCount')}}</h4>
                       <div class="widget-thumb-wrap">
                           <i class="widget-thumb-icon bg-blue fa fa-laptop"></i>
                           <div class="widget-thumb-body">
                               <span class="widget-thumb-subtitle"></span>
                               <span class="widget-thumb-body-stat" data-counter="counterup"
                                     data-value="{{$courseRequest}}">0</span>
                           </div>
                       </div>
                   </div>
               </a>
           </div>
       </div>

    </div>
@endsection
@section('js')

@endsection

@section('script')

@endsection
