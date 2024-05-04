@extends('layout.adminLayout')
@section('title') {{ucwords(__('cp.files'))}}
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

                            <a href="{{url(app()->getLocale().'/admin/fils/create')}}" style="margin-right: 5px"
                               class="btn sbold green">{{__('cp.add')}}
                                <i class="fa fa-plus"></i>
                            </a>
                            
                            <a href="{{url('uploads/excel/tamplate.xlsx')}}" style="margin-right: 5px"
                               class="btn sbold green">{{__('cp.tamplate')}}
                                
                            </a>
                            <a href="{{url('/import')}}" style="margin-right: 5px"
                               class="btn sbold red">بدء العملية
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
                    <!--<th> {{ucwords(__('cp.admin_id'))}}</th>-->
                    <!--<th> {{ucwords(__('cp.type'))}}</th>-->
                    <th> {{ucwords(__('cp.status'))}}</th>
                    <th> {{ucwords(__('cp.created'))}}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($files as $item)
                    <tr class="odd gradeX" id="tr-{{$item->id}}">
                        <td>
                           {{$loop->iteration}}
                        </td>
                       
                        <td> <a href="{{url('uploads/excel/'.$item->file_name)}}"</a>{{@$item->file_name}}</td>
                        <!--<td> {{@$item->admin_id}}</td>-->
                        <!--<td> {{@$item->type}}</td>-->
                         
                        @if($item->status==1)
                        <td><span class="label label-sm label-danger"> {{__('cp.new')}}</span></td>
                        @elseif($item->status==2)
                        <td><span class="label label-sm label-warning"> {{__('cp.processing')}}</span></td>
                        @else
                        <td><span class="label label-sm label-info"> {{__('cp.complete')}}</span></td>
                        @endif

                        <td class="center">{{$item->created_at}}</td>

                    </tr>

                @empty
                  
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
            console.log(id);
            console.log(iss_id);
            var url = '{{url(getLocal()."/admin/users")}}/' + id;
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
                        // swal('Error', {icon: "error"});
                    }
                },
                error: function (e) {
                    // swal('exception', {icon: "error"});
                }
            });

        }


    </script>
@endsection
