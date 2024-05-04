@extends('layout.adminLayout')
@section('title') {{__('مصروف دولار')}}
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
                    <form method="post" action="{{url(app()->getLocale().'/admin/Expenses_dollar/store')}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form" id="form_city">
                        {{ csrf_field() }}

                        <div class="form-body">
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
                                    <label class="col-sm-2 control-label">
                                        {{__('نوع المصروف')}}
                                    </label>
                                    <div class="col-md-6">
                                        <select id="service_name" class="form-control select" required
                                                name="service_name">
                                            <option value="">{{ __('cp.select') }}</option>
                                            @for($i=0;$i<=3;$i++)
                                                <option value="{{$i}}">{{ getServicesName_Expenses($i) }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </fieldset>




{{--                            <fieldset>--}}
{{--                                <div class="form-group">--}}
{{--                                    <label class="col-sm-2 control-label">--}}
{{--        {{__('مصروف')}}                                               --}}
{{--                                    </label>--}}
{{--                                    <div class="col-md-6">--}}
{{--                                        <input type="number" class="form-control" name="service_name" readonly--}}
{{--                                               placeholder=" {{__('مصروف')}}"--}}
{{--                                               value="0">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </fieldset>--}}

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
                                    <label class="col-sm-2 control-label">
                                        {{__('المبلغ')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="number" class="form-control" name="profit"
                                               placeholder=" {{__('المبلغ')}}"
                                               value="{{ old('profit') }}" step="any" required>
                                    </div>
                                </div>
                            </fieldset>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">{{__('cp.add')}}</button>
                                        <a href="{{url(getLocal().'/admin/Expenses_dollar/store')}}" class="btn default">{{__('cp.cancel')}}</a>
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

        $('#edit_image').on('change', function (e) {

            readURL(this, $('#editImage'));

        });



    </script>



@endsection

