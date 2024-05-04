@extends('layout.adminLayout')
@section('title') {{ucwords(__('المصاريف'))}}
@endsection
@section('content')
    <div class="row">

        <div class="col-sm-12" style="text-align: center;padding: 10px">
            <a href="
        @if(isset(request()->currency) && request()->currency == "dollar")
            {{url(app()->getLocale().'/admin/Expenses')}}
            @else
            {{url(app()->getLocale().'/admin/Expenses?currency=dollar')}}
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
    <div class="table-toolbar">
        <div class="row">
            <div class="col-sm-9">
                <div class="btn-group">
                    <a href="{{url(getLocal().'/admin/Expenses/create')."?currency=".(isset(request()->currency) && request()->currency == "dollar" ? "dollar" : "turkey" )}}" style="margin-right: 5px"
                       class="btn sbold green">{{__('cp.add')}}
                        <i class="fa fa-plus"></i>
                    </a>
                    <button class="btn sbold red event" href="#deleteAll" role="button"  data-toggle="modal">{{__('cp.delete')}}
                        <i class="fa fa-times"></i>
                    </button>

                </div>
            </div>

        </div>
    </div>
    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="toolsTable">
        <thead>
        <tr>
            <th>#</th>
            <th>العدد</th>
            <th> {{ucwords(__('المستفيد'))}} </th>
            <th> {{ucwords(__('cp.city'))}} </th>
            <th> {{ucwords(__('المصروف'))}} </th>
            <th style="width: 200px"> {{ucwords(__('cp.details'))}} </th>
            <th> {{ucwords(__('المبلغ'))}} </th>
            <th> {{ucwords(__('cp.addBy'))}} </th>
            <th> {{ucwords(__('cp.created'))}} </th>
            <th> {{ucwords(__('cp.action'))}}</th>
        </tr>
        </thead>
        <tbody>
        @forelse($profit as $item)
            <tr class="odd gradeX" id="tr-{{$item->id}}">
                <td>
                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                        <input type="checkbox" class="checkboxes chkBox" value="{{$item->id}}" name="chkBox"/>
                        <span></span>
                    </label>
                </td>
                <td>{{$loop->index+1}}</td>
                <td> {{$item->user->name}}</td>
                <td> {{$item->city->name}}</td>
                <td> {{getServicesName_Expenses($item->service_name)}}</td>
                <td> {{$item->details}}</td>
                <td> {{$item->profit}}</td>
                <td> {{$item->admin_worker->name}}</td>
                <td> {{$item->created_at}}</td>
                <td>
                    <div class="btn-group btn-action">
                        <a href="{{url(getLocal().'/admin/Expenses/'.$item->id.'/edit')."?currency=".(isset(request()->currency) && request()->currency == "dollar" ? "dollar" : "turkey" )}}"
                           class="btn btn-xs blue tooltips" data-container="body" data-placement="top"
                           data-original-title="{{__('cp.edit')}}"><i class="fa fa-edit"></i></a>

                    </div>
                </td>
            </tr>
        @empty
        @endforelse
        </tbody>
    </table>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            $('#datetimepicker1').datepicker({
                autoclose: true,
                {{getLocal() == "ar" ? " rtl: true" : ""}}
            });
            $('#datetimepicker2').datepicker({
                autoclose: true,
                {{getLocal() == "ar" ? " rtl: true" : ""}}
            });
        });

        function check_form() {
            if ($("#datetimepicker1").val() != "") {
                if ($("#datetimepicker2").val() == "") {
                    alert("الرجاء تحديد البداية و النهاية");
                    return false;
                }
            }
            return true;
        }
    </script>
@endsection
