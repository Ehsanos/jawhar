@extends('layout.adminLayout')
@section('title') {{__('cp.publicServicesRequest')}}
@endsection
@section('css')
@endsection
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-body">
            <div class="table-toolbar">
                <div class="table-toolbar">

                <div class="row">

                    <div class="col-sm-9">

                        <div class="btn-group">

                            <button class="btn sbold blue btn--filter">{{__('cp.filter')}}

                                <i class="fa fa-search"></i>

                            </button>
                            

                        </div>

                    </div>



                </div>

                <div class="box-filter-collapse">

                    <form class="form-horizontal" method="get" action="{{url(getLocal().'/admin/publicServicesRequest')}}">

                        <div class="row">

                            <div class="col-md-5">
                                <div class="form-group row">
                                    <label class="col-md-3 control-label">{{__('cp.status')}}</label>
                                    <div class="col-md-9">
                                        <select id="multiple2" class="form-control"
                                                name="statusUser">
                                            <option value="">{{__('cp.all')}}</option>
                                            <option value="-1">{{__('cp.new')}}</option>
                                            <option value="0">{{__('cp.preparing')}}</option>
                                            <option value="1">{{__('cp.onDelivery')}}</option>
                                            <option value="2">{{__('cp.complete')}}</option>
                                            <option value="3">{{__('cp.cancel')}}</option>
                                            <option value="4">{{__('cp.refund')}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">

                                <div class="row">

                                    <div class="col-md-offset-3 col-md-9">

                                        <button type="submit" class="btn sbold blue">{{__('cp.search')}}

                                            <i class="fa fa-search"></i>

                                        </button>

                                        <a href="{{url(app()->getLocale().'/admin/publicServicesRequest')}}" type="submit"

                                           class="btn sbold btn-default ">{{__('cp.reset')}}

                                            <i class="fa fa-refresh"></i>

                                        </a>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-5">
                                <div class="form-group row">
                                    <label class="col-md-3 control-label">{{__('cp.customer_name')}}</label>

                                    <div class="col-md-9">

                                        <input type="text" class="form-control" name="userName"

                                               placeholder="{{__('cp.customer_name')}}">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>

            </div>

                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="toolsTable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th> {{ucwords(__('cp.city'))}}</th>
                        <th> {{ucwords(__('cp.order_id'))}}</th>
                        <th> {{ucwords(__('cp.publicServices'))}}</th>
                        <th> {{ucwords(__('cp.customer_name'))}}</th>
                        <th> {{ucwords(__('cp.mobile'))}}</th>
                        <th> {{ucwords(__('cp.location'))}}</th>
                        <th> {{ucwords(__('cp.price'))}} {{ucwords(__('cp.total'))}}</th>
                        <th> {{ucwords(__('cp.status'))}}</th>
                        <th> {{ucwords(__('cp.created'))}}</th>
                        <th> {{ucwords(__('cp.action'))}}</th>

                    </tr>
                    </thead>
                    <tbody>
                    @forelse($items as $item)
                        <tr class="odd gradeX" id="tr-{{$item->id}}">
                            <td>
                                 {{$loop->iteration}}
                            </td>
                            <td> {{@$item->city->name}}</td>
                            <td> {{$item->id}}</td>
                            <td> {{@$item->publicService->name}} - {{@$item->publicService->size}}</td>
                            <td> {{@$item->name}}</td>
                            <td> {{@$item->mobile}}</td>
                            <td>   @if(isset($item->latitude) && ($item->longitude))
                            <a href="#" onclick="openWindow('{{url('http://maps.google.com/maps?z=12&t=m&q=loc:'.$item->latitude .'+'.$item->longitude)}}')" class="btn default"><i class="fa fa-map-marker"></i></a>
                        @endif</td>
                            <td>{{$item->price}}</td>

                            <td>
                                <?php $status = '';
                                      $cls = ''; 
                                if($item->status == -1) {
                                    $status = 'new';
                                    $cls    = 'label-danger';
                                }
                                elseif($item->status == 0) {
                                    $status = 'preparing';
                                    $cls    = 'label-info';
                                }
                                elseif ($item->status == 1) {
                                    $status = 'onDelivery';
                                    $cls    = 'label-warning';
                                }
                                elseif ($item->status == 2) { 
                                    $status = 'complete';
                                    $cls    = 'label-success';
                                }
                                elseif ($item->status == 3) { 
                                    $status = 'cancel';
                                    $cls    = 'label-default';
                                }
                                else { 
                                    $status = 'refund';
                                    $cls    = 'label-default';
                                }
                                ?>
                                <span class="label label-sm {{$cls}}" id="label-{{$item->id}}">
                                {{__('cp.'.$status)}}
                            </span>

                            </td>

                            <td class="center">{{$item->created_at}}</td>
                            <td>

                            <div class="btn-group btn-action">


                                
                                <a href="{{url(getLocal().'/admin/publicServicesRequest/'.$item->id.'/edit')}}"
                                   class="btn btn-xs blue tooltips" data-container="body" data-placement="top"
                                   data-original-title="{{__('cp.edit')}}"><i class="fa fa-edit"></i>
                                </a>
                                
                                
                                <!--<a href="#" onclick="openWindow('{{url(getLocal().'/admin/publicServicesRequest/printOrder/'.$item->id)}}')" class="btn btn-xs red tooltips" data-container="body" data-placement="top"-->
                                <!--   data-original-title="{{__('cp.print')}}"><i class="fa fa-print"></i>-->
                                <!--</a>-->
                                   
                                   


                                <div id="myModal{{$item->id}}" class="modal fade" tabindex="-1" role="dialog"

                                     aria-labelledby="myModalLabel1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h4 class="modal-title">{{__('cp.delete')}}</h4>
                                            </div>
                                            <div class="modal-body">
                                                <p>{{__('cp.confirm')}} </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn default" data-dismiss="modal" aria-hidden="true">{{__('cp.cancel')}}</button>
                                                <a href="#" onclick="delete_adv('{{$item->id}}','{{$item->id}}',event)">
                                                    <button class="btn btn-danger">{{__('cp.delete')}}</button>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </td>
                        </tr>
                    @empty
                        {{__('cp.no')}}
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endsection

        @section('js')
        @endsection
        @section('script')
            <script>

                
                $(document).ready(function () {
                 setTimeout(function() {
                     window.location.reload();
                  }, 60000);
                });

                function openWindow($url)
        {
            window.open($url, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=800,height=700");
        }
            </script>
@endsection
