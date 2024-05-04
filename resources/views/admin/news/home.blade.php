@extends('layout.adminLayout')
@section('title') {{__('cp.news')}}
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
                            <a href="{{url(getLocal().'/admin/news/create')}}{{request()->segment("4") != null ? "?my_store_id=".request()->segment("4") :""}}"
                               style="margin-right: 5px"
                               class="btn sbold green">{{__('cp.add')}}
                                <i class="fa fa-plus"></i>
                            </a>

                            <button class="btn sbold green event" href="#activation" role="button"
                                    data-toggle="modal">{{__('cp.active')}}
                                <i class="fa fa-check"></i>
                            </button>
                            <button class="btn sbold default event" href="#cancel_activation" role="button"
                                    data-toggle="modal">{{__('cp.not_active')}}
                                <i class="fa fa-minus"></i>
                            </button>
                            <a href="{{url(getLocal().'/admin/newss/sorting')}}{{request()->segment("4") != null ? "/".request()->segment("4") :""}}" style="margin-right: 5px"
                               class="btn sbold red">{{__('cp.sort')}}
                                <i class="fa fa-sort"></i>
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
                    <th> {{ucwords(__('cp.title'))}}</th>

                    <th> {{ucwords(__('cp.status'))}}</th>
                    <th> {{ucwords(__('cp.created'))}}</th>
                    <th> {{ucwords(__('cp.action'))}}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($items as $one)
                    <tr class="odd gradeX" id="tr-{{$one->id}}">
                        <td>
                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                <input type="checkbox" class="checkboxes chkBox" value="{{$one->id}}" name="chkBox"/>
                                <span></span>
                            </label>
                        </td>


                        <td>{{$one->title}}</td>

                        <td> <span class="label label-sm {{($one->status == "active")
                                ? "label-info" : "label-danger"}}" id="label-{{$one->id}}">

                                {{__('cp.'.$one->status)}}
                            </span>
                        </td>

                        <td class="center">{{$one->created_at}}</td>

                        <td>
                            <div class="btn-group btn-action">
                                <a href="{{url(getLocal().'/admin/news/'.$one->id.'/edit')}}"
                                   class="btn btn-xs blue tooltips" data-container="body" data-placement="top"
                                   data-original-title="{{__('cp.edit')}}"><i class="fa fa-edit"></i></a>
                            </div>
                            <div class="btn-group btn-action">
                                <a href="#myModal{{$one->id}}" role="button" data-toggle="modal"
                                   class="btn btn-xs red tooltips">
                                    &nbsp;&nbsp;<i class="fa fa-times" aria-hidden="true"></i>
                                </a>

                                <div id="myModal{{$one->id}}" class="modal fade" tabindex="-1" role="dialog"
                                     aria-labelledby="myModalLabel1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-hidden="true"></button>
                                                <h4 class="modal-title">{{__('cp.delete')}}</h4>
                                            </div>
                                            <div class="modal-body">
                                                <p>{{__('cp.confirm')}} </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn default" data-dismiss="modal"
                                                        aria-hidden="true">{{__('cp.cancel')}}</button>
                                                <a href="#" onclick="delete_adv('{{$one->id}}','{{$one->id}}',event)">
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
        function delete_adv(id, iss_id, e) {
            //alert(id);
            e.preventDefault();

            var url = '{{url(getLocal()."/admin/news")}}/' + id;
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
