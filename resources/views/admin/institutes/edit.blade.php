@extends('layout.adminLayout')
@section('title') {{__('cp.institutes')}}
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
                    <form method="post" action="{{url(app()->getLocale().'/admin/institutes/'.$item->id)}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form" id="form_city">
                        {{ csrf_field() }}
                        {{ method_field('PATCH')}}


                        <div class="form-body">


                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('cp.name')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="name"
                                               placeholder=" {{__('cp.name')}}"
                                               value="{{@$item->name}}" >
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
                                                <option value="{{$one->id}}" data-code="{{$one->user_code}}" {{ old('user_id',$item->user_id) == $one->id ? "selected" :"" }}>{{$one->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group">
                                <label class="col-sm-2 control-label" >
                                    نسبة التطبيق من المعاهد
                                </label>
                                 <div class="col-md-3">
                                <input onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" type="number" min="0" max="100" class="form-control" name="app_percent"
                                       value="{{$item->app_percent}}"  >
                                 </div>
                                </div>
                            </fieldset>

                              <fieldset>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            {{__('cp.city')}}
                                        </label>
                                        <div class="col-md-6">
                                            <select name="city_id" class="form-control">
                                                <option value="">{{__('cp.select')}}</option>
                                                @foreach($cities as $one)
                                                    <option value="{{$one->id}}" {{ $one->id == old('city_id',$item->city_id) ? 'selected':''}}>{{$one->name}}</option>
                                                @endforeach
                                            </select>
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
                                            <img src="@if($item->image){{$item->image}} @else  {{ url(admin_assets('/images/ChoosePhoto.png'))}} @endif" id="editImage" >
                                        </div>
                                        
                                        <div class="btn red"
                                             onclick="document.getElementById('edit_image').click()">
                                            <i class="fa fa-pencil"></i>
                                        </div>
                                        <input type="file" class="form-control" name="image"
                                               id="edit_image"
                                               style="display:none">
                                    </div>
                                </div>
                            </fieldset>



                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">{{__('cp.edit')}}</button>
                                        <a href="{{url(getLocal().'/admin/institutes')}}" class="btn default">{{__('cp.cancel')}}</a>
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

