@extends('layout.adminLayout')
@section('title') {{__('باقات شبكات الموبيل ')}}
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
                    <form method="post" action="{{url(app()->getLocale().'/admin/MobileNetworkPackages')}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form" id="form_city">
                        {{ csrf_field() }}

                        <div class="form-body">
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('cp.name')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="name"
                                               placeholder=" {{__('cp.name')}}"
                                               value="{{ old('name') }}" >
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label ">
                                        {{__('اسم الشبكة')}}
                                    </label>
                                    <div class="col-md-6">
                                        <select name="mobile_companies_id" class="form-control" required>
                                            <option value="">{{ __('cp.select') }}</option>
                                            @foreach($cobilecompany as $one)
                                                <option value="{{$one->id}}">{{$one->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group" id="is_dollar">
                                    <label class="col-sm-2 control-label" >العملة
                                    </label>
                                    <div class="col-md-6">
                                        <select id="multiple2" class="form-control select is_dollar"
                                                name="is_dollar" required>
                                            <option value="">{{__('cp.select')}}</option>
                                            <option value="0">
                                                سعر المنتج بالتركي
                                            </option>
                                            <option value="1">
                                                سعر المنتج بالدولار
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('cp.purchasing_price')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="purchasing_price"
                                               placeholder=" {{__('cp.purchasing_price')}}"
                                               value="{{ old('purchasing_price') }}" >
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('cp.price')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="price"
                                               placeholder=" {{__('cp.price')}}"
                                               value="{{ old('price') }}" >
                                    </div>
                                </div>
                            </fieldset>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">{{__('cp.add')}}</button>
                                        <a href="{{url(getLocal().'/admin/MobileNetworkPackages')}}" class="btn default">{{__('cp.cancel')}}</a>
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

