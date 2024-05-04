@extends('layout.adminLayout')
@section('title') {{__('cp.requestMobileBalance')}}
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

                        </div>
                    </div>

                </div>
            </div>
            <table class="table table-striped table-bordered table-hover table-checkable order-column" id="toolsTable">
                <thead>
                <tr>
                    <th>

                    </th>
                    <th> {{ucwords(__('cp.user'))}}</th>
                    <th> {{ucwords(__('cp.mobile'))}}</th>
                    <th> {{ucwords(__('cp.price'))}}</th>
                    <th> {{ucwords(__('cp.network'))}}</th>
                    <th> {{ucwords(__('اسم الباقة'))}}</th>
                    <th> {{ucwords(__('cp.status'))}}</th>
                    <th> {{ucwords(__('cp.created'))}}</th>
                    <th> {{ucwords(__('cp.action'))}}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($request as $one)
                    <tr class="odd gradeX" id="tr-{{$one->id}}">
                        <td>
                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                <input type="checkbox" class="checkboxes chkBox" value="{{$one->id}}" name="chkBox"/>
                                <span></span>
                            </label>
                        </td>


                        <td>{{@$one->userName->name}}</td>
                        <td>{{@$one->mobile}}</td>
                        <td>{{@$one->balance}}</td>
                        <td>{{@$one->mobileCompany->name}}</td>
                        <td>{{@$one->requestmobilebalance->name}}</td>
                        <td>    @if  ($one->action == "0")    <span style="color :#f00" > {{__('cp.new')}}      </span>   @endif
                                @if  ($one->action == "1")    <span style="color :#ff0" > {{__('cp.inprogress')}}      </span>   @endif
                                @if  ($one->action == "2")    <span style="color :#000" > {{__('cp.done')}}      </span>   @endif
                                @if  ($one->action == "3")    <span style="color :#000" > {{__('cp.reject')}}      </span>   @endif

                         
                        </td>

                        <td class="center">{{$one->created_at}}</td>

                        <td>
                            <div class="btn-group btn-action">
                                <a href="{{url(getLocal().'/admin/requestMobileBalance/'.$one->id.'/edit')}}"
                                   class="btn btn-xs blue tooltips" data-container="body" data-placement="top"
                                   data-original-title="{{__('cp.edit')}}"><i class="fa fa-edit"></i></a>

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
