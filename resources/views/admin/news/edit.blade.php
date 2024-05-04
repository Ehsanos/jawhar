@extends('layout.adminLayout')
@section('title') {{__('cp.news')}}
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
                              style="color: #e02222 !important;">{{__('cp.edit')}} {{__('cp.news')}}</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <form method="post" action="{{url(app()->getLocale().'/admin/news/'.$item->id)}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form">
                        {{ csrf_field() }}
                        {{ method_field('PATCH')}}
                        <div class="form-body">
                                <fieldset>
                                    <div class="form-group">

                                        <label class="col-sm-2 control-label" for="title">
                                            {{__('cp.title')}}
                                            <span class="symbol">*</span>
                                        </label>
                                        <div class="col-md-6">
                                            <input type="texta" class="form-control" name="title"
                                                   placeholder=" {{__('cp.title')}}"
                                                   id="titel"
                                                   value="{{ @$item->title}}" required>
                                        </div>
                                    </div>

                                </fieldset>
                                <fieldset>
                                    <div class="form-group">

                                        <label class="col-sm-2 control-label" for="title">
                                            {{__('cp.link')}}
                                            <span class="symbol">*</span>
                                        </label>
                                        <div class="col-md-6">
                                            <input type="url" class="form-control" name="link"
                                                   placeholder=" {{__('cp.link')}}"
                                                   id="link"
                                                   value="{{ @$item->link}}" required>
                                        </div>
                                    </div>

                                </fieldset>
    


                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">{{__('cp.edit')}}</button>
                                        <a href="{{url(getLocal().'/admin/news')}}" class="btn default">{{__('cp.cancel')}}</a>
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

