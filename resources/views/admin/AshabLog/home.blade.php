@extends('layout.adminLayout')
@section('title') {{__('العاب اصحاب')}}
@endsection
@section('css')
@endsection

@section('content')
    <div class="portlet light bordered">
        <div class="portlet-body">
            <table class="table table-striped table-bordered table-hover table-checkable order-column" id="toolsTable">
                <thead>
                <tr>
                    <th> </th>
                    <th> {{ucwords(__('اسم المستحدم'))}}</th>
                    <th> {{ucwords(__('الصفحة'))}}</th>
                    <th> {{ucwords(__('رقم الطلب'))}}</th>
                    <th> {{ucwords(__('اسم المنتج'))}}</th>
                    <th> {{ucwords(__('الاختيار'))}}</th>
                    <th> {{ucwords(__('السعر'))}}</th>
                    <th> {{ucwords(__('الكمية'))}}</th>
                    <th> {{ucwords(__('id'))}}</th>
                    <th> {{ucwords(__('المدينة'))}}</th>
                    <th> {{ucwords(__('التاريخ'))}}</th>
                </tr>
                </thead>
                <tbody>
                @forelse(@$ashab_log as $item)
                    <tr class="odd gradeX" id="tr-{{$item->id}}">
                        <td>
                            {{$item->id}}
                        </td>
                        <td>
                            {{@$item->user->name}}
                        </td>
                        <td>
                            {{getAllAshabPages()[@$item->page_id-1]}}
                        </td>
                        <td>
                            {{@$item->order_id}}
                        </td>
                        <td>
                            {{@$item->game_id}}
                        </td>
                        <td>
                            {{@$item->denomination_id}}
                        </td>
                        <td>
                            {{@$item->price}}
                        </td>
                        <td>
                            {{@$item->qty}}
                        </td>
                        <td>
                            {{@$item->playerid}}
                        </td>
                        <td>
                            {{@$item->city->name}}
                        </td>
                        <td>
                            {{$item->created_at}}
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
@endsection
