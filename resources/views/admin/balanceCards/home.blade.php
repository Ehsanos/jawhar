@extends('layout.adminLayout')
@section('title') {{__('cp.BalanceCard')}}
@endsection
@section('css')
@endsection

@section('content')

    <div class="portlet light bordered">
        <div class="portlet-body">
            <div class="table-toolbar">
                <div class="row">
                    <div class="col-sm-9">
                        <div class="btn-group">
                            <a href="{{url(getLocal().'/admin/balanceCards/create')}}" style="margin-right: 5px"
                               class="btn sbold green">{{__('cp.add')}}
                                <i class="fa fa-plus"></i>
                            </a>
                            
                            
                            
                                                    <button class="btn sbold blue btn--filter">{{__('cp.filter')}}
                                <i class="fa fa-search"></i>
                            </button>    
                            
                            <button class="btn sbold green event"  href="#activation" role="button"  data-toggle="modal">{{__('cp.active')}}
                                <i class="fa fa-check"></i>
                            </button>
                            <button class="btn sbold default event"  href="#cancel_activation" role="button"  data-toggle="modal">{{__('cp.not_active')}}
                                <i class="fa fa-minus"></i>
                            </button>

{{--            <button class="btn sbold red event" href="#deleteAll" role="button"  data-toggle="modal">{{__('cp.delete')}}--}}
{{--                                <i class="fa fa-times"></i>--}}
{{--                            </button>--}}
                        </div>
                    </div>

                </div>
                <div class="box-filter-collapse">
                    <form class="form-horizontal" method="get" action="{{url(getLocal().'/admin/balanceCards')}}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label class="col-md-3 control-label">{{__('cp.price')}}</label>
                                    <div class="col-md-9">
                                        <input type="number" min="0" class="form-control" name="price"
                                              >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 control-label">{{__('cp.serial_number')}}</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="serial_number" placeholder="اكتب اي جزء"
                                              >
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn sbold blue">{{__('cp.search')}}
                                            <i class="fa fa-search"></i>
                                        </button>
                                        <a href="{{url('admin/balanceCards')}}" type="submit"
                                           class="btn sbold btn-default ">{{__('cp.reset')}}
                                            <i class="fa fa-refresh"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
                
            </div>
            <table class="table table-striped table-bordered table-hover table-checkable order-column" id="toolsTable1">
                <thead>
                <tr>
                    <th>
                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                           <input type="checkbox"  name="checkAll"/>
                            <span></span>
                        </label>
                    </th>
                    <th> {{ucwords(__('cp.serial_number'))}}</th>
                    <th> {{ucwords(__('cp.price'))}}</th>
                    <th> {{"العملة"}}</th>
                    <th> {{ucwords(__('cp.password'))}}</th>
                    <th> {{ucwords(__('cp.status'))}}</th>
                    <th> {{ucwords(__('cp.action'))}}</th>
                </tr>
                </thead>
                <tbody>
                @forelse(@$balanceCards as $cards)
                    <tr class="odd gradeX" id="tr-{{$cards->id}}">
                        <td>
                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                <input type="checkbox" class="checkboxes chkBox" value="{{$cards->id}}" name="chkBox"/>
                                <span></span>
                            </label>
                        </td>
                        <td>{{$cards->serial_number}}</td>
                        <td>{{$cards->price}}</td>
                        <td>{{$cards->currency}}</td>
                        <td>{{$cards->password}}</td>
                        <td> <span class="label label-sm {{($cards->status == "active")
                                ? "label-info" : "label-danger"}}" id="label-{{$cards->id}}">
                                {{__('cp.'.$cards->status)}}

                            </span>
                        </td>


                        <td>
                            <div class="btn-group btn-action">
{{--                                <a href="{{url(getLocal().'/admin/balanceCards/'.$cards->id.'/edit')}}"--}}
{{--                                   class="btn btn-xs blue tooltips" data-container="body" data-placement="top"--}}
{{--                                   data-original-title="{{__('cp.edit')}}"><i class="fa fa-edit"></i></a>--}}
{{--                                 --}}
                                <a href="#myModal{{$cards->id}}" role="button"  data-toggle="modal" class="btn btn-xs red tooltips" data-placement="top"
                                   data-original-title="{{__('cp.delete')}}">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </a>
                                <div id="myModal{{$cards->id}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
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
                                                    <a href="#" onclick="delete_adv('{{$cards->id}}','{{$cards->id}}',event)"><button class="btn btn-danger">{{__('cp.delete')}}</button></a>
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
                        {{$balanceCards->appends($_GET)->links("pagination::bootstrap-4") }}
            
        </div>
    </div>
@endsection

@section('js')
@endsection
@section('script')
    <script>
        function delete_adv(id, iss_id, e) {
            //alert(id);
            e.preventDefault();

            var url = '{{url(getLocal()."/admin/balanceCards")}}/' + id;
            var csrf_token = '{{csrf_token()}}';
            $.ajax({
                type: 'delete',
                headers: {'X-CSRF-TOKEN': csrf_token},
                url: url,
                data: {_method: 'delete'},
                success: function (response) {
                    console.log(response);
                    if (response === 'success') {
                        $('#tr-' + id).hide(500);
                        $('#myModal' + id).modal('toggle');

                    } else {
                         swal('Error', {icon: "error"});
                    }
                },
                error: function (e) {

                }
            });

        }


    </script>
@endsection
