@extends('layout.adminLayout')
@section('title') {{__('cp.requestMobileBalance')}}
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
                              style="color: #e02222 !important;">{{__('cp.edit')}} {{__('cp.requestMobileBalance')}}</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <form method="post" action="{{url(app()->getLocale().'/admin/requestMobileBalance/'.$item->id)}}"
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
    
                                                   {{ @$item->mobile}}
                                        </div>
                                    </div>
                                    <div class="form-group">

                                        <label class="col-sm-2 control-label" for="title">
                                            {{__('اسم الباقة')}}
                                            <span class="symbol">*</span>
                                        </label>
                                        <div class="col-md-6">

                                            {{ @$item->requestmobilebalance->name}}
                                        </div>
                                    </div>
                                    <div class="form-group">

                                        <label class="col-sm-2 control-label" for="title">
                                            {{__('cp.price')}}
                                            <span class="symbol">*</span>
                                        </label>
                                        <div class="col-md-6">
    
                                                   {{ @$item->balance}}
                                        </div>
                                    </div>
                                    <div class="form-group">

                                        <label class="col-sm-2 control-label" for="title">
                                            {{__('cp.mobileCompany')}}
                                            <span class="symbol">*</span>
                                        </label>
                                        <div class="col-md-6">
    
                                                   {{ @$item->mobileCompany->name}}
                                        </div>
                                    </div>

                                </fieldset>
              <legend>سيصل هذا المحتوى للمستخدم في حالة الطلب المكتمل فقط</legend>                  
                            <fieldset>
                               
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('cp.admin_response')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="admin_response"
                                               value="{{@$order->admin_response}}" >
                                    </div>
                                  
                                </div>

                            </fieldset>

              <legend>{{__('cp.status')}}</legend>                  
                                
                            <fieldset>
                                <div class="form-group" id="home_club">
                                    <label class="col-sm-2 control-label" >{{__('cp.status')}}
                                        <span class="symbol">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <select id="multiple2" class="form-control select2"
                                                name="action" required>
                                            <option value="">{{__('cp.select')}}</option>
                                                 <option value="0"> {{__('cp.new')}}</option>
                                                 <option value="1"> {{__('cp.inprogress')}}</option>
                                                 <option value="2"> {{__('cp.done')}}</option>
                                                 <option value="3"> {{__('cp.reject')}}</option>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>


                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">{{__('cp.edit')}}</button>
                                        <a href="{{url(getLocal().'/admin/requestMobileBalance')}}" class="btn default">{{__('cp.cancel')}}</a>
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

