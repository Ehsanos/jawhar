@extends('layout.adminLayout')
@section('title') {{__('cp.requestRenewCard')}}
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
                              style="color: #e02222 !important;">{{__('cp.edit')}} {{__('cp.requestRenewCard')}}</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <form method="post" action="{{url(app()->getLocale().'/admin/requestRenewCard/'.$item->id)}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form">
                        {{ csrf_field() }}
                        {{ method_field('PATCH')}}
                        <div class="form-body">
                                <fieldset>
                                    <div class="form-group">

                                        <label class="col-sm-2 control-label" for="title">
                                            {{__('cp.name')}}
                                            <span class="symbol">*</span>
                                        </label>
                                        <div class="col-md-6">

                                                   {{ @$item->userName->name}}
                                        </div>
                                    </div>
                                    <div class="form-group">

                                        <label class="col-sm-2 control-label" for="title">
                                            {{__('cp.mobile')}}
                                            <span class="symbol">*</span>
                                        </label>
                                        <div class="col-md-6">

                                                   {{ @$item->userName->mobile}}
                                        </div>
                                    </div>
                                    <div class="form-group">

                                        <label class="col-sm-2 control-label" for="title">
                                            {{__('cp.balance')}}
                                            <span class="symbol">*</span>
                                        </label>
                                        <div class="col-md-6">

                                                   {{ @$item->balance}}
                                        </div>
                                    </div>
                                    <div class="form-group">

                                        <label class="col-sm-2 control-label" for="title">
                                            {{__('cp.wifi')}}
                                            <span class="symbol">*</span>
                                        </label>
                                        <div class="col-md-6">

                                                   {{ @$item->wifi->name}}
                                        </div>
                                    </div>
                                    <div class="form-group">

                                        <label class="col-sm-2 control-label" for="title">
                                            {{__('cp.networks')}}
                                            <span class="symbol">*</span>
                                        </label>
                                        <div class="col-md-6">

                                                   {{ @$item->network->name}}
                                        </div>
                                    </div>

                                </fieldset>
                            <fieldset>
                                <div class="form-group" id="home_club">
                                    <label class="col-sm-2 control-label" >{{__('cp.status')}}
                                        <span class="symbol">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <select id="multiple2" class="form-control select2"
                                                name="action" required>
                                            <option value="">{{__('cp.select')}}</option>
                                            <option   {{ @$item->action == "0" ? "selected" : ""}}  value="0"> {{__('cp.new')}}</option>
                                            <option {{ @$item->action == "1" ? "selected" : ""}} value="1"> {{__('cp.done')}}</option>
                                            <option {{ @$item->action == "2" ? "selected" : ""}} value="2"> {{__('cp.reject')}}</option>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>


                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        @if($item->action != "1" && $item->action != "2")
                                        <button type="submit" class="btn green">{{__('cp.edit')}}</button>
                                        @endif
                                        <a href="{{url(getLocal().'/admin/requestRenewCard')}}" class="btn default">{{__('cp.cancel')}}</a>
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

