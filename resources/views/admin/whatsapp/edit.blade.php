@extends('layout.adminLayout')
@section('title') {{__('cp.games')}}
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
                              style="color: #e02222 !important;">{{__('cp.edit')}}</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <form method="post" action="{{url(app()->getLocale().'/admin/Whatsapp/'.$whatsapp->id)}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form" id="form_city">
                        {{ csrf_field() }}
                        {{ method_field('PATCH')}}

                        <div class="form-body">
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('cp.phone')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="phone"
                                               placeholder=" {{__('cp.phone')}}"
                                               value="{{ old('phone',$whatsapp->phone) }}">
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('cp.status')}}
                                    </label>
                                    <div class="col-md-6">
                                        <select id="status" class="form-control "
                                                name="status">
                                            <option value="0" {{ old('status',$whatsapp->status) == "0" ? "selected" :"" }}>بدون</option>
                                            <option value="1" {{ old('status',$whatsapp->status) == "1" ? "selected" :"" }}>نسبة</option>

                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset id="percent" style="display: {{ old('status',$whatsapp->status) == "1" ? "block" :"none" }};">
                                <div class="form-group">

                                    <label class="col-sm-2 control-label" for="order">
                                        {{'نسبة'}}
                                    </label>

                                    <div class="col-md-6">
                                        <input type="number" class="form-control" name="percent"
                                               placeholder="{{'نسبة'}}"
                                               value="{{ old('percent',$whatsapp->percent) }}">
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label ">
                                        {{__('cp.username')}}
                                    </label>
                                    <div class="col-md-6">
                                        <select name="user_id" class="js-example-basic-single form-control" required>
                                            @foreach($user as $one)
                                                <option value="{{$one->id}}" data-code="{{$one->user_code}}" {{ old('user_id',$whatsapp->user_id) == $one->id ? "selected" :"" }}>{{$one->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">{{__('cp.edit')}}</button>
                                        <a href="{{url(getLocal().'/admin/Whatsapp')}}" class="btn default">{{__('cp.cancel')}}</a>
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

