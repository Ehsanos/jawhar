@extends('layout.adminLayout')
@section('title') {{__('cp.distribution_points')}}
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
                    <form method="post" action="{{url(app()->getLocale().'/admin/distribution_points')}}"
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
                                        <label class="col-sm-2 control-label">
                                            {{__('cp.mobile')}}
                                        </label>
                                        <div class="col-md-6">
                                            <input type="number" class="form-control" name="mobile"
                                                   placeholder=" {{__('cp.mobile')}}"
                                                   value="{{ old('mobile') }}" >
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
                                                <option value="0">{{__('cp.select')}}</option>
                                                @foreach($cities as $one)
                                                    <option value="{{$one->id}}" {{ $one->id == old('city_id') ? 'selected':''}}>{{$one->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>

                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            {{__('cp.latitude')}}
                                        </label>
                                        <div class="col-md-6">
                                            <input type="number" step="0.000000" class="form-control" name="latitude"
                                                   placeholder=" {{__('cp.latitude')}}"
                                                   value="{{ old('latitude') }}" >
                                        </div>
                                    </div>
                            </fieldset>
                            </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            {{__('cp.longitude')}}
                                        </label>
                                        <div class="col-md-6">
                                            <input type="number" step="0.000000" class="form-control" name="longitude"
                                                   placeholder=" {{__('cp.longitude')}}"
                                                   value="{{ old('longitude') }}" >
                                        </div>
                                    </div>
                            </fieldset>



                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">{{__('cp.add')}}</button>
                                        <a href="{{url(getLocal().'/admin/distribution_points')}}" class="btn default">{{__('cp.cancel')}}</a>
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

