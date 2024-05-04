@extends('layout.adminLayout')
@section('title') {{__('باقات العاب اصحاب')}}
@endsection
@section('css')
@endsection
@section('content')
    <form method="post" id="jojo" action="{{url(app()->getLocale().'/admin/gamecard/store')}}"
          enctype="multipart/form-data" class="form-horizontal" role="form">
        {{ csrf_field() }}
        <?php
        if(session()->get("saved") != null && session()->get("saved") == "done")
        {
        session()->put("saved",null);
        ?>
        <ul style="border: 1px solid #01b070; background-color: white">
            <li style="color: #01b070; margin: 15px">تم التعديل بنجاح</li>
        </ul>
        <?php
        }
        ?>
        <input type="hidden" id="gameId" name="gameId" value="{{$gameId}}">
    <div class="portlet light bordered">
        <div class="portlet-body">
            <div class="table-toolbar">
                <div class="row">
                    <div class="col-sm-9">
                        <div class="btn-group">
                            <a href="#" onclick="document.getElementById('jojo').submit()" style="margin-right: 5px"
                               class="btn sbold green">{{__('حفظ')}}
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover table-checkable order-column" id="toolsTable3">
                <thead>
                <tr>
                    <th>#</th>
                    <th>{{ucwords(__('cp.name'))}}</th>
                    <th> {{ucwords(__('الاسم بالعربي'))}}</th>
                    <th> {{ucwords(__('حالة العرض'))}}</th>
                    <th>{{ucwords(__('cp.price'))}}</th>
                    <th>{{ucwords(__('نسبة الربح'))}}</th>
                    <th>{{ucwords(__('الحالة'))}}</th>
                </tr>
                </thead>
                <tbody>
                @forelse(@$lolo as $item)
                    <tr class="odd gradeX" id="tr-{{$item->denomination_id}}">
                        <td>{{@$item->denomination_id}}</td>
                        <td>{{@$item->product_name}}</td>

                        <td> @php
                                $vv = "";
                                foreach($jojo as $one_jojo)
                                {
                                      if($one_jojo->denomination_id == $item->denomination_id)
                                {
                                    $vv =  $one_jojo->card_name;
                                }
                                }
                            @endphp
                            <input type="text" style="width:200px" class="form-control" name="card_name_{{$item->denomination_id}}" id="card_name_{{$item->denomination_id}}"
                                   value="{{  $vv  }}"></td>
                        <td> @php
                                $vvv = false;
                                foreach($jojo as $one_jojo)
                                {
                                      if($one_jojo->denomination_id == $item->denomination_id)
                                {
                                    if($one_jojo->status_cart == 1){
                                        $vvv = true;
                                    }
                                }
                                }
                            @endphp
                            <input type="checkbox" class="checkboxes chkBox" name="status_cart_{{$item->denomination_id}}"  {{ $vvv ?  "checked" : "" }} id="status_cart_{{$item->denomination_id}}"
                                   ></td>

                        <td>{{@$item->product_price}}</td>
                        <td>
                            @php
                            $vv = "";
                            foreach($jojo as $one_jojo)
                            {
                                if($one_jojo->denomination_id == $item->denomination_id)
                                {
                                    $vv =  $one_jojo->price;
                                }
                            }
                            @endphp
                            <input type="number" style="width:100px" {{@$item->product_available == false ? "readonly" :"" }} step="0.01" class="form-control" name="price_{{$item->denomination_id}}" id="price_{{$item->denomination_id}}"
                                   value="{{  $vv  }}">
                        </td>
                        <td>{{@$item->product_available == false ? "غير فعال" :"فعال" }}</td>
                    </tr>
                @empty
                    {{__('cp.no')}}
                @endforelse
                </tbody>
            </table>
            <div class="table-toolbar">
                <div class="row">
                    <div class="col-sm-9">
                        <div class="btn-group">
                            <a href="#" onclick="document.getElementById('jojo').submit()" style="margin-right: 5px"
                               class="btn sbold green">{{__('حفظ')}}
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
@endsection
@section('js')
@endsection
@section('script')
    <script>
        $('#toolsTable2').DataTable({
            dom: 'Bfrtip',
            searching: true,
            bInfo: false, //Dont display info e.g. "Showing 1 to 4 of 4 entries"
            paging: false,//Dont want paging
            bPaginate: false,//Dont want paging
            buttons: [
                'copy', 'excel', 'pdf', 'print'
            ]
        });
    </script>
@endsection
