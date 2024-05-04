@extends('layout.adminLayout')
@section('title') {{__('cp.Networks')}}
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
                            <a href="{{url(getLocal().'/admin/networks/create')}}{{request()->segment("4") != null ? "?my_store_id=".request()->segment("4") :""}}" style="margin-right: 5px"
                               class="btn sbold green">{{__('cp.add')}}
                                <i class="fa fa-plus"></i>
                            </a>

                            <button class="btn sbold green event"  href="#activation" role="button"  data-toggle="modal">{{__('cp.active')}}
                                <i class="fa fa-check"></i>
                            </button>
                            <button class="btn sbold default event"  href="#cancel_activation" role="button"  data-toggle="modal">{{__('cp.not_active')}}
                                <i class="fa fa-minus"></i>
                            </button>
                            <button class="btn sbold btn-danger event" href="#deleteAll" role="button"  data-toggle="modal">{{__('cp.delete')}}
                                <i class="fa fa-times"></i>
                            </button>
                            <a href="{{url('/files/startImport')}}" style="margin-right: 5px"
                               class="btn sbold purple">بدء العملية
                                <i class="fa fa-check"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
            <table class="table table-striped table-bordered table-hover table-checkable order-column" id="toolsTable">
                <thead>
                <tr>
                    <th>

                    </th>

                    <th> {{ucwords(__('cp.name'))}}</th>
                    <th> {{ucwords(__('cp.wifi_id'))}}</th>
                    <th> {{ucwords(__('cp.price'))}}</th>
                    <th> {{ucwords(__('cp.image'))}}</th>
                    <th> {{ucwords(__('cp.Status'))}}</th>
                    <th> {{ucwords(__('cp.count'))}}</th>
                    <th> {{ucwords(__('cp.action'))}}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($network as $one)
                    <tr class="odd gradeX" id="tr-{{$one->id}}">
                        <td>
                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                <input type="checkbox" class="checkboxes chkBox" value="{{$one->id}}" name="chkBox"/>
                                <span></span>
                            </label>
                        </td>



                        <td>{{@$one->name}}</td>
                        <td>{{@$one->wifiName->name}}</td>
                        <td>@if($one->is_dollar == 1)
                                {{$one->price}} $ <br>
                                ( {{$one->result($one->price)}} ) ₺
                            @else
                                {{$one->price}} ₺
                            @endif
                        </td>



                        <td><img src="{{$one->image}}" width="50px" height="50px"></td>


                        <td> <span class="label label-sm {{(@$one->Status == "active")
                                ? "label-info" : "label-danger"}}" id="label-{{$one->id}}">

                                {{__('cp.'.$one->Status)}}
                            </span>
                        </td>

                        <td class="center">{{($one->networks_cards_count)}}</td>

                        <td>
                            <div class="btn-group btn-action">
                                <a href="{{url(getLocal().'/admin/networks/'.$one->id.'/edit')}}"
                                   class="btn btn-xs blue tooltips" data-container="body" data-placement="top"
                                   data-original-title="{{__('cp.edit')}}"><i class="fa fa-edit"></i></a>
                                @if ($one->type == 1)
                                    <a href="{{url(getLocal().'/admin/cards/'.$one->id)}}"
                                       class="btn btn-xs red tooltips" data-container="body" data-placement="top"
                                       data-original-title="{{__('cp.cards')}}"><i class="fa fa-eye"></i></a>
                                @endif
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
        function delete_adv(id, iss_id, e) {
            //alert(id);
            e.preventDefault();

            var url = '{{url(getLocal()."/admin/azkar")}}/' + id;
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
