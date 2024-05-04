@extends('layout.adminLayout')
@section('title') {{__('المصروف')}}
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
                    <form method="post" action="{{url(app()->getLocale().'/admin/Expenses/'.$item->id)}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form" id="form_city">
                        {{ csrf_field() }}
                        {{ method_field('PATCH')}}

                        <input type="hidden"  name="currency" value="{{$currency}}">
                        <div class="form-body">

                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('cp.city')}}
                                    </label>
                                    <div class="col-md-6">
                                        <select name="city_id" class="form-control" required>
                                            <option value="">{{__('cp.select')}}</option>
                                            @foreach($cities as $one)
                                                <option value="{{$one->id}}" {{ $one->id == old('city_id',$item->city_id) ? 'selected':''}}>{{$one->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('نوع المصروف')}}
                                    </label>
                                    <div class="col-md-6">
                                <select id="service_name" class="form-control select"
                                        name="service_name" required>
                                    <option value="">{{__('cp.select')}}</option>
                                    @for($i=0;$i<count(getAllServicesName_Expenses());$i++)
                                    <option value="{{$i}}" {{ $item->service_name == $i ? 'selected' : '' }}>{{ getServicesName_Expenses($i) }}</option>
                                    @endfor
                                </select>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('cp.details')}}
                                    </label>
                                    <div class="col-md-6">
                                        <textarea class="form-control" name="details"
                                                  placeholder=" {{__('cp.details')}}"
                                                  style="height: 100px" required>{{ old('details',@$item->details) }}</textarea>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('المبلغ')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="number" class="form-control" name="profit"
                                               placeholder=" {{__('المبلغ')}}"
                                               value="{{@$item->profit}}" step="any" required>
                                    </div>
                                </div>
                            </fieldset>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">{{__('cp.edit')}}</button>
                                        <a href="{{url(getLocal().'/admin/Expenses')}}" class="btn default">{{__('cp.cancel')}}</a>
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


@endsection

