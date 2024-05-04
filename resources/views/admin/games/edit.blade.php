@extends('layout.adminLayout')
@section('title') {{__('cp.games')}}
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
                    <form method="post" action="{{url(app()->getLocale().'/admin/games/'.$item->id)}}"
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
                                            {{__('cp.city')}}
                                        </label>
                                        <div class="col-md-6">
                                            <select name="city_id" class="form-control">
                                                <option value="0">{{__('cp.all')}}</option>
                                                @foreach($cities as $one)
                                                    <option value="{{$one->id}}" {{ $one->id == old('city_id',$item->city_id) ? 'selected':''}}>{{$one->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                                
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label ">
                                            {{__('cp.is_game')}}
                                        </label>
                                        <div class="col-md-6">
                                            <select name="is_game_player" class="form-control" required>
                                                <option
                                                    value="1" {{old('is_game_player' , $item->is_game_player) == '1'?'selected':''}}>
                                                    {{__('cp.yes')}}
                                                </option>
                                                <option
                                                    value="0" {{old('is_game_player' , $item->is_game_player) == '0'?'selected':''}}>
                                                    {{__('cp.No')}}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label ">
                                            {{__('cp.quantity')}}
                                        </label>
                                        <div class="col-md-6">
                                            <select name="is_quantity" class="form-control" required>
                                                <option
                                                    value="1" {{old('is_quantity' , $item->is_quantity) == '1'?'selected':''}}>
                                                    {{__('cp.yes')}}
                                                </option>
                                                <option
                                                    value="0" {{old('is_quantity' , $item->is_quantity) == '0'?'selected':''}}>
                                                    {{__('cp.No')}}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('cp.min_quantity')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="min_quantity"
                                               placeholder=" {{__('cp.min_quantity')}}"
                                               value="{{@$item->min_quantity}}" >
                                    </div>
                                </div>
                            </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label ">
                                            {{__('cp.status')}}
                                        </label>
                                        <div class="col-md-6">
                                            <select name="status" class="form-control" required>
                                                <option
                                                    value="active" {{old('status' , $item->status) == 'active'?'selected':''}}>
                                                    {{__('cp.active')}}
                                                </option>
                                                <option
                                                    value="not_active" {{old('status' , $item->status) == 'not_active'?'selected':''}}>
                                                    {{__('cp.not_active')}}
                                                </option>
                                            </select>
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
                                        <a href="{{url(getLocal().'/admin/games')}}" class="btn default">{{__('cp.cancel')}}</a>
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

