@extends('layout.adminLayout')
@section('title') {{__('cp.edit')}}
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
                              style="color: #e02222 !important;">{{ucwords(__('cp.edit'))}} {{ucwords(__('cp.wifi'))}}</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <form method="post" action="{{url(app()->getLocale().'/admin/wifi/'.$wifi->id)}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form" id="form">
                        {{ csrf_field() }}
                        {{ method_field('PATCH')}}
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="category">{{__('cp.city')}}  <span class="symbol">*</span></label>
                                    <div class="col-md-6">
                                    <select class="form-control" id="city" name="city_id">
                                        <option value="">{{__('cp.select')}}</option>
                                    @foreach ($cities as $city)

                                    <option value="{{ $city->id }}" @if($city->id == $wifi->city_id) selected @endif>{{$city->name }}</option> 
                                    
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
                                               value="{{ old('name', $wifi->name) }}" required>
                                    </div>
                                </div>
                        </fieldset>





                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn green">{{__('cp.submit')}}</button>
                                    <a href="{{url(getLocal().'/admin/wifi')}}" class="btn default">{{__('cp.cancel')}}</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>







@endsection
@section('script')
    <script>

        $('#edit_image').on('change', function (e) {

            readURL(this, $('#editImage'));

        });

    </script>

@endsection