@extends('layout.adminLayout')
@section('title') {{__('باقات شبكات الموبيل ')}}
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
                    <form method="post" action="{{url(app()->getLocale().'/admin/MobileNetworkPackages/'.$item->id)}}"
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
                                    <label class="col-sm-2 control-label">
                                        {{__('اسم الشبكة')}}
                                    </label>
                                    <div class="col-md-6">
                                        <select name="mobile_companies_id" class="form-control">
                                            <option value="">{{__('cp.select')}}</option>
                                            @foreach($cobilecompany as $one)
                                                <option value="{{$one->id}}" {{ $one->id == old('mobile_companies_id',$item->mobile_companies_id) ? 'selected':''}}>{{$one->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group" id="is_dollar">
                                    <label class="col-sm-2 control-label" >العملة
                                    </label>
                                    <div class="col-md-6">
                                        <select id="multiple2" class="form-control select is_dollar"
                                                name="is_dollar" required>
                                            <option value="">{{__('cp.select')}}</option>

                                            <option value="0" @if($item->is_dollar == 0) selected @endif >  سعر المنتج بالتركي     </option>
                                            <option value="1"@if($item->is_dollar == 1) selected @endif >  سعر المنتج بالدولار     </option>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('cp.purchasing_price')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="purchasing_price"
                                               placeholder=" {{__('cp.purchasing_price')}}"
                                               value="{{@$item->purchasing_price}}" >
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('cp.price')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="price"
                                               placeholder=" {{__('cp.price')}}"
                                               value="{{@$item->price}}" >
                                    </div>
                                </div>
                            </fieldset>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">{{__('cp.edit')}}</button>
                                        <a href="{{url(getLocal().'/admin/MobileNetworkPackages')}}" class="btn default">{{__('cp.cancel')}}</a>
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
