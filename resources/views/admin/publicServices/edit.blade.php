@extends('layout.adminLayout')
@section('title') {{__('cp.publicServices')}}
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
                    <form method="post" action="{{url(app()->getLocale().'/admin/publicServices/'.$item->id)}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form" id="form_city">
                        {{ csrf_field() }}
                        {{ method_field('PATCH')}}


                        <div class="form-body">
                            <fieldset>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            {{__('cp.main_service')}}
                                        </label>
                                        <div class="col-md-6">
                                            <select name="parent_id" class="form-control">
                                                @if(is_one_public_services_root())
                                                <option >{{__('cp.select')}}</option>
                                                @endif
                                                @if(isset($item->parent_id) && $item->parent_id != 0)
                                                @foreach($publicServices as $one)
                                                    <option value="{{$one->id}}" {{ $one->id == old('parent_id',@$item->parent_id) ? 'selected':''}}>{{$one->name}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>

                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('cp.name')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="name"
                                               value="{{@$item->name}}" >
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
                                                <option value="">{{__('cp.select')}}</option>
                                                @foreach($cities as $one)
                                                    <option value="{{$one->id}}" {{ $one->id == old('city_id',$item->city_id) ? 'selected':''}}>{{$one->name}}</option>
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
                                        <input type="number" class="form-control" name="purchasing_price"
                                               step="0.01"   value="{{@$item->purchasing_price}}" >
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('cp.price')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="number" class="form-control" name="price"
                                                step="0.01"   value="{{@$item->price}}" >
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>{{__('cp.image')}}</legend>
                                <div class="form-group {{ $errors->has('image') ? ' has-error' : '' }}">
                                    <div class="col-md-6 col-md-offset-3">
                                        @if ($errors->has('image'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('image') }}</strong>
                                            </span>
                                        @endif
                                        <div class="fileinput-new thumbnail"
                                             onclick="document.getElementById('edit_image').click()"
                                             style="cursor:pointer">
                                            <img src="@if($item->image){{$item->image}} @else  {{ url(admin_assets('/images/ChoosePhoto.png'))}} @endif" id="editImage" >
                                        </div>
                                        
                                        <div class="btn red"
                                             onclick="document.getElementById('edit_image').click()">
                                            <i class="fa fa-pencil"></i>
                                        </div>
                                        <input type="file" class="form-control" name="image"
                                               id="edit_image"
                                               style="display:none">
                                    </div>
                                </div>
                            </fieldset>                            
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">{{__('cp.edit')}}</button>
                                        <a href="{{url(getLocal().'/admin/publicServices')}}" class="btn default">{{__('cp.cancel')}}</a>
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

