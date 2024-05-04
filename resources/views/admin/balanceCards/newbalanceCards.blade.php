@extends('layout.adminLayout')
@section('title') {{__('cp.UsedBalanceCard')}}
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
  
            <th style="width:40%" > {{ucwords(__('cp.date'))}}</th>
                    <th> {{ucwords(__('cp.price'))}}</th>
                    <th> المالك</th>
                    <th> أصحاب العملية</th>
                    <th> {{ucwords(__('cp.cards'))}}</th>
                    <th> {{ucwords(__('cp.title'))}}</th>
                    <th> {{ucwords(__('cp.details'))}}</th>
                </tr>
                </thead>
                <tbody>
                @forelse(@$balanceCards as $cards)
                    <tr class="odd gradeX" id="tr-{{$cards->id}}">
                                               <td style="width:20%"> {{@$cards->created_at}}</td>
                        <td> {{@$cards->total_price}}</td>
                        <td> {{@$cards->user->name}}</td>
                        <td> <?php echo getUsers($cards->created_at,$cards->id); ?> </td>
                         <td>{{isset(explode(':',@$cards->details)[1])? explode(':',@$cards->details)[1]:""}} </td>
                        <td> {{@$cards->title}}</td>
                        <td> {{@$cards->details}}</td>
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
