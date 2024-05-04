@extends('layout.adminLayout')
@section('title') {{ucwords(__('cp.files'))}}
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
                              style="color: #e02222 !important;"> {{__('cp.add')}} </span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <form method="post" action="{{url(app()->getLocale().'/admin/apk/store')}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form" id="form" >
                        {{ csrf_field() }}

                          
                            <fieldset>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            {{__('cp.file')}}
                                            <span class="symbol">*</span>
                                        </label>
                                        <div class="col-md-6">
                                            
                                             <input type="file" name="file" class="form-control" required>
                                        
                                        </div>
                                    </div>
                            </fieldset>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        
                                        <button type="submit" class="btn green">{{__('cp.submit')}}</button>
                                        <a href="{{url(getLocal().'/admin/apk')}}" class="btn default">{{__('cp.cancel')}}</a>
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
