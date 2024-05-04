@extends('layout.adminLayout')
@section('title'){{__('cp.network')}}
@endsection
@section('css')
    {{-- <style>
        ::placeholder {
      color: red;
      opacity: 1; /* Firefox */
    }

    :-ms-input-placeholder { /* Internet Explorer 10-11 */
     color: red;
    }

    ::-ms-input-placeholder { /* Microsoft Edge */
     color: red;
    }
    </style> --}}
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
                              style="color: #e02222 !important;">{{__('cp.add')}} {{__('cp.networks')}}</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <form method="post" action="{{url(app()->getLocale().'/admin/networks')}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form" id="form">
                        {{ csrf_field() }}
                        <div class="form-body">


                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="category">{{__('cp.wifi')}} <span
                                                class="symbol">*</span></label>
                                    <div class="col-md-6">
                                        <select class="form-control" id="wifi" name="wifi_id">
                                            <option value="">{{__('cp.select')}}</option>
                                            @foreach ($wifi as $one)

                                                <option value="{{ $one->id }}">{{ $one->name }}
                                                    ---( {{ $one->city->name }} )
                                                </option>

                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="name">
                                        {{__('cp.name')}}
                                        <span class="symbol">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="name"
                                               id="name"
                                               value="{{ old('name')}}" required>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <div class="form-group" id="is_dollar">
                                    <label class="col-sm-2 control-label">العملة
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
                                    <label class="col-sm-2 control-label" for="price">
                                        {{__('cp.price')}}
                                        <span class="symbol">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="price"

                                               id="price"
                                               value="{{ old('price') }}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="price">
                                        تجديد باقة
                                        <span class="symbol">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <input name="type" type="hidden" value="1">
                                        <input name="type" type="checkbox" value="0">
                                    </div>

                                </div>


                            </fieldset>


                            <fieldset>
                                <legend>{{__('cp.image')}}</legend>
                                <div class="form-group {{ $errors->has('image') ? ' has-error' : '' }}">
                                    <div class="col-md-6 col-md-offset-3">
                                        @if ($errors->has('image'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('image') }}</strong>
                                            </span>
                                        @endif
                                        <div class="fileinput-new thumbnail"
                                             onclick="document.getElementById('edit_image').click()"
                                             style="cursor:pointer">
                                            <img src=" {{url(admin_assets('/images/ChoosePhoto.png'))}}" id="editImage"
                                                 value="{{old('image')}}">

                                        </div>
                                        <div class="btn red" onclick="document.getElementById('edit_image').click()">
                                            <i class="fa fa-pencil"></i>
                                        </div>
                                        <input type="file" class="form-control" name="image" value="{{old('image')}}"
                                               id="edit_image" required
                                               style="display:none">
                                    </div>
                                </div>
                            </fieldset>


                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">{{__('cp.submit')}}</button>
                                        <a href="{{url(getLocal().'/admin/networks')}}"
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
        $(document).on('change', '.category', function (e) {
            var category = $(this).val();
            var url = "{{ url(app()->getLocale().'/admin/getSubCategoryByCategoryId/') }}";

            if (category) {
                $.ajax({
                    type: "GET",
                    url: url + '/' + category,
                    success: function (response) {
                        if (response) {
                            $(".sub_category").empty();
                            $(".sub_category").append('<optgroup label="{{__('cp.area')}}">');
                            $.each(response, function (index, value) {
                                $(".sub_category").append('<option value="' + value.id + '">' + value.name + '</option>');
                                $(".sub_category").append('</optgroup>');
                            });
                        }
                    }
                });
            } else {
                $(".area").empty();
            }
        });

        $(document).ready(function () {
            $('form').ajaxForm({
                beforeSend: function () {
                    $('#success').empty();
                    $('.progress-bar').text('0%');
                    $('.progress-bar').css('width', '0%');
                },
                uploadProgress: function (event, position, total, percentComplete) {
                    $('.progress-bar').text(percentComplete + '0%');
                    $('.progress-bar').css('width', percentComplete + '0%');
                },
                success: function (data) {
                    if (data.success) {
                        $('#success').html('<div class="text-success text-center"><b>' + data.success + '</b></div><br /><br />');
                        $('#success').append(data.image);
                        $('.progress-bar').text('Uploaded');
                        $('.progress-bar').css('width', '100%');
                    }
                }
            });
        });
    </script>

    <script type="text/javascript">

        $(document).ready(function () {

            $(".btn-success").click(function () {
                var html = $(".clone").html();
                $(".increment").after(html);
            });

            $("body").on("click", ".btn-danger", function () {
                $(this).parents(".control-group").remove();
            });

        });

    </script>
    <script>

        $('#edit_image').on('change', function (e) {

            readURL(this, $('#editImage'));

        });


    </script>
@endsection
