@extends('layout.adminLayout')
@section('title')  {{__('cp.admins')}} - {{$item->name}}
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
                    <span class="caption-subject font-dark sbold uppercase" style="color: #e02222 !important;">{{__('cp.edit')}} {{__('cp.admins')}}</span>
                </div>
            </div>
            
            <div class="portlet-body form">
                
                <form method="post" action="{{url(app()->getLocale().'/admin/admins/'.$item->id)}}" enctype="multipart/form-data" class="form-horizontal" role="form" id="form">
                {{ csrf_field() }}
                {{ method_field('PATCH')}}

                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="order">
                                {{__('cp.name')}} <span class="symbol">*</span>
                            </label>
                            <div class="col-md-6">
                                <input type="text" name="name" class="form-control"  value="{{ $item->name }}"  {{ old('name') }} required>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                                    <div class="form-group" id="category">
                                        <label class="col-sm-2 control-label" >{{__('cp.city')}}
                                            
                                        </label>
                                        <div class="col-md-6">
                                        <select id="city" class="form-control select "
                                              name="city_id">
                                            <option value="" >{{__('cp.select')}}</option>
                                           @foreach($cities as $one)
                                             <option value="{{$one->id}}" @if($item->city_id==$one->id) selected @endif>{{$one->name}}</option>
                                           @endforeach
                                      </select>
                                        </div>
                                    </div>
                                </fieldset>   
                    <fieldset>
                    <div class="form-group">
                            <label class="col-sm-2 control-label" for="order">
                                {{__('cp.mobile')}} <span class="symbol">*</span>
                            </label>
                            <div class="col-md-6">
                                <input type="number" class="form-control" name="mobile" value="{{ $item->mobile }}"   {{ old('mobile') }} required>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                    <div class="form-group">
                            <label class="col-sm-2 control-label" for="order">
                                {{__('cp.email')}} <span class="symbol">*</span>
                            </label>
                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ $item->email }}"  {{ old('email') }} required>
                            </div>
                        </div>
                    </fieldset>

                    <?php

                    $boo = false;
                    $boo1 = false;
                    $boo2 = false;
                    $boo3 = false;

                    ?>

                    @if (can('admins') && $item->id != 1 )

                    <fieldset>
                        <div class="form-group{{ $errors->has('permissions') ? ' has-error' : '' }}">
                            <label class="col-sm-2 control-label">
                                {{__('cp.role')}}
                            </label>
                            <div class="col-md-6">
                                <select class="form-control select2" id="permissions" name="permissions[]" multiple="multiple" required>
                                    @foreach($role as$roleItem)
                                        <option value="{{$roleItem->roleSlug}}" @if(in_array($roleItem->roleSlug,$userRoleItem))  @if($roleItem->roleSlug == "stores") <?php $boo=true; ?> @endif @if($roleItem->roleSlug == "publicServices") <?php $boo2=true; ?> @endif @if($roleItem->roleSlug == "institutes") <?php $boo1=true; ?> @endif  @if($roleItem->roleSlug == "productServices") <?php $boo3=true; ?> @endif selected @endif>{{$roleItem->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset id="store_id_view" style="display: {{  $boo == false ? "none" : "block" }}">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="category">{{__('cp.stores')}}</label>
                                <div class="col-md-6">
                                    <select class="form-control" id="store_id" name="store_id">
                                        <option value="">{{__('cp.all')}}</option>
                                        @foreach ($all_stores as $one)

                                            <option value="{{ $one->id }}"  {{ isset($userRole->store_id) &&  $userRole->store_id == $one->id ? "selected" : "" }}>{{ $one->store_name }}</option>

                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset id="institute_id_view" style="display: {{  $boo1 == false ? "none" : "block" }}">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="category">{{__('cp.institutes')}}</label>
                                <div class="col-md-6">
                                    <select class="form-control" id="institute_id" name="institute_id">
                                        <option value="">{{__('cp.all')}}</option>
                                        @foreach ($all_institutes as $key=>$value)

                                            <option value="{{ $value }}"  {{ isset($userRole->institute_id) &&  $userRole->institute_id ==$value ? "selected" : "" }}>{{ $key }}</option>

                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset id="public_services_id_view" style="display: {{  $boo2 == false ? "none" : "block" }}">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="category">{{__('cp.publicServices')}}</label>
                                <div class="col-md-6">
                                    <select class="form-control" id="public_services_id" name="public_services_id">
                                        <option value="">{{__('cp.all')}}</option>
                                        @foreach ($all_public_services as $key=>$value)

                                            <option value="{{ $value }}"  {{ isset($userRole->public_services_id) &&  $userRole->public_services_id ==$value ? "selected" : "" }}>{{ $key }}</option>

                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </fieldset>

                    @endif


                    <fieldset id="product_services_view" style="display: {{  $boo3 == false ? "none" : "block" }}">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="category">{{__('cp.productServices')}}</label>
                            <div class="col-md-6">
                                <select class="form-control" id="product_services_id" name="product_services_id">
                                    <option value="" {{ isset($userRole->product_services_id) &&  $userRole->product_services_id =="" ? "selected" : "" }}>مدينة واحدة</option>
                                    <option value="1" {{ isset($userRole->product_services_id) &&  $userRole->product_services_id =="1" ? "selected" : "" }}>كل المدن</option>
                                </select>
                            </div>
                        </div>
                    </fieldset>


                    <div class="form-actions">
                        <div class="row">
                                    <div class="col-md-12">
                                <button type="submit" class="btn green">{{__('cp.submit')}}</button>
                                <a href="javascript:history.back()" class="btn default">{{__('cp.cancel')}}</a>
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

    var FormValidation = function () {

    var handleValidation1 = function () {
    var form1 = $('#form_category');
    var error1 = $('.alert-danger', form1);
    var success1 = $('.alert-success', form1);

    form1.validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block help-block-error', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",  // validate all fields including form hidden input
        messages: {
            select_multi: {
                maxlength: jQuery.validator.format("Max {0} items allowed for selection"),
                minlength: jQuery.validator.format("At least {0} items must be selected"),
            },
        },
        rules: {
            title_ar: {required: true},
            title_en: {required: true},
            logo: {required: true},
        },

        invalidHandler: function (event, validator) { //display error alert on form submit
            success1.hide();
            error1.show();
            App.scrollTo(error1, -200);
        },

        highlight: function (element) { // hightlight error inputs
            $(element)
            .closest('.form-group').addClass('has-error'); // set error class to the control group
        },

        unhighlight: function (element) { // revert the change done by hightlight
            $(element)
            .closest('.form-group').removeClass('has-error'); // set error class to the control group
        },

        success: function (label) {
            label
            .closest('.form-group').removeClass('has-error'); // set success class to the control group
        },

        submitHandler: function (form) {
            success1.show();
            error1.hide
            e.submit()
        }
    });
};


    return {
        //main function to initiate the module
        init: function () {
            handleValidation1();
        }
    };

    }();

        jQuery(document).ready(function () {
            FormValidation.init();
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
        $('#city').change(function(){
            var tid = $('#city').val();
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
