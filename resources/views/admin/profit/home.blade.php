@extends('layout.adminLayout')
@section('title') {{ucwords(__('المالية تركي'))}}
@endsection
@section('content')
    <form method="get" action="{{route('admin.profit.home')}}">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <br>
                الوضع
                <select id="type" class="form-control select"
                        name="type" style="width: 100%;">
                    <option value="0" {{ request()->type == "0" ? 'selected' : '' }}>الأرباح</option>
                    <option value="1" {{ request()->type == "1" ? 'selected' : '' }}>المصاريف</option>
                </select>
                العملة
                <a style="width: 100%;" href="
                    @if(isset(request()->currency) && request()->currency == "dollar")
                        {{url(getLocal().'/admin/profit?type='.request()->type)}}
                    @else
                        {{url(getLocal().'/admin/profit?type='.request()->type.'&currency=dollar')}}
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
                <input id="currency" name="currency" class="form-control" type="hidden"
                       value="{{request()->currency}}" >
                <br>
                <br>
                <br>
                <br>

            </div>
            <div class="col-md-4"></div>
        </div>
        @if(request()->type == 0)
        <div class="row">
            <div class="col-md-2">
                من
                <input readonly id="datetimepicker1" name="start" class="form-control" type="text"
                       value="{{request()->start}}">
            </div>
            <div class="col-md-2">
                الى
                <input readonly id="datetimepicker2" name="end" class="form-control" type="text"
                       value="{{request()->end}}">
            </div>
            <div class="col-md-2">
                المدن
                <select id="city_id" class="form-control select"
                        name="city_id">
                    <option value="" {{ request()->city_id == null || request()->city_id == "" ? 'selected' : '' }}>{{__('cp.all')}}</option>
                    @foreach($data["cities"] as $city)
                        <option value="{{$city->city_id}}" {{ request()->city_id == $city->city_id ? 'selected' : '' }}>
                            {{$city->city_name()}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                الموظفيين
                <select id="worker_id" class="form-control select"
                        name="worker_id">
                    <option value="" {{ request()->worker_id == null || request()->worker_id == "" ? 'selected' : '' }}>{{__('cp.all')}}</option>
                    @foreach($data["workers"] as $worker)
                        <option value="{{$worker->worker_id}}" {{ request()->worker_id == $worker->worker_id ? 'selected' : '' }}>
                            {{$worker->worker_name()}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                نوع الخدمة
                <?php $serv_array = getAllServicesName_Profit(); ?>
                <select id="service_name" class="form-control select"
                        name="service_name">
                    <option value="" {{ request()->service_name == null ||  request()->service_name == "" ? 'selected' : '' }}>{{__('cp.all')}}</option>
                        @foreach($serv_array as $one_serv)
                            <option value="{{$loop->index}}" {{ request()->service_name != null && request()->service_name == $loop->index ? 'selected' : '' }}>
                                {{__($one_serv)}}
                            </option>
                        @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <br>
                <button onclick="return check_form()" style="width: 100%" class="btn sbold blue btn--filter">فلترة
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
        @elseif(request()->type == 1)
        <div class="row">
                <div class="col-md-2">
                    من
                    <input readonly id="datetimepicker1" name="start" class="form-control" type="text"
                           value="{{request()->start}}">
                </div>
                <div class="col-md-2">
                    الى
                    <input readonly id="datetimepicker2" name="end" class="form-control" type="text"
                           value="{{request()->end}}">
                </div>
                <div class="col-md-2">
                    المدن
                    <select id="city_id" class="form-control select"
                            name="city_id">
                        <option value="" {{ request()->city_id == null || request()->city_id == "" ? 'selected' : '' }}>{{__('cp.all')}}</option>
                        @foreach($data["cities"] as $city)
                            <option value="{{$city->city_id}}" {{ request()->city_id == $city->city_id ? 'selected' : '' }}>
                                {{$city->city_name()}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    الموظفيين
                    <select id="worker_id" class="form-control select"
                            name="worker_id">
                        <option value="" {{ request()->worker_id == null || request()->worker_id == "" ? 'selected' : '' }}>{{__('cp.all')}}</option>
                        @foreach($data["workers"] as $worker)
                            <option value="{{$worker->worker_id}}" {{ request()->worker_id == $worker->worker_id ? 'selected' : '' }}>
                                {{$worker->worker_name()}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    نوع الخدمة
                    <?php $serv_array = getAllServicesName_Expenses(); ?>
                    <select id="service_name" class="form-control select"
                            name="service_name">
                        <option value="" {{ request()->service_name == null ||  request()->service_name == "" ? 'selected' : '' }}>{{__('cp.all')}}</option>
                        @foreach($serv_array as $one_serv)
                            <option value="{{$loop->index}}" {{ request()->service_name != null && request()->service_name == $loop->index ? 'selected' : '' }}>
                                {{__($one_serv)}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <br>
                    <button onclick="return check_form()" style="width: 100%" class="btn sbold blue btn--filter">فلترة
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        @endif
    </form>
    <br>
    @if(request()->type == 0)
        <div class="row">
        <div class="col-md-3">
            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                <h4 class="widget-thumb-heading">
                    ربح جوهر
                    @if(!is_null(request()->city_id) && !empty(request()->city_id))
                       - {{ \App\Models\City::where("id",request()->city_id)->first()->name  }}
                    @endif
                    @if(!is_null(request()->service_name))
                        - {{ getServicesName(request()->service_name,request()->type) }}
                    @endif
                    @if(!is_null(request()->start) && !empty(request()->start))
                        <?php
                        $a1 = "من";
                        $start = Carbon\Carbon::createFromFormat('m/d/Y', request()->start)->format('Y-m-d');
                        $a2 = "الى";
                        $end = Carbon\Carbon::createFromFormat('m/d/Y', request()->end)->format('Y-m-d');
                        ?>
                        - {{ $a1." ".$start." ".$a2." ".$end }}
                    @endif
                </h4>
                <div class="widget-thumb-wrap">
                    <i class="widget-thumb-icon bg-red fa fa-money"></i>
                    <div class="widget-thumb-body">
                        <span class="widget-thumb-subtitle"></span>
                        <span class="widget-thumb-body-stat"
                              data-value="">{{$data["profit_sum"]-($expenses["profit_sum"] ?? 0)}}</span>

                    </div>
                </div>
            </div>
        </div>
            <div class="col-md-3">
                <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                    <h4 class="widget-thumb-heading">
                        مصروف جوهر
                        @if(!is_null(request()->city_id) && !empty(request()->city_id))
                            - {{ \App\Models\City::where("id",request()->city_id)->first()->name  }}
                        @endif
                        @if(!is_null(request()->service_name))
                            - {{ getServicesName(request()->service_name,request()->type) }}
                        @endif
                        @if(!is_null(request()->start) && !empty(request()->start))
                            <?php
                            $a1 = "من";
                            $start = Carbon\Carbon::createFromFormat('m/d/Y', request()->start)->format('Y-m-d');
                            $a2 = "الى";
                            $end = Carbon\Carbon::createFromFormat('m/d/Y', request()->end)->format('Y-m-d');
                            ?>
                            - {{ $a1." ".$start." ".$a2." ".$end }}
                        @endif
                    </h4>
                    <div class="widget-thumb-wrap">
                        <i class="widget-thumb-icon bg-red fa fa-money"></i>
                        <div class="widget-thumb-body">
                            <span class="widget-thumb-subtitle"></span>
                            <span class="widget-thumb-body-stat"
                                  data-value="">{{$expenses["profit_sum"]}}</span>
                        </div>
                    </div>
                </div>
            </div>
        <div class="col-md-3">
            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                <h4 class="widget-thumb-heading">
                    رأسمال جوهر
                    @if(!is_null(request()->city_id) && !empty(request()->city_id))
                        - {{ \App\Models\City::where("id",request()->city_id)->first()->name  }}
                    @endif
                    @if(!is_null(request()->service_name))
                        - {{ getServicesName(request()->service_name,request()->type) }}
                    @endif
                    @if(!is_null(request()->start) && !empty(request()->start))
                        <?php
                        $a1 = "من";
                        $start = Carbon\Carbon::createFromFormat('m/d/Y', request()->start)->format('Y-m-d');
                        $a2 = "الى";
                        $end = Carbon\Carbon::createFromFormat('m/d/Y', request()->end)->format('Y-m-d');
                        ?>
                        - {{ $a1." ".$start." ".$a2." ".$end }}
                    @endif
                </h4>
                <div class="widget-thumb-wrap">
                    <i class="widget-thumb-icon bg-red fa fa-money"></i>
                    <div class="widget-thumb-body">
                        <span class="widget-thumb-subtitle"></span>
                        <span class="widget-thumb-body-stat"
                              data-value="">{{$data["capital_sum"]}}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                <h4 class="widget-thumb-heading">
                    @if(!is_null(request()->worker_id) && !empty(request()->worker_id))
                        <?php
                        $uname = \App\Models\User::where("id",request()->worker_id)->first()->name;
                        ?>
                        {{  $uname  }}
                    @else
                        ربح الموظفيين
                    @endif
                    @if(!is_null(request()->city_id) && !empty(request()->city_id))
                        - {{ \App\Models\City::where("id",request()->city_id)->first()->name  }}
                    @endif
                    @if(!is_null(request()->service_name))
                        - {{ getServicesName(request()->service_name,request()->type) }}
                    @endif
                    @if(!is_null(request()->start) && !empty(request()->start))
                        <?php
                        $a1 = "من";
                        $start = Carbon\Carbon::createFromFormat('m/d/Y', request()->start)->format('Y-m-d');
                        $a2 = "الى";
                        $end = Carbon\Carbon::createFromFormat('m/d/Y', request()->end)->format('Y-m-d');
                        ?>
                        - {{ $a1." ".$start." ".$a2." ".$end }}
                    @endif
                </h4>
                <div class="widget-thumb-wrap">
                    <i class="widget-thumb-icon bg-red fa fa-money"></i>
                    <div class="widget-thumb-body">
                        <span class="widget-thumb-subtitle"></span>
                        <span class="widget-thumb-body-stat"
                              data-value="">{{$data["profit_worker_sum"]}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @elseif(request()->type == 1)
        <div class="row">
            <div class="col-md-6">
                <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                    <h4 class="widget-thumb-heading">
                        @if(!is_null(request()->worker_id) && !empty(request()->worker_id))
                            <?php
                            $uname = \App\Models\User::where("id",request()->worker_id)->first()->name;
                            ?>
                            {{  $uname  }}
                        @else
                            متجر جوهر
                        @endif
                        @if(!is_null(request()->city_id) && !empty(request()->city_id))
                            - {{ \App\Models\City::where("id",request()->city_id)->first()->name  }}
                        @endif
                        @if(!is_null(request()->service_name))
                            - {{ getServicesName(request()->service_name,request()->type) }}
                        @endif
                        @if(!is_null(request()->start) && !empty(request()->start))
                            <?php
                            $a1 = "من";
                            $start = Carbon\Carbon::createFromFormat('m/d/Y', request()->start)->format('Y-m-d');
                            $a2 = "الى";
                            $end = Carbon\Carbon::createFromFormat('m/d/Y', request()->end)->format('Y-m-d');
                            ?>
                            - {{ $a1." ".$start." ".$a2." ".$end }}
                        @endif
                    </h4>
                    <div class="widget-thumb-wrap">
                        <i class="widget-thumb-icon bg-red fa fa-money"></i>
                        <div class="widget-thumb-body">
                            <span class="widget-thumb-subtitle"></span>
                            <span class="widget-thumb-body-stat"
                                  data-value="">{{$data["profit_sum"]}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <br>
    @if(request()->type == 0)
    <form method="post" action="{{route('admin.profit.reset')}}">
        {{ csrf_field() }}

        <input id="profit_id" name="profit_id" class="form-control" type="hidden"
                value="@forelse($data["profit"] as $item){{$item->id.","}}@endforeach" >

        <input id="expenses_id" name="expenses_id" class="form-control" type="hidden"
               value="@forelse($expenses["profit"] as $item){{$item->id.","}}@endforeach" >

        <input id="profit_totel" name="profit_totel" class="form-control" type="hidden"
               value="{{$data["profit_sum"]-($expenses["profit_sum"] ?? 0)}}" >

        <input id="profit_capital" name="profit_capital" class="form-control" type="hidden"
               value="{{$data["capital_sum"]}}" >

        <input id="profit_worker" name="profit_worker" class="form-control" type="hidden"
               value="{{$data["profit_worker_sum"]}}" >

        <div class="row">
            <div class="col-md-4"></div>
         <div class="col-md-4">
    <button type="submit" style="width: 100%" class="btn sbold blue btn--filter"
    @if($data["profit_sum"] - $expenses["profit_sum"] <= 0)
        disabled
        @endif
    >تصفير
        <i class="fa fa-dollar"></i>
    </button>
        <br>
    </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-4"></div>
        <div class="col-md-4">
                <fieldset>
                    <div class="form-group">
                        <label class=" control-label">
                            {{__('سعر التصريف')}}
                        </label>
                    </div>
                </fieldset>
        </div>
        </div>
        <div class="row">
            <div class="col-md-4"></div>
        <div class="col-md-4">
            <fieldset>
                <div class="form-group">
                    <div class="">
                        <input id="is_dollar" name="is_dollar" class="form-control" type="number" step="0.01" required
                               placeholder=" {{__('سعر النصريف')}}"
                               value="{{ old('is_dollar') }}" >
                    </div>
                </div>
            </fieldset>
        </div>
        </div>
    </form>
    @endif
    <br>
    <br>
    <table class="table table-striped table-bordered table-checkable order-column" id="toolsTable">
        <thead>
        @if(request()->type == 0)
        <tr>
            <th>#</th>
            <th> {{ucwords(__('cp.username'))}} </th>
            <th> {{ucwords(__('cp.city'))}} </th>
            <th> {{ucwords(__('cp.service'))}} </th>
            <th style="width: 200px"> {{ucwords(__('cp.details'))}} </th>
            <th> {{ucwords(__('الربح'))}} </th>
            <th> {{ucwords(__('cp.purchasing_price'))}} </th>
            <th> {{ucwords(__('العامل'))}} </th>
            <th> {{ucwords(__('ربح العامل'))}} </th>
            <th> {{ucwords(__('cp.created'))}} </th>
        </tr>
        @elseif(request()->type == 1)
            <tr>
                <th>#</th>
                <th> {{ucwords(__('cp.addBy'))}} </th>
                <th> {{ucwords(__('cp.city'))}} </th>
                <th> {{ucwords(__('cp.service'))}} </th>
                <th style="width: 200px"> {{ucwords(__('cp.details'))}} </th>
                <th> {{ucwords(__('المصروف'))}} </th>
                <th> {{ucwords(__('العامل'))}} </th>
                <th> {{ucwords(__('cp.created'))}} </th>
            </tr>
        @endif
        </thead>

        <tbody>
        @forelse($data_all["profit"] as $item)
            @if(request()->type == 0)
                <tr  @if($item->status_wellet==1)
                     style="background-color:#FF0000"
                     @endif
                     class="odd gradeX" id="tr-{{$item->id}}">
                <td>{{$loop->index+1}}</td>
                    <td>
                    @if($item->user != null)
                        {{$item->user->name}}
                    @else
                        لا يوجد مستخدم
                    @endif
                    </td>
                <td> {{$item->city->name}}</td>
                <td> {{getServicesName($item->service_name,request()->type)}}</td>
                <td> {{$item->details}}</td>
                <td> {{number_format($item->profit, 2, '.', '')}}</td>
                <td> {{number_format($item->purchasing_price, 2, '.', '')}}</td>
                <td> {{$item->worker_name()}}</td>
                <td> {{number_format($item->worker_profit, 2, '.', '') == 0 ? "" : number_format($item->worker_profit, 2, '.', '')}}</td>
                <td style="direction: ltr"> {{$item->created_at}}</td>

            </tr>
            @elseif(request()->type == 1)
                <tr class="odd gradeX" id="tr-{{$item->id}}">
                <td>{{$loop->index+1}}</td>
                    @if($item->user != null)
                        <td> {{$item->user->name}}</td>
                    @endif
                <td> {{$item->city->name}}</td>
                <td> {{getServicesName($item->service_name,request()->type)}}</td>
                <td> {{$item->details}}</td>
                <td> {{number_format($item->profit, 2, '.', '')}}</td>
                <td> {{$item->worker_name()}}</td>
                <td style="direction: ltr"> {{$item->created_at}}</td>

            </tr>
            @endif
        @empty
        @endforelse
        </tbody>

    </table>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            $("#type").change(function() {
                window.location = "{{url(getLocal().'/admin/profit?type=')}}" + $(this).val() + "&currency={{request()->currency}}";
            });
            $('#datetimepicker1').datepicker({
                clearBtn: true,
                autoclose: true,
                {{getLocal() == "ar" ? " rtl: true" : ""}}
            });
            $('#datetimepicker2').datepicker({
                clearBtn: true,
                autoclose: true,
                {{getLocal() == "ar" ? " rtl: true" : ""}}
            });
        });

        function check_form() {
            if ($("#datetimepicker1").val() != "") {
                if ($("#datetimepicker2").val() == "") {
                    alert("الرجاء تحديد البداية و النهاية");
                    return false;
                }
            }
            return true;
        }
    </script>
@endsection
