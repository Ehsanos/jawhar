@extends('layout.adminLayout')
@section('title') {{__('cp.details')}}
@endsection
@section('css')
    <style>
        .stamp {
            position: absolute;
            top: 180px;
            right: 900px;
            background: url({{url('uploads/images/products/draft.png')}}) no-repeat;
            width: 200px;
            height: 180px;
        }

        .completed {
            position: absolute;
            top: 190px;
            right: 885px;
            background: url({{url('uploads/images/products/completed.png')}}) no-repeat;
            width: 200px;
            height: 180px;
        }

    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-settings font-dark"></i>
                        <span class="caption-subject font-dark sbold uppercase" style="color: #e02222 !important;">{{__('cp.details')}} ({{__('cp.order_date').": ".@$AshabRequest->created_at}})</span>
                    </div>
                </div>
                <form method="post" action="{{url(app()->getLocale().'/admin/AshabRequest')}}"
                      enctype="multipart/form-data" class="form-horizontal" role="form" id="form_company">
                    {{ csrf_field() }}
                    <input type="hidden" class="form-control" name="id"  value="{{old('id',@$AshabRequest->id)}}">
                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="">
                                <b>{{ucwords(__('cp.customer_name'))}}: </b>
                            </label>
                            <div class="col-md-2">
                                <label class="control-label" for="">{{@$AshabRequest->user->name}}  </label>
                            </div>
                            <label class="col-sm-2 control-label" for="">
                                <b>{{ucwords(__('رقم الطلب '))}}: </b>
                            </label>
                            <div class="col-md-2">
                                <label class="control-label" for="">{{@$AshabRequest->order_id}}</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="">
                                <b> {{__('cp.price')}}: </b>
                            </label>
                            <div class="col-md-2">
                                <label class="control-label" for="">{{@$AshabRequest->price}}</label>
                            </div>
                            <label class="col-sm-2 control-label" for="">
                                <b> {{__('حالة الطلب لدا اصحاب')}}: </b>
                            </label>
                            <div class="col-md-2">
                                <label class="control-label" for="">
                                    <?php
                                    if($AshabRequest->status_from_ashab == "completed")
                                    {
                                        echo "مكتمل";
                                    }else
                                        if($AshabRequest->status_from_ashab == "processing")
                                        {
                                            echo "قيد المعالجة";
                                        }
                                        else
                                        {
                                            echo $AshabRequest->status_from_ashab;
                                        }
                                    ?>
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="">
                                    <b> {{__('حالة الطلب لدينا ')}}: </b>
                                </label>
                                <div class="col-md-2">
                                    <label class="control-label" for="">
                                        @if(@$AshabRequest->status == 0){{__('cp.complete')}} @endif
                                        @if(@$AshabRequest->status == 1){{__('cp.cancel')}} @endif
                                    </label>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">
                                {{__('الرد القادم من اصحاب ')}}
                            </label>
                            <div class="col-md-6">
                                <textarea id="card_id" style="direction: ltr" readonly name="card_id" rows="4" cols="50">{{@$AshabRequest->text}}</textarea>
                            </div>
                        </div>
                    </fieldset>


                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">
                                {{__('الرد الذي سيتلقاه الزبون ')}}
                            </label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="my_text"  value="{{old('my_text')}}">
                            </div>
                        </div>
                    </fieldset>

                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                @if(@$AshabRequest->status == 0)
                                    <button type="submit" class="btn green">{{__('cp.submit')}}</button>
                                    <a href="{{url(getLocal().'/admin/AshabRequest')}}"
                                       class="btn default">{{__('cp.cancel')}}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            $("#invoice_discount").on("input", function (evt) {
                var self = $(this);
                self.val(self.val().replace(/[^0-9\.]/g, ''));
                if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) {
                    evt.preventDefault();
                }
            });
        });
    </script>
@endsection
