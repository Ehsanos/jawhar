@extends('layout.adminLayout')
@section('title')  {{ucwords(__('cp.setting'))}}
@endsection
@section('css')
    <script type="text/javascript"
            src="https://maps.googleapis.com/maps/api/js?key={{env('APIGoogleKey')}}&callback=initMap">

    </script>
    <style type="text/css">
        .input-controls {
            margin-top: 10px;
            border: 1px solid transparent;
            border-radius: 2px 0 0 2px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            height: 32px;
            outline: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        }

        #searchInput {
            background-color: #fff;
            font-family: Roboto;
            font-size: 15px;
            font-weight: 300;
            margin-left: 12px;
            padding: 0 11px 0 13px;
            text-overflow: ellipsis;
            width: 50%;
        }

        #searchInput:focus {
            border-color: #4d90fe;
        }
    </style>

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
                              style="color: #e02222 !important;">{{__('cp.edit')}}{{__('cp.setting')}}</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <form method="post" action="{{url(app()->getLocale().'/admin/settings')}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form">
                        {{ csrf_field() }}
                        <div class="form-body">

                            <fieldset>
                                <legend>{{__('cp.setting')}}</legend>

                            @foreach($locales as $locale)
                                
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="order">
                                            {{__('cp.appName_'.$locale->lang)}}
                                        </label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control"
                                                   name="title_{{$locale->lang}}" value="{{old('title_'.$locale->lang,@$item->translate($locale->lang)->title)}}">
                                        </div>
                                    </div>
                            @endforeach


                            @foreach($locales as $locale)
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="order">
                                            {{__('cp.address_'.$locale->lang)}}
                                        </label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="address_{{$locale->lang}}" value="{{old('address_'.$locale->lang,@$item->translate($locale->lang)->address)}}" id="order">
                                        </div>
                                    </div>
                            @endforeach


                            @foreach($locales as $locale)
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="order">
                                            {{__('cp.description_'.$locale->lang)}}
                                        </label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="description_{{$locale->lang}}" value="{{old('description_'.$locale->lang,@$item->translate($locale->lang)->description)}}" id="order">
                                        </div>
                                    </div>
                            @endforeach
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="order">
                                        {{__('cp.info_email')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="email" class="form-control" name="info_email" value="{{old('info_email',$item->info_email)}}" id="order">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="order">
                                        {{__('cp.app_store_url')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="url" class="form-control" name="app_store_url" value="{{old('app_store_url',$item->app_store_url)}}" id="order">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="order">
                                        {{__('cp.play_store_url')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="url" class="form-control" name="play_store_url" value="{{old('play_store_url',$item->play_store_url)}}" id="order">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="order">
                                        {{__('cp.direct_link')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="url" class="form-control" name="direct_link" value="{{old('direct_link',$item->direct_link)}}" id="order">
                                    </div>
                                </div>
                                
                                </fieldset>
                            <fieldset>
                                <legend>{{__('cp.InsuranceAmount')}}</legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="order">
                                        {{__('cp.InsuranceAmount')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="number" min=0 class="form-control" name="min_order" value="{{old('min_order',$item->min_order)}}" id="min_order">
                                    </div>
                                </div>
                              
                            </fieldset>
                            <fieldset>
                 <legend>متجر جوهر</legend>                                
                           <div class="form-group">
                                    <label class="col-sm-2 control-label" >{{__('cp.store_status')}}
                                        <span class="symbol">*</span>
                                    </label>
                                    <div class="col-md-3">
                                        <select id="multiple2" class="form-control select"
                                                name="store_status" required>
                                  <option value="0" {{@$item->store_status == 0 ? 'selected' : '' }}>{{__('cp.closed')}}</option>
                                  <option value="1" {{@$item->store_status ==1 ? 'selected' : '' }}>{{__('cp.opend')}}</option>
                                        </select>
                                    </div>
                            </div>
                            </fieldset>
                            <fieldset>
                                <legend>{{__('مفتاح حساب اصحاب')}}</legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="order">
                                        {{__('مفتاح حساب اصحاب')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text"  class="form-control" name="api_key" value="{{old('win_games',$item->api_key)}}" id="api_key">
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>{{__('اصدار التطبيق')}}</legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="order">
                                        {{__('اصدار التطبيق')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text"  class="form-control" name="version" value="{{old('version',$item->version)}}" id="version">
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>{{__('تفعيل تحديث التطبيق الزامياً عند المستخدمين')}}ً</legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" >{{__('حالة الزام التحديث ')}}
                                        <span class="symbol">*</span>
                                    </label>
                                    <div class="col-md-3">
                                        <select id="multiple22" class="form-control select"
                                                name="version_status" required>
                                            <option value="0" {{@$item->version_status == 0 ? 'selected' : '' }}>{{__('غير فعال')}}</option>
                                            <option value="1" {{@$item->version_status ==1 ? 'selected' : '' }}>{{__('فعال')}}</option>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>وضع الصيانة</legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" >وضع الصيانة
                                        <span class="symbol">*</span>
                                    </label>
                                    <div class="col-md-3">
                                        <select id="multiple22" class="form-control select"
                                                name="is_maintenance" required>
                                            <option value="0" {{@$item->is_maintenance == 0 ? 'selected' : '' }}>{{__('غير فعال')}}</option>
                                            <option value="1" {{@$item->is_maintenance ==1 ? 'selected' : '' }}>{{__('فعال')}}</option>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>{{__('نص رسالة الايقاف')}}</legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="order">
                                        {{__('الرسالة')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text"  class="form-control" name="stop_text" value="{{old('stop_text',$item->stop_text)}}" id="stop_text">
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>{{__('تفعيل صرف الدولار عند المستخدمين')}}ً</legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" >{{__('حالة الصرف  ')}}
                                        <span class="symbol">*</span>
                                    </label>
                                    <div class="col-md-3">
                                        <select id="multiple22" class="form-control select"
                                                name="status_dollar_conversion" required>
                                            <option value="0" {{@$item->status_dollar_conversion == 0 ? 'selected' : '' }}>{{__('غير فعال')}}</option>
                                            <option value="1" {{@$item->status_dollar_conversion ==1 ? 'selected' : '' }}>{{__('فعال')}}</option>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>{{__('سعر لبيع الدولار')}}</legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="order">
                                        {{__('سعر بيع الدولار')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="number" step="0.01" required  class="form-control" name="selling_price" value="{{old('selling_price',$item->selling_price)}}" id="selling_price">
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>{{__('تفعيل أرباح اللاعبين')}}</legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="order">
                                        {{__('نسبة الربح')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="number" step="0.01" required  class="form-control" name="leader_profit" value="{{old('leader_profit',$item->leader_profit)}}" id="selling_price">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="order">
                                        {{__('الحالة')}}
                                    </label>
                                    <div class="col-md-6">
                                        <select id="multiple22" class="form-control select"
                                                name="leader_profit_status" required>
                                            <option value="0" {{@$item->leader_profit_status == 0 ? 'selected' : '' }}>{{__('غير فعال')}}</option>
                                            <option value="1" {{@$item->leader_profit_status ==1 ? 'selected' : '' }}>{{__('فعال')}}</option>
                                        </select>
                                    </div>
                                </div>

                            </fieldset>
                            <fieldset>
                                <legend>{{__(' سعر شراء الدولار')}}</legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="order">
                                        {{__(' سعر شراء الدولار ')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="number" step="0.01" required class="form-control" name="exchange_rate" value="{{old('exchange_rate',$item->exchange_rate)}}" id="exchange_rate">
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>{{__('cp.contact_info')}}</legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="order">
                                        {{__('cp.mobile')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="mobile" value="{{old('mobile',$item->mobile)}}" id="order">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="order">
                                        {{__('cp.phone')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="phone" value="{{old('phone',$item->phone)}}" id="order">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="order">
                                        {{__('cp.facebook')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="facebook" value="{{old('facebook',$item->facebook)}}" id="order">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="order">
                                        {{'whatsapp'}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="twitter" value="{{old('twitter',$item->twitter)}}" id="order">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="order">
                                        {{__('cp.instagram')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="instagram" value="{{old('instagram',$item->instagram)}}" id="order">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="order">
                                        {{__('cp.linked_in')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="linked_in" value="{{old('linked_in',$item->linked_in)}}" id="order">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="order">
                                        {{__('cp.google_plus')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="google_plus" value="{{old('google_plus',$item->google_plus)}}" id="order">
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>{{__('cp.logo')}}</legend>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <div class="fileinput-new thumbnail"
                                             onclick="document.getElementById('edit_logo').click()"
                                             style="cursor:pointer">
                                            <img src="{{url($item->logo)}}" id="editlogo">
                                        </div>
                                        <div class="btn red"
                                             onclick="document.getElementById('edit_logo').click()">
                                            <i class="fa fa-pencil"></i>{{__('cp.change_image')}}
                                        </div>
                                        <input type="file" class="form-control" name="logo"
                                               id="edit_logo"
                                               style="display:none">
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>{{__('cp.image')}}</legend>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <div class="fileinput-new thumbnail"
                                             onclick="document.getElementById('edit_image').click()"
                                             style="cursor:pointer">
                                            <img src="{{url($item->image)}}" id="editImage">
                                        </div>
                                        <div class="btn red"
                                             onclick="document.getElementById('edit_image').click()">
                                            <i class="fa fa-pencil"></i>{{__('cp.change_image')}}
                                        </div>
                                        <input type="file" class="form-control" name="image"
                                               id="edit_image"
                                               style="display:none">
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                    <legend>{{""}}</legend>
                                    <input id="searchInput" class="input-controls" type="text"
                                           placeholder="{{__('cp.search')}}">
                                    <div class="map" id="map" style="width: 100%; height: 300px;"></div>
                                    <div class="form_area">
                                        <input type="hidden" value="{{$setting->address}}" name="address" id="location">
                                        <input type="hidden" value="{{$setting->latitude}}" name="latitude" id="lat">
                                        <input type="hidden" value="{{$setting->longitude}}" name="longitude" id="lng">
                                    </div>

                                </fieldset>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">{{__('cp.submit')}}</button>
                                        <a href="{{url(getLocal().'/admin/home')}}" class="btn default">{{__('cp.cancel')}}</a>
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


        $('#edit_logo').on('change', function (e) {
            readURL(this, $('#editlogo'));
        });

        function format(){
            var e = document.getElementById("type");
            var type = e.options[e.selectedIndex].value;
            //alert(type);

            if(type == 2){

                $('#park').removeClass('hidden');
                $('#edu').prop('required',true);
            }

            if(type == 1){
                $('#park').addClass('hidden');
                $('#edu').prop('required',false);
            }

        }



        /* script */
        function initialize() {
            var latlng = new google.maps.LatLng('{{$setting->latitude}}', '{{$setting->longitude}}');
            var map = new google.maps.Map(document.getElementById('map'), {
                center: latlng,
                zoom: 10
            });
            var marker = new google.maps.Marker({
                map: map,
                position: latlng,
                draggable: true,
                anchorPoint: new google.maps.Point(0, -29)
            });
            var input = document.getElementById('searchInput');
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
            var geocoder = new google.maps.Geocoder();
            var autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.bindTo('bounds', map);
            var infowindow = new google.maps.InfoWindow();
            autocomplete.addListener('place_changed', function () {
                infowindow.close();
                marker.setVisible(false);
                var place = autocomplete.getPlace();
                if (!place.geometry) {
                    window.alert("Autocomplete's returned place contains no geometry");
                    return;
                }

                // If the place has a geometry, then present it on a map.
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                }

                marker.setPosition(place.geometry.location);
                marker.setVisible(true);

                bindDataToForm(place.formatted_address, place.geometry.location.lat(), place.geometry.location.lng());
                infowindow.setContent(place.formatted_address);
                infowindow.open(map, marker);

            });
            // this function will work on marker move event into map
            google.maps.event.addListener(marker, 'dragend', function () {
                geocoder.geocode({'latLng': marker.getPosition()}, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            bindDataToForm(results[0].formatted_address, marker.getPosition().lat(), marker.getPosition().lng());
                            infowindow.setContent(results[0].formatted_address);
                            infowindow.open(map, marker);
                        }
                    }
                });
            });
        }

        function bindDataToForm(address, lat, lng) {
            document.getElementById('location').value = address;
            document.getElementById('lat').value = lat;
            document.getElementById('lng').value = lng;
//                                                console.log('location = ' + address);
//                                                console.log('lat = ' + lat);
//                                                console.log('lng = ' + lng);
        }

        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
@endsection
