@extends('layout.adminLayout')
@section('title') {{__('cp.balanceCards')}}
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
                              style="color: #e02222 !important;">{{__('cp.balanceCards')}}</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <form method="post" action="{{url(app()->getLocale().'/admin/balanceCards/'.$item->id.'/')}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form" id="form_city">
                        {{ csrf_field() }}
                        {{ method_field('PATCH')}}


                        <div class="form-body">


                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('cp.serial_number')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="serial_number"
                                               placeholder=" {{__('cp.serial_number')}}"
                                               value="{{@$item-> 	serial_number}}" >
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('cp.price')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="number" min='1' class="form-control" name="price"
                                               placeholder=" {{__('cp.price')}}"
                                               value="{{@$item->price}}" >
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('cp.password')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" min='1' class="form-control" name="password"
                                               placeholder=" {{__('cp.password')}}"
                                               value="{{@$item->password}}" >
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group" id="is_dollar">
                                    <label class="col-sm-2 control-label" >العملة
                                    </label>
                                    <div class="col-md-6">
                                        <select id="multiple2" class="form-control select"
                                                name="currency" required>
                                            <option value="">{{__('cp.select')}}</option>
                                            <option {{@$item->currency == "turkey" ? "selected" : ""}} value="turkey">
                                                بالتركي
                                            </option>
                                            <option {{@$item->currency == "dollar" ? "selected" : ""}} value="dollar">
                                                بالدولار
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>





                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">{{__('cp.edit')}}</button>
                                        <a href="{{url(getLocal().'/admin/balanceCards')}}" class="btn default">{{__('cp.cancel')}}</a>
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



        $('#type').on('change', function() {

            var gover = this.value ;

            sessionStorage.setItem("type",  this.value);



            if(gover == 1){

                $('#gover_option').removeClass('hidden');

                $('#options').prop('required',true);

            }else{

                $('#gover_option').addClass('hidden');

                $('#options').prop('required',false);

            }





        });







        var FormValidation = function () {



            // basic validation

            var handleValidation1 = function() {

                // for more info visit the official plugin documentation:

                // http://docs.jquery.com/Plugins/Validation



                var form1 = $('#form_city');

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

                        type: {required: true},

                        name_ar: {required: true},

                        name_en: {required: true},

                        gover_id: {required:    function(){ return $("#type").val() == "1" ; } },

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

                        e.submit()                }

                });

            };

            // console.log(sessionStorage.getItem("type"));







            return {

                //main function to initiate the module

                init: function () {



                    handleValidation1();



                }



            };



        }();



        jQuery(document).ready(function() {

            FormValidation.init();

        });







    </script>

@endsection

