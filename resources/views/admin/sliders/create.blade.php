@extends('layout.adminLayout')
@section('title') {{__('cp.slider')}}
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
                              style="color: #e02222 !important;">{{__('cp.add')}} {{__('cp.slider')}}</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <form method="post" action="{{url(app()->getLocale().'/admin/sliders/')}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form" id="form">
                        {{ csrf_field() }}
                        <input type="hidden" name="my_store_id" value="{{request()->my_store_id}}"/>
                        <div class="form-body">

                            <fieldset>
                                @foreach($locales as $locale)
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="title_{{$locale->lang}}">
                                            {{__('cp.title_'.$locale->lang)}}
                                            <span class="symbol">*</span>
                                        </label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="title_{{$locale->lang}}"

                                                   id="title_{{$locale->lang}}"
                                                   value="{{ old('title_'.$locale->lang) }}" required>
                                        </div>
                                    </div>

                                @endforeach
                            </fieldset>


                            <fieldset>
                                <div class="form-group" id="champion">
                                    <label class="col-sm-2 control-label">{{__('cp.type')}}
                                        <span class="symbol">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <select id="type" class="form-control "
                                                name="type">
                                            <option value="0">صورة فقط</option>
                                            <option value="1">منتج</option>
                                            <option value="2">رابط</option>

                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset id="link" style="display: none;">
                                <div class="form-group">

                                    <label class="col-sm-2 control-label" for="order">

                                        {{__('cp.link')}}
                                    </label>

                                    <div style="direction: ltr" class="col-md-6">
                                        <input style="direction: ltr" type="url" class="form-control" name="link" value="{{ old('link') }}">
                                        <small>http://www.google.com</small>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset id="product" style="display: none;">
                                <div class="form-group">

                                    <label class="col-sm-2 control-label" for="order">

                                        {{__('cp.product')}}
                                    </label>
                                    <div class="col-md-6">
                                        <select id="product_id" class="form-control select"
                                                name="product_id">
                                            <option value="">{{__('cp.select')}}</option>
                                            @foreach($products as $product)
                                                <option value="{{$product->id}}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                                    {{$product->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <legend>{{__('cp.logo')}}</legend>
                                <div class="form-group {{ $errors->has('logo') ? ' has-error' : '' }}">
                                    <div class="col-md-6 col-md-offset-3">
                                        @if ($errors->has('logo'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('logo') }}</strong>
                                            </span>
                                        @endif
                                        <div class="fileinput-new thumbnail"
                                             onclick="document.getElementById('edit_image').click()"
                                             style="cursor:pointer">
                                            <img src=" {{url(admin_assets('/images/ChoosePhoto.png'))}}" id="editImage">

                                        </div>
                                        <div class="btn red" onclick="document.getElementById('edit_image').click()">
                                            <i class="fa fa-pencil"></i>
                                        </div>
                                        <input type="file" class="form-control" name="image"
                                               id="edit_image" required
                                               style="display:none">
                                    </div>
                                </div>
                            </fieldset>


                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">{{__('cp.add')}}</button>
                                        <a href="{{url(getLocal().'/admin/sliders')}}"
                                           class="btn default">{{__('cp.cancel')}}</a>
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

    <script>

        $(document).on('change', '#type', function (e) {

            $("#product_id").val($("#product_id option:first").val());
            $('input[name="link"]').val("");

            doChange();

        });

        function doChange(){

            var option = $("#type option:selected").val();
            if (option == 1) {
                $("#product").show(300);
                $("#link").hide(300);
            }
            if (option == 2) {
                $("#product").hide(300);
                $("#link").show(300);
            }
            if (option == 0) {
                $("#product").hide(300);
                $("#link").hide(300);
            }
        }

    </script>

@endsection

