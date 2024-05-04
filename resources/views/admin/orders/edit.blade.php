@extends('layout.adminLayout')
@section('title') {{__('cp.details')}}
@endsection
@section('css')
<style>
    .stamp
{
	position:absolute; top: 180px; right:900px; background:url({{url('uploads/images/products/draft.png')}}) no-repeat;
	width:200px;
	height:180px;
}

.completed
{
	position:absolute; top: 190px; right:885px; background:url({{url('uploads/images/products/completed.png')}}) no-repeat;
	width:200px;
	height:180px;
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
                      <span class="caption-subject font-dark sbold uppercase" style="color: #e02222 !important;">{{__('cp.details')}} ({{__('cp.order_date').": ".@$order->created_at->toDateString()." ".__('cp.order_time').": ".@$order->created_at->toTimeString()}})</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <form method="post" action="{{url(app()->getLocale().'/admin/orders/'.@$order->id)}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form" id="form_company">
                        {{ csrf_field() }}
                        {{ method_field('PATCH')}}

                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="">
                                    <b>{{ucwords(__('cp.customer_name'))}}: </b>
                                </label>
                                <div class="col-md-2">
                                    <label class="control-label" for="">{{@$order->user->name}}  {{@$order->customer_name}}</label>
                                </div>

                                <label class="col-sm-2 control-label" for="">
                                   <b>{{ucwords(__('cp.mobile'))}}: </b>
                                </label>
                                <div class="col-md-2">
                                    <label class="control-label" for="">{{@$order->mobile}}</label>
                                </div>
                                
                                 <label class="col-sm-2 control-label" for="">
                                   <b>{{__('cp.city')}}: </b>
                                </label>
                                <div class="col-md-2">
                                    <label class="control-label" for="">{{@$order->city->name}}</label>
                                </div>

                            </div>
                               <div class="form-group">
                               
                                <label class="col-sm-2 control-label" for="">
                                  <b> {{__('cp.address')}}: </b>
                                </label>
                                <div class="col-md-2">
                                    <label class="control-label" for="">{{@$order->address_name}}</label>
                                </div>
                                <label class="col-sm-2 control-label" for="">
                                  <b> {{__('cp.fullAddress')}}: </b>
                                </label>
                                <div class="col-md-2">
                                    <label class="control-label" for="">{{@$order->full_address}}</label>
                                </div>
                                <label class="col-sm-2 control-label" for="">
                                  <b> خط الطول والعرض: </b>
                                </label>
                                <div class="col-md-2">
                                    <label class="control-label" for=""><span>{{@$order->latitude}}</span> <br><span>{{@$order->longitude}}</span> </label>
                                </div>
                                <label class="col-sm-2 control-label" for="">
                                  <b> {{__('cp.note')}}: </b>
                                </label>
                                <div class="col-md-2">
                                    <label class="control-label" for="">{{@$order->note}}</label>
                                </div>
                         <label class="col-sm-2 control-label" for="">
                                   <b>{{__('cp.payment')}}: </b>
                                </label>
                                <div class="col-md-2">
                                    <label class="control-label" for="">
                                        @if (@$order->payment == 1)
                                    عند الاستلام
                                        @endif
                                        @if (@$order->payment == 0)
                                    المحفظة
                                        @endif
                                    </label>
                                </div>
                         <label class="col-sm-2 control-label" for="">
                                   <b>{{__('cp.discount_code')}}: </b>
                                </label>
                                <div class="col-md-2">
                                    <label class="control-label" for="">
                                        @if (@$order->code_name != null)
                                        {{@$order->code_name}}
                                        @endif
                                    </label>
                                </div>
                         <label class="col-sm-2 control-label" for="">
                                   <b>{{__('cp.discount_code')}}: </b>
                                </label>
                                <div class="col-md-2">
                                    <label class="control-label" for="">
                                        @if (@$order->code_name != null)
                                        {{@$order->code_name}}
                                        @endif
                                    </label>
                                </div>
                                
                                <label class="col-sm-2 control-label" for="">
                                   <b>{{__('cp.discount%')}}: </b>
                                </label>
                                <div class="col-md-2">
                                    <label class="control-label" for="">
                                        @if (@$order->code_name != null)
                                            {{@$order->	discount_code}}%
                                        @endif
                                    </label>
                                </div>
                            </div>

                                <div class="form-group">
                                <label class="col-sm-2 control-label" for="">
                                   <b>{{ucwords(__('cp.price'))}} {{ucwords(__('cp.total'))}}: </b>
                                </label>
                                <div class="col-md-2">
                                    <label class="control-label" for="">{{@$order->total_price}}</label>
                                </div>
                                
                                <label class="col-sm-2 control-label" for="">
                                   <b>{{ucwords(__('cp.invoice_discount'))}}: </b>
                                </label>
                                <div class="col-md-2">
                                    <label class="control-label" for="">    <input type="text" class="form-control" name="invoice_discount" id="invoice_discount" value="{{@$order->invoice_discount}}" {{(@$order->status ==2) ? 'disabled': ''}} />
                                    </label>
                                </div>
                                
                                <label class="col-sm-2 control-label" for="">
                                   <b>{{ucwords(__('cp.net'))}}: </b>
                                </label>
                                <div class="col-md-2">
                                    <label class="control-label" for="">{{@$order->total_price - @$order->invoice_discount}}</label>
                                </div>
                                

                            </div>
                        </fieldset>
                        <div class="row">
                            <table class="table table-striped table-bordered  table-checkable order-column" >
                                <thead>
                                <tr>
                                    <th> #</th>
                                    <th> {{ucwords(__('cp.product_image'))}}</th>
                    <th> {{ucwords(__('cp.name_product'))}}</th>
                    <th> {{ucwords(__('cp.subcategory'))}}</th>
                    <th> {{ucwords(__('cp.category'))}}</th>  
                                    <th> {{ucwords(__('cp.quantity'))}}</th>
                                    <th> {{ucwords(__('cp.price'))}}</th>
                                    <th> {{ucwords(__('cp.total'))}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($products as $one)
                                    <tr class="odd gradeX">
                                        <td>{{$loop->iteration}}</td>
                                        <td><img src="{{@$one->product->image}}" width="75px" height="75px"></td>
                        <td><span>{{@$one->product->name}}</span></td>
                        <td>{{@$one->product->subcategory->name}}</td>
                        <td>{{@$one->product->subcategory->category->name}}</td>
                        <td>{{@$one->quantity}}</td>
                                        <td>{{@$one->price}}</td>
                                        <td>{{@$one->price * @$one->quantity}}</td>

                                    </tr>
                                @empty
                                    {{__('cp.no')}}
                                @endforelse
                                </tbody>
                            </table>
                        </div>


                        <fieldset style="margin-top:40px">
                            <div class="form-group" id="gover_option">
                                <label class="control-label col-md-3">
                                    <b>{{__('cp.invoice_discount')}}</b>
                                </label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="invoice_discount2" id="invoice_discount" value="{{@$order->invoice_discount}}" {{(@$order->status ==2) ? 'disabled': ''}} />
                                </div>
                            </div>
                            <div class="form-group" id="gover_option">
                                <label class="control-label col-md-3">
                                    <b>{{__('cp.order_status')}}</b>
                                </label>
                                <div class="col-md-3">
                                    <select id="multiple2" class="form-control"
                                            name="status" {{(@$order->status ==2 || @$order->status == 3) ? 'disabled': ''}}>
                                            
                                        @if(@$order->status == -1)
                                        <option value="">{{__('cp.select')}}</option>
                                        <option value="0">{{__('cp.preparing')}}</option>
                                        <option value="3">{{__('cp.cancel')}}</option>
                                        <option value="4">{{__('cp.refund')}}</option>
                                        @elseif(@$order->status == 0)
                                         <option value="">{{__('cp.select')}}</option>
                                        <option value="1">{{__('cp.onDelivery')}}</option>
                                         <option value="3">{{__('cp.cancel')}}</option>
                                         <option value="4">{{__('cp.refund')}}</option>
                                        @elseif(@$order->status == 1 )
                                         <option value="">{{__('cp.select')}}</option>
                                        <option value="2">{{__('cp.complete')}}</option>
                                        @endif
                             

                                    </select>
                                               حالة الطلب الحالية : 
                                        @if(@$order->status == -1){{__('cp.new')}} @endif
                                        @if(@$order->status == 0){{__('cp.preparing')}} @endif
                                        @if(@$order->status == 1){{__('cp.onDelivery')}} @endif
                                        @if(@$order->status == 2){{__('cp.complete')}} @endif
                                        @if(@$order->status == 3){{__('cp.cancel')}} @endif
                                        @if(@$order->status == 4){{__('cp.refund')}} @endif
                                </div>
                            </div>
                        </fieldset>


                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                   @if(@$order->status == -1 || @$order->status == 0 || @$order->status == 1) <button type="submit" class="btn green">{{__('cp.submit')}}</button> @endif
                                    <a href="{{url(getLocal().'/admin/orders')}}" class="btn default">{{__('cp.cancel')}}</a>
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
        
    $("#invoice_discount").on("input", function(evt) {
   var self = $(this);
   self.val(self.val().replace(/[^0-9\.]/g, ''));
   if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) 
   {
     evt.preventDefault();
   }
 });
    });
       
    </script>
@endsection
