@extends('layout.adminLayout')
@section('title') {{ucwords(__('cp.edit'))}}
@endsection
@section('css')
    <style>
        #map-canvas {
            width: 800px;
            height: 350px;

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
                              style="color: #e02222 !important;"> بيانات المالك </span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <form method="post" action="{{route('admin.stores.update',$store->id)}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form">
                        {{ csrf_field() }}
                        {{ method_field('PATCH')}}
                        <legend>بيانات المالك</legend>

                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="order">
                                    {{__('cp.name')}}
                                </label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="name" value="{{$user->name}}"
                                           disabled="">

                                </div>

                                <label class="col-sm-2 control-label" for="order">
                                    {{__('cp.email')}}
                                    <span class="symbol">*</span>
                                </label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="email" value="{{ $user->email }}"
                                           disabled="">
                                </div>
                            </div>
                        </fieldset>


                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="order">
                                    {{__('cp.mobile')}}
                                    <span class="symbol">*</span>
                                </label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="mobile"
                                           value="{{$user->mobile}}" disabled="">
                                </div>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="form-group {{ $errors->has('image') ? ' has-error' : '' }}">
                                <div class="col-md-2 col-md-offset-2">
                                    @if ($errors->has('image'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('image') }}</strong>
                                            </span>
                                    @endif
                                    <div class="fileinput-new thumbnail"
                                         onclick="document.getElementById('edit_image').click()"
                                         style="cursor:pointer">
                                        <img src="@if($user->image_profile){{$user->image_profile}} @else  {{ url(admin_assets('/images/ChoosePhoto.png'))}} @endif"
                                             id="editImage">
                                    </div>
                                    <div class="btn red"
                                         onclick="document.getElementById('edit_image').click()">
                                        <i class="fa fa-pencil"></i>
                                    </div>
                                    <input type="file" class="form-control" name="image_profile"
                                           id="edit_image"
                                           style="display:None">
                                </div>
                            </div>
                        </fieldset>

                        <legend>بيانات المتجر</legend>

                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">{{__('cp.type')}}
                                    <span class="symbol">*</span>
                                </label>
                                <div class="col-md-3">
                                    <select id="multiple2" class="form-control select"
                                            name="type">
                                        @if($store->type == 1)
                                            <option value="1" {{$store->type == 1 ? 'selected' : '' }}>{{__('cp.store')}} </option>
                                        @endif
                                        @if($store->type == 2)
                                            <option value="2" {{$store->type == 2 ? 'selected' : '' }}>{{__('cp.wifi')}} </option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">
                                    {{__('cp.store_name')}}
                                    <span class="symbol">*</span>
                                </label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="store_name"
                                           value="{{$store->store_name}}">

                                </div>

                                <label class="col-sm-2 control-label">
                                    {{__('cp.store_mobile')}}
                                    <span class="symbol">*</span>
                                </label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="store_mobile"
                                           value="{{$store->mobile}}">
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">{{__('cp.category')}}
                                    <span class="symbol">*</span>
                                </label>
                                <div class="col-md-3">
                                    <select id="multiple2" class="form-control select"
                                            name="store_category_id">
                                        <option value="">{{__('cp.select')}}</option>
                                        @foreach($categories as $storeCategory)
                                            <option value="{{$storeCategory->id}}" {{$store->store_category_id == $storeCategory->id ? 'selected' : '' }}>
                                                {{$storeCategory->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset>
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        نسبة التطبيق من الشبكات
                                    </label>
                                    <div class="col-md-3">
                                        <input onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"
                                               type="number" min="0" max="100" class="form-control" name="app_percent"
                                               value="{{$store->app_percent}}">
                                    </div>
                                    <label class="col-sm-2 control-label">
                                        نسبة التطبيق من تجديد الشبكات
                                    </label>
                                    <div class="col-md-3">
                                        <input onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"
                                               type="number" min="0" max="100" class="form-control"
                                               name="reNewNetwork_percent"
                                               value="{{$store->reNewNetwork_percent}}">
                                    </div>
                                </div>
                            </fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">
                                    {{__('cp.details')}}
                                    <span class="symbol">*</span>
                                </label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="details" value="{{$store->details}}">

                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">{{__('cp.city')}}
                                    <span class="symbol">*</span>
                                </label>
                                <div class="col-md-3">
                                    <select id="multiple2" class="form-control select"
                                            name="city_id">
                                        <option value="">{{__('cp.select')}}</option>
                                        @foreach($cities as $city)
                                            @if($admin_city_id == -1)
                                                <option value="{{$city->id}}" {{ $store->city_id == $city->id ? 'selected' : '' }}>
                                                    {{$city->name}}
                                                </option>
                                            @else
                                                @if($admin_city_id == $city->id)
                                                    <option value="{{$city->id}}" {{ $store->city_id == $city->id ? 'selected' : '' }}>
                                                        {{$city->name}}
                                                    </option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <div class="form-group" id="gover_option">
                                <label class="col-sm-2 control-label" >
                                    كل المدن
                                </label>
                                <div class="col-md-6">
                                    <input type="checkbox" id="all_cities" class="form-check-input" name="all_cities" {{ $store->all_cities == "1" ? "checked" : "" }}>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">
                                    {{__('cp.address')}}
                                    <span class="symbol">*</span>
                                </label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="address" value="{{$store->address}}">
                                </div>
                                <label class="col-sm-2 control-label">
                                    latitude
                                    <span class="symbol">*</span>
                                </label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="" value="{{$store->latitude}}"
                                           disabled="">

                                </div>
                                <label class="col-sm-2 control-label">
                                    longitude
                                    <span class="symbol">*</span>
                                </label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="" value="{{$store->longitude}}"
                                           disabled="">

                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>{{__('cp.location')}}</legend>
                            <div class="form-group">

                                <input type="text" id="searchmap">
                                <div id="map-canvas"></div>

                                <input type="hidden" id="latitude" data-id="{{$store->latitude}}"
                                       value="{{$store->latitude}}" class="form-control input-sm" name="lat">
                                <input type="hidden" id="longitude" data-id="{{$store->longitude}}"
                                       value="{{$store->longitude}}" class="form-control input-sm" name="lng">
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">{{__('cp.is_cash')}}
                                    <span class="symbol">*</span>
                                </label>
                                <div class="col-md-3">
                                    <select id="multiple2" class="form-control select"
                                            name="is_cash">
                                        <option value="0">{{__('cp.No')}}</option>
                                        <option value="1">{{__('cp.yes')}}</option>
                                    </select>
                                </div>
                                <label class="col-sm-2 control-label"> {{__('cp.is_wallet')}}
                                    <span class="symbol">*</span>
                                </label>
                                <div class="col-md-3">
                                    <select id="multiple2" class="form-control select"
                                            name="is_wallet">
                                        <option value="0">{{__('cp.No')}}</option>
                                        <option value="1">{{__('cp.yes')}}</option>
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"> {{__('cp.is_online')}}
                                    <span class="symbol">*</span>
                                </label>
                                <div class="col-md-3">
                                    <select id="multiple2" class="form-control select"
                                            name="is_online">
                                        <option value="0">{{__('cp.No')}}</option>
                                        <option value="1">{{__('cp.yes')}}</option>
                                    </select>
                                </div>

                            </div>
                        </fieldset>
                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"> {{__('cp.is_pickup')}}
                                    <span class="symbol">*</span>
                                </label>
                                <div class="col-md-3">
                                    <select id="multiple2" class="form-control select"
                                            name="is_pickup">
                                        <option value="0">{{__('cp.No')}}</option>
                                        <option value="1">{{__('cp.yes')}}</option>
                                    </select>
                                </div>
                                <label class="col-sm-2 control-label"> {{__('cp.is_delivery')}}
                                    <span class="symbol">*</span>
                                </label>
                                <div class="col-md-3">
                                    <select id="multiple2" class="form-control select"
                                            name="is_delivery">
                                        <option value="0">{{__('cp.No')}}</option>
                                        <option value="1">{{__('cp.yes')}}</option>
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                        <legend>{{__('cp.store')}} {{__('cp.logo')}}</legend>

                        <fieldset>
                            <div class="form-group {{ $errors->has('logo') ? ' has-error' : '' }}">
                                <div class="col-md-2 col-md-offset-2">
                                    @if ($errors->has('logo'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('logo') }}</strong>
                                            </span>
                                    @endif
                                    <div class="fileinput-new thumbnail"
                                         onclick="document.getElementById('edit_logo').click()"
                                         style="cursor:pointer">
                                        <img src="@if($store->logo){{$store->logo}} @else  {{ url(admin_assets('/images/ChoosePhoto.png'))}} @endif"
                                             id="editLogo"></div>
                                    <div class="btn red"
                                         onclick="document.getElementById('edit_logo').click()">
                                        <i class="fa fa-pencil"></i>
                                    </div>
                                    <input type="file" class="form-control" name="logo"
                                           id="edit_logo"
                                           style="display:None">
                                </div>
                            </div>
                        </fieldset>

                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn green">{{__('cp.submit')}}</button>
                                    <a href="{{url(getLocal().'/admin/stores')}}"
                                       class="btn default">{{__('cp.cancel')}}</a>
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
        $('#edit_logo').on('change', function (e) {
            readURL(this, $('#editLogo'));
        });

    </script>



    <script src="https://maps.googleapis.com/maps/api/js?key={{env('APIGoogleKey')}}&libraries=places"
            type="text/javascript"></script>
    <script>
        var latitude = $('#latitude').data("id");
        var longitude = $('#longitude').data("id");
        var map = new google.maps.Map(document.getElementById('map-canvas'), {
            center: {
                lat: latitude,
                lng: longitude
            },
            zoom: 9
        });
        var marker = new google.maps.Marker({
            position: {
                lat: latitude,
                lng: longitude
            },
            map: map,
            draggable: true
        });
        var searchBox = new google.maps.places.SearchBox(document.getElementById('searchmap'));
        google.maps.event.addListener(searchBox, 'places_changed', function () {
            var places = searchBox.getPlaces();
            var bounds = new google.maps.LatLngBounds();
            var i, place;
            for (i = 0; place = places[i]; i++) {
                bounds.extend(place.geometry.location);
                marker.setPosition(place.geometry.location); //set marker position new...
            }
            map.fitBounds(bounds);
            map.setZoom(15);
        });
        google.maps.event.addListener(marker, 'position_changed', function () {
            var lat = marker.getPosition().lat();
            var lng = marker.getPosition().lng();
            $('#latitude').val(lat);
            $('#longitude').val(lng);
        });
    </script>
@endsection
