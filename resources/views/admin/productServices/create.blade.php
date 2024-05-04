@extends('layout.adminLayout')
@section('title'){{__('cp.addProductServices')}}
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
                              style="color: #e02222 !important;">{{__('cp.addProductServices')}}</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <form method="post" action="{{url(app()->getLocale().'/admin/productServices')}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form" id="form">
                        {{ csrf_field() }}
                        <div class="form-body">
                            <fieldset>
                                <div class="form-group" id="gover_option">
                                    <label class="col-sm-2 control-label" >{{__('cp.service')}}
                                        <span class="symbol">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <select id="service_id" class="form-control select"
                                                name="service" required>
                                            <option value="">{{__('cp.select')}}</option>
                                            @foreach($items as $item)
                                                <option value="{{$item->id}}" {{ old('service_id') == $item->id ? 'selected' : '' }}>
                                                    {{$item->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group" id="gover_option">
                                    <label class="col-sm-2 control-label" >
                                        واتس اب
                                    </label>
                                    <div class="col-md-6">
                                        <input type="checkbox" id="wapp_status" class="form-check-input" name="wapp_status">
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset id="wapp_nums" style="display: none;">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label ">
                                        ارقام واتس اب
                                    </label>
                                    <div class="col-md-6">
                                        <select name="wapp_id" class="js-example-basic-single form-control">
                                            @foreach($whatsapp as $one)
                                                <option value="{{$one->id}}">{{$one->user->name}} - {{$one->phone}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group" id="gover_option">
                                    <label class="col-sm-2 control-label" >
                                        صانع الخدمة
                                    </label>
                                    <div class="col-md-6">
                                        <input type="checkbox" id="service_worker_status" class="form-check-input" name="service_worker_status">
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset id="wapp_id_service_worker_nums" style="display: none;">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label ">
                                        ارقام  صانع الخدمة
                                    </label>
                                    <div class="col-md-6">
                                        <select name="wapp_id_service_worker" class="js-example-basic-single form-control">
                                            @foreach($whatsapp as $one)
                                                <option value="{{$one->id}}">{{$one->user->name}} - {{$one->phone}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                @foreach($locales as $locale)
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="name_{{$locale->lang}}">
                                            {{__('cp.name_'.$locale->lang)}}
                                            <span class="symbol">*</span>
                                        </label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="name_{{$locale->lang}}"

                                                   id="name_{{$locale->lang}}"
                                                   value="{{ old('name_'.$locale->lang) }}" required>
                                        </div>
                                    </div>
                                @endforeach
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
                                        <label class="col-sm-2 control-label" >
                                            {{__('cp.purchasing_price')}}
                                            <span class="symbol">*</span>
                                        </label>
                                        <div class="col-md-6">
                                            <input type="number" step="0.01" class="form-control" name="purchasing_price"
                                                   value="{{ old('purchasing_price') }}" required>
                                        </div>
                                    </div>

                            </fieldset>
                            <fieldset>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" >
                                            {{__('cp.selling_price')}}
                                            <span class="symbol">*</span>
                                        </label>
                                        <div class="col-md-6">
                                            <input type="number" step="0.01" class="form-control" name="price"

                                                   id="price"
                                                   value="{{ old('price') }}" required>
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
                                            <img src=" {{url(admin_assets('/images/ChoosePhoto.png'))}}"  id="editImage" value="{{old('image')}}" >

                                        </div>
                                        <div class="btn red" onclick="document.getElementById('edit_image').click()">
                                            <i class="fa fa-pencil"></i>
                                        </div>
                                        <input type="file" class="form-control" name="image" value="{{old('image')}}"
                                               id="edit_image" required
                                               style="display:none" >
                                    </div>
                                </div>
                            </fieldset>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">{{__('cp.submit')}}</button>
                                        <a href="{{url(getLocal().'/admin/productServices')}}" class="btn default">{{__('cp.cancel')}}</a>
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

<script type="text/javascript">

    $(document).ready(function() {

      $(".btn-success").click(function(){
          var html = $(".clone").html();
          $(".increment").after(html);
      });

      $("body").on("click",".btn-danger",function(){
          $(this).parents(".control-group").remove();
      });

    });

    $('#edit_image').on('change', function (e) {

        readURL(this, $('#editImage'));

    });

    $(document).ready(function() {
        $("#wapp_status").change(function() {
            if(this.checked) {
                $("#service_worker_status").prop('checked', false);
                $("#wapp_id_service_worker_nums").hide(300);
                $("#wapp_nums").show(300);
            }
            else
            {
                $("#wapp_nums").hide(300);
            }
        });
        $("#service_worker_status").change(function() {
            if(this.checked) {
                $("#wapp_status").prop('checked', false);
                $("#wapp_nums").hide(300);
                $("#wapp_id_service_worker_nums").show(300);
            }
            else
            {
                $("#wapp_id_service_worker_nums").hide(300);
            }
        });
    });

    $(document).ready(function() {
        $('.js-example-basic-single').select2({
            width: "100%",
            dir: "rtl",
        })

    });

</script>

</script>

@endsection

