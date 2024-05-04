@extends('layout.adminLayout')
@section('title') {{__('cp.gameServies')}}
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
                    <form method="post" action="{{url(app()->getLocale().'/admin/gameServies/'.$item->id)}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form" id="form_city">
                        {{ csrf_field() }}
                        {{ method_field('PATCH')}}


                        <div class="form-body">
                            <fieldset>
                           <div class="form-group">
                                    <label class="col-sm-2 control-label" >{{__('cp.game')}}
                                        <span class="symbol">*</span>
                                    </label>
                                    <div class="col-md-3">
                                        <select id="multiple2" class="form-control select"
                                                name="game_id" required>
                                            <option value="">{{__('cp.select')}}</option>
                                            @foreach($games as $game)
                                                <option value="{{$game->id}}" {{@$item->game->id == $game->id ? 'selected' : '' }}>
                                                     {{@$game->name}} ----> {{@$game->city_id == 0 ? __('cp.all') : @$game->city->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                            </div>
                            </fieldset>


 @if($item->api_id > 0)
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('cp.api')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="api"
                                               placeholder=" {{__('cp.api')}}"
                                               value="{{@$item->api->name}}" disabled="disabled">
                                    </div>
                                </div>
                            </fieldset>
                            
                            <!--<fieldset>-->
                            <!--    <div class="form-group">-->
                            <!--        <label class="col-sm-2 control-label">-->
                            <!--            {{__('cp.price')}}-->
                            <!--        </label>-->
                            <!--        <div class="col-md-6">-->
                            <!--            <input type="text" class="form-control" name="price"-->
                            <!--                   placeholder=" {{__('cp.price')}}"-->
                            <!--                   value="{{@$item->price}}" @if($item->api_id != 5 && $item->api_id != 2) disabled="disabled" @endif>-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--</fieldset>-->
                            
                            
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('cp.commission')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="commission"
                                               placeholder=" {{__('cp.commission')}}"
                                               value="{{@$item->commission}}">
                                    </div>
                                </div>
                            </fieldset>
                            
                            
                            
                        @endif

                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('cp.size')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="size"
                                               placeholder=" {{__('cp.size')}}"
                                               value="{{@$item->size}}" >
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
                                        <input type="number"  step="0.01" class="form-control" name="purchasing_price"
                                               placeholder=" {{__('cp.purchasing_price')}}"
                                               value="{{$item->purchasing_price}}" >
                                        @if($item->is_dollar == 1)
                                            {{$item->purchasing_price}} $ = ( {{$item->result($item->purchasing_price)}} ) ₺
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('cp.price')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="number"  step="0.01" class="form-control" name="price"
                                               placeholder=" {{__('cp.price')}}"
                                               value="{{$item->price}}" 
                                               @if($item->api_id != 5 && $item->api_id != 2 && $item->api_id > 0) disabled="disabled" @endif
                                               >
                                        @if($item->is_dollar == 1)
                                            {{$item->price}} $ = ( {{$item->result($item->price)}} ) ₺
                                        @endif
                                    </div>
                                </div>
                            </fieldset>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">{{__('cp.edit')}}</button>
                                        <a href="{{url(getLocal().'/admin/gameServies')}}" class="btn default">{{__('cp.cancel')}}</a>
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

