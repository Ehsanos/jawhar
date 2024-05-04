@extends('layout.adminLayout')
@section('title') {{__('ارباح التركي يدوي')}}
@endsection
@section('css')
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-settings font-dark"></i>
                        <span class="caption-subject font-dark sbold uppercase"
                              style="color: #e02222 !important;">{{__('cp.add')}}</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <form method="post" action="{{url(app()->getLocale().'/admin/profit_tr/store')}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form" id="form_city">
                        {{ csrf_field() }}

                        <div class="form-body">

                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label ">
                                        {{__('cp.username')}}
                                    </label>
                                    <div class="col-md-6">
                                        <select name="user_id" class="js-example-basic-single form-control" required>
                                            @foreach($user as $one)
                                                <option value="{{$one->id}}" data-code="{{$one->user_code}}">{{$one->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label ">
                                        {{__('cp.city')}}
                                    </label>
                                    <div class="col-md-6">
                                        <select name="city_id" class="form-control" required>
                                            <option value="">{{ __('cp.select') }}</option>
                                            @foreach($cities as $one)
                                                <option value="{{$one->id}}">{{$one->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group">
                                        <label class="col-sm-2 control-label ">
                                            {{__('نوع الخدمة')}}
                                        </label>
                                        <div class="col-md-6">
                                        <?php $serv_array = getAllServicesName_Profit(); ?>
                                        <select id="service_name" class="form-control select"
                                                name="service_name" required >
                                            <option value="">{{__('cp.select')}}</option>
                                            @foreach($serv_array as $one_serv)
                                                <option value="{{$loop->index}}" {{ request()->service_name != null && request()->service_name == $loop->index ? 'selected' : '' }}>
                                                    {{__($one_serv)}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('cp.details')}}
                                    </label>
                                    <div class="col-md-6">
                                        <textarea class="form-control" name="details"
                                                  placeholder=" {{__('cp.details')}}"
                                                  style="height: 100px" required>{{ old('details') }}</textarea>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label ">
                                        {{__('  الربح')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input id="profit" name="profit" class="form-control" type="number" step="0.01" required
                                               placeholder=" {{__('الربح')}}"
                                               value="{{ old('profit') }}" >
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label ">
                                        {{__('سعر الشراء')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input id="Purchasing_price" name="purchasing_price" class="form-control" type="number" step="0.01"
                                               placeholder=" {{__('سعر الشراء')}}"
                                               value="{{0}}" >
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label ">
                                        {{__(' الموظفيين')}}
                                    </label>
                                    <div class="col-md-6">
                                        <select id="worker_id" class="form-control select"
                                                name="worker_id">
                                            <option value="0">{{__('cp.select')}}</option>
                                            @foreach($workers as $one)
                                                <option value="{{$one->user_id}}">{{$one->user->name}} - {{$one->phone}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label ">
                                        {{__('ربح العمل')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input id="worker_profit" name="worker_profit" class="form-control" type="number" step="0.01"
                                               placeholder=" {{__('ربح العمل')}}"
                                               value="{{0}}" >
                                    </div>
                                </div>
                            </fieldset>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">{{__('cp.add')}}</button>
                                        <a href="{{url(getLocal().'/admin/profit_tr/store')}}" class="btn default">{{__('cp.cancel')}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')

@endsection

@section('script')

    <script>

        $(document).on('change', '#status', function (e) {

            doChange();

        });

        function doChange(){

            var option = $("#status option:selected").val();
            if (option == 1) {
                $("#percent").show(300);
            }
            if (option == 0) {
                $("#percent").hide(300);
            }
        }

        $(document).ready(function() {
            $('.js-example-basic-single').select2({
                dir: "rtl",
                matcher: matchCustom
            })

        });

        function matchCustom(params, data) {

            if ($.trim(params.term) === '') {
                return data;
            }

            if (typeof data.text === 'undefined') {
                return null;
            }

            if (data.text.indexOf(params.term) > -1 || data.element.getAttribute('data-code').indexOf(params.term) > -1) {
                var matchedData = $.extend({}, data, true);
                return matchedData;
            }

            return null;
        }

    </script>



@endsection

