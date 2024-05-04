@extends('layout.adminLayout')

@section('title')  {{__('cp.admins')}} 
@endsection


@section('css_file_upload')
@endsection


@section('css')

@endsection


@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject font-dark sbold uppercase" style="color: #e02222 !important;">{{__('cp.add')}} {{__('cp.admin')}}</span>
                </div>
            </div>
            
            <div class="portlet-body form">
                <form  method="post" action="{{url(app()->getLocale().'/admin/admins')}}"                           enctype="multipart/form-data" class="form-horizontal" role="form" id="form">

                {{ csrf_field() }}
                    
                    <div class="form-body">

                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="order">
                                    {{__('cp.name')}} <span class="symbol">*</span>
                                </label>
                                
                                <div class="col-md-6">
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}"  required>
                                </div>
                            </div>
                        </fieldset>
                            <fieldset>
                                <div class="form-group" id="category">
                                    <label class="col-sm-2 control-label" >{{__('cp.city')}}
                                        <span class="symbol">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <select id="multiple2" class="form-control select "
                                                name="city_id" required>
                                            <option value="">{{__('cp.select')}}</option>
                                            @foreach($cities as $city)
                                                <option value="{{$city->id}}" {{ old('$city') == $city->id ? 'selected' : '' }}>
                                                    {{$city->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>


                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="order">
                                    {{__('cp.email')}} <span class="symbol">*</span>
                                </label>
                                <div class="col-md-6">
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}"  required>
                                </div>
                            </div>
                        </fieldset>


                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="order">
                                    {{__('cp.mobile')}} <span class="symbol">*</span>
                                </label>
                                <div class="col-md-6">
                                    <input onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"  type="text" class="form-control" name="mobile" value=""
                                     value="{{ old('mobile') }}"  required>
                                </div>
                            </div>
                        </fieldset>


                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="order">
                                    {{__('cp.password')}} <span class="symbol">*</span>
                                </label>
                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="password" value="" required>
                                </div>
                            </div>
                        </fieldset>


                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="order">
                                    {{__('cp.confirm_password')}} <span class="symbol">*</span>
                                </label>
                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="confirm_password" value=""  required>
                                </div>
                            </div>
                        </fieldset>

                            
                        <div class="form-group{{ $errors->has('permissions') ? ' has-error' : '' }}">
                            <label class="control-label col-md-2" for="permissions">
                                {{__('cp.role')}}
                            </label>
                            <div class="col-md-6">
                                <select class="form-control select2" id="permissions" name="permissions[]" multiple="multiple" required>
                                    @foreach($role as$roleItem)
                                        <option value="{{$roleItem->roleSlug}}">{{$roleItem->name}}</option>
                                    @endforeach
                                </select>
                                
                                @if ($errors->has('permissions'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('permissions') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <fieldset id="store_id_view" style="display: none">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="category">{{__('cp.stores')}}</label>
                                <div class="col-md-6">
                                    <select class="form-control" id="store_id" name="store_id">
                                        <option value="">{{__('cp.all')}}</option>
                                    </select>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset id="institute_id_view" style="display: none">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="category">{{__('cp.institutes')}}</label>
                                <div class="col-md-6">
                                    <select class="form-control" id="institute_id" name="institute_id">
                                        <option value="">{{__('cp.all')}}</option>
                                    </select>
                                </div>
                            </div>
                        </fieldset>


                        <fieldset id="public_services_id_view" style="display: none">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="category">{{__('cp.publicServices')}}</label>
                                <div class="col-md-6">
                                    <select class="form-control" id="public_services_id" name="public_services_id">
                                        <option value="">{{__('cp.all')}}</option>
                                    </select>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset id="product_services_view" style="display: none">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="category">{{__('cp.productServices')}}</label>
                                <div class="col-md-6">
                                    <select class="form-control" id="product_services_id" name="product_services_id">
                                        <option value="">مدينة واحدة</option>
                                        <option value="1">كل المدن</option>
                                    </select>
                                </div>
                            </div>
                        </fieldset>

                        <div class="form-actions">
                            <div class="row">
                                    <div class="col-md-12">
                                    <button type="submit" class="btn green">{{__('cp.submit')}}</button>
                                    <a href="{{url(getLocal().'/admin/admins')}}" class="btn default">{{__('cp.cancel')}}</a>
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



@section('js_file_upload')
@endsection


@section('js')
@endsection


@section('script')
<script>
    $('#edit_image').on('change', function (e) {
        readURL(this, $('#editImage'));
    });

    $('#permissions').on('select2:select', function (e) {
        /*
        var boo = false;
        var args = JSON.stringify($("#permissions").val(), function (key, value) {
            if(value == "stores") {
                boo = true;
            }
                return value;
        });
        */
        if(e.params.data.id == "stores")
        {
            $('#store_id_view').css({display: 'block'});
        }

        if(e.params.data.id == "institutes")
        {
            $('#institute_id_view').css({display: 'block'});
        }

        if(e.params.data.id == "publicServices")
        {
            $('#public_services_id_view').css({display: 'block'});
        }

        if(e.params.data.id == "productServices")
        {
            $('#product_services_view').css({display: 'block'});
        }
    });

    $('#permissions').on('select2:unselect', function (e) {
        /*
        var boo = false;
        var args = JSON.stringify($("#permissions").val(), function (key, value) {
            if(value == "stores") {
                boo = true;
            }
                return value;
        });
        */
        if(e.params.data.id == "stores")
        {
            $('#store_id_view').css({display: 'none'});
        }

        if(e.params.data.id == "institutes")
        {
            $('#institute_id_view').css({display: 'none'});
        }

        if(e.params.data.id == "publicServices")
        {
            $('#public_services_id_view').css({display: 'none'});
        }

        if(e.params.data.id == "productServices")
        {
            $('#product_services_view').css({display: 'none'});
        }

    });

    $(document).ready(function() {
        $('#multiple2').change(function(){
        var tid = $('#multiple2').val();
        if(tid){
            $.ajax({
                type:"get",
                url:"{{url('admin/admins/get_cities')}}/"+tid,
                success:function(res)
                {
                    $('#store_id').empty();
                    $('#store_id').append("<option value=''>{{__('cp.all')}}</option>");
                    if(res)
                    {
                        $.each(res,function(key,value){
                            $('#store_id').append("<option value='"+value+"'>"+key+"</option>");
                        });
                    }
                }

            });

            $.ajax({
                type:"get",
                url:"{{url('admin/admins/get_cities_institutes')}}/"+tid,
                success:function(res)
                {
                    $('#institute_id').empty();
                    $('#institute_id').append("<option value=''>{{__('cp.all')}}</option>");
                    if(res)
                    {
                        $.each(res,function(key,value){
                            $('#institute_id').append("<option value='"+value+"'>"+key+"</option>");
                        });
                    }
                }

            });


            $.ajax({
                type:"get",
                url:"{{url('admin/admins/get_cities_public_services')}}/"+tid,
                success:function(res)
                {
                    $('#public_services_id').empty();
                    $('#public_services_id').append("<option value=''>{{__('cp.all')}}</option>");
                    if(res)
                    {
                        $.each(res,function(key,value){
                            $('#public_services_id').append("<option value='"+value+"'>"+key+"</option>");
                        });
                    }
                }

            });
        }
        });
    });

    </script>
@endsection
