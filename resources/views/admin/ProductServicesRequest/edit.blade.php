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
                        <span class="caption-subject font-dark sbold uppercase" style="color: #e02222 !important;">{{__('cp.details')}} ({{__('cp.order_date').": ".@$order->created_at}})</span>
                    </div>
                </div>
                <form method="post" action="{{url(app()->getLocale().'/admin/ProductServiceRequests/'.@$order->id)}}"
                      enctype="multipart/form-data" class="form-horizontal" role="form" id="form_company">
                    {{ csrf_field() }}
                    {{ method_field('PATCH')}}
                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="">
                                <b>{{ucwords(__('cp.customer_name'))}}: </b>
                            </label>
                            <div class="col-md-2">
                                <label class="control-label" for="">{{@$order->user->name}}  </label>
                            </div>
                            <label class="col-sm-2 control-label" for="">
                                <b>{{ucwords(__('cp.servies'))}}: </b>
                            </label>
                            <div class="col-md-2">
                                <label class="control-label" for="">{{@$order->productService->name}}</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="">
                                <b> {{__('cp.address')}}: </b>
                            </label>
                            <div class="col-md-2">
                                <label class="control-label" for="">{{@$order->city->name}}</label>
                            </div>
                            <label class="col-sm-2 control-label" for="">
                                <b> {{__('cp.order_id')}}: </b>
                            </label>
                            <div class="col-md-2">
                                <label class="control-label" for="">{{@$order->id}}</label>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="">
                                    <b> سعر البيع: </b>
                                </label>
                                <div class="col-md-2">
                                    <label class="control-label" for="">{{@$order->productService->price}}</label>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="row">
                        <table class="table table-striped table-bordered  table-checkable order-column">
                            <thead>
                            <tr>
                                <th> {{ucwords(__('cp.productServices'))}}</th>
                                <th> {{ucwords(__('الباقة'))}}</th>

                            </tr>
                            </thead>
                            <tbody>
                            <tr class="odd gradeX">
                                <td><span>{{@$order->productService->productService->name}}</span></td>
,
                                    @if(@$order->status == 0)
                                    <td>{{ucwords(__('قيد التنفيذ'))}} </td>
                                    @elseif(@$order->status == 1)
                                    <td>  {{@$order->number}}</td>
                                    @elseif(@$order->status == 2)
                                    <td> {{ucwords(__(' مرفوض'))}}</td>
                                @endif
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">
                                {{__('الرقم المباع')}}
                            </label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="number" min="0"  value="{{old('number')}}">
                            </div>
                        </div>
                    </fieldset>
                    <fieldset style="margin-top:40px">
                        <div class="form-group" id="gover_option">
                            <label class="control-label col-md-3">
                                <b>{{__('cp.order_status')}}</b>
                            </label>
                            <div class="col-md-3">
                                <select id="multiple2" class="form-control"
                                        name="status" {{(@$order->status ==2 ) ? 'disabled': ''}}>
                                    @if(@$order->status == 0)
                                        <option value="1">{{__('cp.complete')}}</option>
                                        <option value="2">{{__('cp.cancel')}}</option>
                                    @elseif(@$order->status == 1)
                                        <option value="1">{{__('cp.complete')}}</option>
                                    @elseif(@$order->status == 2 )
                                        <option value="2">{{__('cp.cancel')}}</option>
                                    @endif
                                </select>
                                حالة الطلب الحالية :
                                @if(@$order->status == 0){{__('cp.new')}} @endif
                                @if(@$order->status == 1){{__('cp.complete')}} @endif
                                @if(@$order->status == 2){{__('cp.cancel')}} @endif
                            </div>
                        </div>
                    </fieldset>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                @if(@$order->status == 0)
                                    <button type="submit" class="btn green">{{__('cp.submit')}}</button>
                                @endif
                                <a href="{{url(getLocal().'/admin/ProductServiceRequests')}}"
                                   class="btn default">{{__('cp.cancel')}}</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    @if(@$order->status != 2)
        <div class="stamp"></div>
    @else
        <div class="completed"></div>
        @endif
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
