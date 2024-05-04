@extends('layout.adminLayout')
@section('title') API's
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
                    <form method="post" action="{{url(app()->getLocale().'/admin/apis/'.$item->id)}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form" id="form_city">
                        {{ csrf_field() }}
                        {{ method_field('PATCH')}}


                        <div class="form-body">

                            <fieldset>
                                    <div class="form-group">

                                        <label class="col-sm-2 control-label" for="order">

                                            {{__('cp.name')}}
                                        </label>

                                        <div class="col-md-6">
                                            <input type="text" class="form-control" 
                                                   value="{{ @$item->name}}" required>
                                        </div>
                                    </div>
                            </fieldset>

                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('cp.link')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control"
                                               placeholder=" {{__('cp.link')}}"
                                               value="{{@$item->url}}" >
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('cp.token')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control"
                                               placeholder=" {{__('cp.token')}}" name="token"
                                               value="{{@$item->token}}" >
                                    </div>
                                </div>
                            </fieldset>






                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">{{__('cp.edit')}}</button>
                                        <a href="{{url(getLocal().'/admin/apis')}}" class="btn default">{{__('cp.cancel')}}</a>
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



@endsection

