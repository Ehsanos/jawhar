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
                    <form method="post" action="{{url(app()->getLocale().'/admin/gameRequest/'.@$order->id)}}"
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
                                   <b>{{ucwords(__('cp.mobile'))}}: </b>
                                </label>
                                <div class="col-md-2">
                                    <label class="control-label" for="">{{@$order->mobile}}</label>
                                </div>

                                

                            </div>
                               <div class="form-group">
                               
                                <label class="col-sm-2 control-label" for="">
                                  <b> {{__('cp.address')}}: </b>
                                </label>
                                <div class="col-md-2">
                                    <label class="control-label" for="">{{@$order->user->address}}</label>
                                </div>

                                <label class="col-sm-2 control-label" for="">
                                  <b> {{__('cp.note')}}: </b>
                                </label>
                                <div class="col-md-2">
                                    <label class="control-label" for="">{{@$order->note}}</label>
                                </div>


                                <div class="form-group">
                                <label class="col-sm-2 control-label" for="">
                                   <b>{{ucwords(__('cp.price'))}} {{ucwords(__('cp.total'))}}: </b>
                                </label>
                                <div class="col-md-2">
                                    <label class="control-label" for="">{{@$order->price}}</label>
                                </div>
                                


                            </div>
                        </fieldset>
                        <div class="row">
                            <table class="table table-striped table-bordered  table-checkable order-column" >
                                <thead>
                                <tr>
                                    <th> {{ucwords(__('cp.game'))}}</th>
                    <th> {{ucwords(__('cp.size'))}}</th>
                                                        <th> ID </th>

                    <th> {{ucwords(__('cp.price'))}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr class="odd gradeX">
                        <td><span>{{@$order->game->name}}</span></td>
                        <td>{{@$order->servies->size}}</td>
                                                <td>{{@$order->user_game_id}}</td>

                                        <td>{{@$order->price}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                <span class="symbol">سيصل هذا المحتوى للمستخدم في حالة الطلب المكتمل فقط</span>                                
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

                        <fieldset style="margin-top:40px">
                            <div class="form-group" id="gover_option">
                                <label class="control-label col-md-3">
                                    <b>{{__('cp.order_status')}}</b>
                                </label>
                                <div class="col-md-3">
                                    <select id="multiple2" class="form-control"
                                            name="status" {{(@$order->status ==2 || @$order->status == 3) ? 'disabled': ''}}>
                                            
                                        <option value="">{{__('cp.select')}}</option>
                                        <option value="0">{{__('cp.preparing')}}</option>
                                        <option value="3">{{__('cp.cancel')}}</option>
                                        <option value="4">{{__('cp.refund')}}</option>
                                        <option value="2">{{__('cp.complete')}}</option>

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
                                    <a href="{{url(getLocal().'/admin/gameRequest')}}" class="btn default">{{__('cp.cancel')}}</a>
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
