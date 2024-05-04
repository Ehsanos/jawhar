@extends('layout.adminLayout')
@section('title') {{ucwords(__('cp.wallet'))}}
@endsection
@section('css')
@endsection
@section('content')

    <div class="row">

        <div class="col-sm-12" style="text-align: center;padding: 10px">
            <a href="
        @if(isset(request()->currency) && request()->currency == "dollar")
            {{url(app()->getLocale().'/admin/users/'.$id_lolo.'/wallet')}}
            @else
            {{url(app()->getLocale().'/admin/users/'.$id_lolo.'/wallet?currency=dollar')}}
            @endif
                    " type="submit"
               class="btn sbold btn-default ">
                @if(isset(request()->currency) && request()->currency == "dollar")
                    العملة تركي
                @else
                    العملة دولار
                @endif
                <i class="fa fa-refresh"></i>
            </a>
        </div>
    </div>

    <div class="portlet light bordered">
        <div class="portlet-body">
            <div class="table-toolbar">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="btn-group">
<div class="w3-container">
  <h2> <i class="fa fa-user" style="font-size:60px;color:orange;"></i> {{@$user->name}}</h2>
 <p> <i class="fa fa-phone" style="font-size:20px;color:orange;"></i> {{@$user->mobile}} <i class="fa fa-envelope" style="font-size:20px;color:orange;"></i> {{@$user->email}}  </p>
</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="btn-group">
<div class="w3-container">
  <h2> <i class="fa fa-money" style="font-size:60px;color:red;"></i>{{@$balance}} </h2>
</div>
                        </div>
                    </div>

                </div>
            </div>
            <table class="table table-striped table-bordered table-hover table-checkable order-column" id="toolsTable">
                <thead>
               <tr>
                    <th > #</th>
                    <th style="width:20%" > {{ucwords(__('cp.date'))}}</th>
                    <th> {{ucwords(__('cp.total_price'))}}</th>
                    <th> {{ucwords(__('cp.type'))}}</th>
                    <th> {{ucwords(__('cp.title'))}}</th>
                    <th> {{ucwords(__('cp.details'))}}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($items as $item)
                        <td > {{@$item->id}}</td>
                        <td style="width:20%"> {{@$item->created_at}}</td>
                        <td> {{@$item->total_price}}</td>
                      
                        @if ($item->type == 0) 
                      <td style="background-color:#FF0000">  ايداع في المحفظة </td>
                        @else
                       <td style="background-color:#00FF00"> سحب من المحفظة </td>
                        @endif
                         </td>
                        <td> {{@$item->title}}</td>
                        <td> {{@$item->details}}</td>
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
