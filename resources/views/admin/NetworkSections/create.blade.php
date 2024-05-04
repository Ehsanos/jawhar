@extends('layout.adminLayout')
@section('title') {{__('اقسام الشبكات')}}
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
                    <form method="post" action="{{url(app()->getLocale().'/admin/NetworkSections/')}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form" id="form_city">
                        {{ csrf_field() }}
                        <input type="hidden" name="my_store_id" value="{{request()->my_store_id}}"/>
                        <div class="form-body">
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('cp.networks')}}
                                    </label>
                                    <div class="col-md-6">
                                        <select name="store_id" class="form-control" required>
                                            <option value="">{{ __('cp.select') }}</option>
                                            @foreach($store as $one)
                                                <option value="{{$one->id}}">{{$one->store_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label ">
                                        {{__('cp.username')}}
                                    </label>
                                    <div class="col-md-6">
                                        <select name="user_id" class="form-control" required>
                                            <option value="">{{ __('cp.select') }}</option>
                                            @foreach($user as $one)
                                                <option value="{{$one->id}}">{{$one->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" >
                                        نسبة التطبيق من الشبكات
                                        <span class="symbol">*</span>
                                    </label>
                                    <div class="col-md-3">
                                        <input onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" type="number" min="0" max="100" class="form-control" name="app_percent"
                                               value="{{old('app_percent')}}"  required>
                                    </div>
                                    <label class="col-sm-2 control-label" >
                                        نسبة التطبيق من تجديد الشبكات
                                        <span class="symbol">*</span>
                                    </label>
                                    <div class="col-md-3">
                                        <input onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" type="number" min="0" max="100" class="form-control" name="reNewNetwork_percent"
                                               value="{{old('reNewNetwork_percent')}}"  required>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>




                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('النقطة اليمنى العلوية')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" style="direction: ltr" readonly name="top_point"
                                               placeholder=" {{__('النقطة اليمنى العلوية')}}"
                                               value="{{ old('top_point') }}" >
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('النقطة اليسرى العلوية')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" style="direction: ltr" readonly name="bottom_point"
                                               placeholder=" {{__('النقطة اليسرى العلوية')}}"
                                               value="{{ old('bottom_point') }}" >
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('النقطة اليمنى السفلية')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" style="direction: ltr" readonly name="right_point"
                                               placeholder=" {{__('النقطة اليمنى السفلية')}}"
                                               value="{{ old('right_point') }}" >
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('النقطة اليسرى السفلية')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" style="direction: ltr" readonly name="left_point"
                                               placeholder=" {{__('النقطة اليسرى السفلية')}}"
                                               value="{{ old('left_point') }}" >
                                    </div>
                                </div>
                            </fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">
                                    {{__('الموقع')}}
                                </label>
                                <div class="col-md-12">
                                    <div id="map" style="width: 800px; height: 400px; border: 1px solid #ccc"></div>
                                    <span id="ID"></span>
                                </div>
                            </div>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">{{__('cp.add')}}</button>
                                        <a href="{{url(getLocal().'/admin/NetworkSections')}}" class="btn default">{{__('cp.cancel')}}</a>
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
        var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            osmAttrib = '&copy; <a href="http://openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            osm = L.tileLayer(osmUrl, { maxZoom: 18, attribution: osmAttrib }),
            map = new L.Map('map', { center: new L.LatLng(51.505, -0.04), zoom: 13 }),
            drawnItems = L.featureGroup().addTo(map);
        L.control.layers({
            'osm': osm.addTo(map),
            "google": L.tileLayer('http://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', {
                attribution: 'google'
            })
        }, { 'drawlayer': drawnItems }, { position: 'topleft', collapsed: false }).addTo(map);
        map.addControl(new L.Control.Draw({
            edit: {
                featureGroup: drawnItems,
                edit: false,
                poly: {
                    allowIntersection: false
                }
            },
            draw: {
                polygon: false,
                polyline: false,
                rectangle: true,
                circle: false,
                marker: true,
                circlemarker: false

            }
        }));

        map.on(L.Draw.Event.CREATED, function (event) {
            var type = event.layerType;
            var layer = event.layer;

            if (type === 'rectangle') {

                drawnItems.clearLayers();

                layer.on('mouseover', function () {
                    var tmp = document.getElementById('ID');
                    var c = getCorners(layer);
                    tmp.textContent = getString(c[0].toString()) + " " + getString(c[1].toString()) + " " + getString(c[2].toString()) + " " + getString(c[3].toString());
                    $("[name='top_point']").val(getString(c[0].toString()));
                    $("[name='bottom_point']").val(getString(c[1].toString()));
                    $("[name='right_point']").val(getString(c[2].toString()));
                    $("[name='left_point']").val(getString(c[3].toString()));

                });
            }

            if (type === 'marker') {
                map.on('click', function(e) {
                    var lat = e.latlng.lat;
                    var lng = e.latlng.lng;
                    layer.bindPopup(
                        "Latitude : " + lat + "<br>Longitude : " + lng);
                });

            }


            drawnItems.addLayer(layer);
        });

        function getCorners(layer) {
            const corners = layer.getBounds();

            const northwest = corners.getNorthWest();
            const northeast = corners.getNorthEast();
            const southeast = corners.getSouthEast();
            const southwest = corners.getSouthWest();

            return [northwest, northeast, southeast, southwest];
        }

        function getString(my_str) {
            my_str = my_str.replace('LatLng(', '');
            my_str = my_str.replace(')', '');
            return my_str;
        }

    </script>
@endsection

