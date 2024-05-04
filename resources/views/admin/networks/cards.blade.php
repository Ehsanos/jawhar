@extends('layout.adminLayout')
@section('title') {{__('cp.NetworksCards')}}
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
                      <a href="{{url(getLocal().'/admin/networks/'.$id.'/addCards')}}" style="margin-right: 5px"
                               class="btn sbold green">{{__('cp.add')}}
                                <i class="fa fa-plus"></i>
                            </a>
                            <a href="{{url(getLocal().'/admin/fils/'.$id.'/add')}}" style="margin-right: 5px"
                               class="btn sbold red">{{__('cp.import')}}
                                <i class="fa fa-plus"></i>
                            </a>

                            <button class="btn sbold btn-success event"  data-action="active" href="#activation" role="button"  data-toggle="modal">{{__('cp.active')}}
                                <i class="fa fa-check"></i>
                            </button>
                            <button class="btn sbold btn-warning event"href="#cancel_activation" role="button"  data-toggle="modal" data-action="not_active">{{__('cp.not_active')}}
                                <i class="fa fa-minus"></i>
                            </button>

                            <button class="btn sbold btn-danger event" href="#deleteAll" role="button"  data-toggle="modal">{{__('cp.delete')}}
                                <i class="fa fa-times"></i>
                            </button>
                                   </div>
                        
                    </div>

                </div>
            </div>
            <table class="table table-striped table-bordered table-hover table-checkable order-column" id="toolsTable">
                <thead>
                <tr>
                    <th>

                    </th>
                    <th> PIN</th>
                    <th> {{ucwords(__('cp.password'))}}</th>
                    <th> {{ucwords(__('cp.wifi'))}}</th>
                    <th> {{ucwords(__('cp.network'))}}</th>
                    <th> {{ucwords(__('cp.status'))}}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($networksCards as $one)
                    <tr class="odd gradeX" id="tr-{{$one->id}}">
                        <td>
                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                <input type="checkbox" class="checkboxes chkBox" value="{{$one->id}}" name="chkBox"/>
                                <span></span>
                            </label>
                        </td>



                        <td>{{$one->pin}}</td>
                        <td>{{$one->password}}</td>
                        <td>{{$one->wifiName->name}}</td>
                        <td>{{$one->networkName->name}}</td>
                        <td> <span class="label label-sm {{(@$one->Status == "active")
                                ? "label-info" : "label-danger"}}" id="label-{{$one->id}}">
                                {{__('cp.'.$one->Status)}}
                            </span>
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
