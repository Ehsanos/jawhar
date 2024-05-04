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
                              style="color: #e02222 !important;">{{ucwords(__('cp.edit'))}} {{ucwords(__('cp.network'))}}</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <form method="post" action="{{url(app()->getLocale().'/admin/networks/'.$network->id)}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form" id="form">
                        {{ csrf_field() }}
                        {{ method_field('PATCH')}}

                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="name">
                                    {{__('cp.name')}}
                                    <span class="symbol">*</span>
                                </label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="name"

                                           id="name"
                                           value="{{ old('name', $network->name) }}" required>
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

                                        <option value="0" @if($network->is_dollar == 0) selected @endif >  سعر المنتج بالتركي     </option>
                                        <option value="1"@if($network->is_dollar == 1) selected @endif >  سعر المنتج بالدولار     </option>
                                    </select>
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
                                           value="{{$network->price}}" >
                                    @if($network->is_dollar == 1)
                                        {{$network->price}} $ = ( {{$network->result($network->price)}} ) ₺
                                    @endif
                                </div>
                            </div>
                        </fieldset>

                        {{--                        <fieldset>--}}
                        {{--                            <div class="form-group">--}}


                        {{--                                <label class="col-sm-2 control-label" for="order">--}}
                        {{--                                    {{__('cp.price')}}--}}
                        {{--                                    <span class="symbol">*</span>--}}
                        {{--                                </label>--}}

                        {{--                                <div class="col-md-4">--}}
                        {{--                                    <input type="text" class="form-control" name="price" min="0"   value="{{$network->price}}" required>--}}
                        {{--                                </div>--}}
                        {{--                            </div>--}}
                        {{--                        </fieldset>--}}

                        <fieldset>
                            <legend>{{__('cp.image')}}</legend>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-3">
                                    <div class="fileinput-new thumbnail"
                                         onclick="document.getElementById('edit_image').click()"
                                         style="cursor:pointer">
                                        <img src="{{url($network->image)}}" id="editImage">
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
                                    <button type="submit" class="btn green">{{__('cp.submit')}}</button>
                                    <a href="{{url(getLocal().'/admin/networks')}}" class="btn default">{{__('cp.cancel')}}</a>
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