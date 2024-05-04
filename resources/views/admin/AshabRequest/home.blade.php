@extends('layout.adminLayout')
@section('title') {{__('العاب اصحاب')}}
@endsection
@section('css')
@endsection

@section('content')
    <div class="portlet light bordered">
        <div class="portlet-body">
            <table class="table table-striped table-bordered table-hover table-checkable order-column" id="toolsTable1">
                <thead>
                <tr>
                    <th> </th>
                    <th> {{ucwords(__('اسم المستحدم'))}}</th>
                    <th> {{ucwords(__('اسم المنتج'))}}</th>
                    <th> {{ucwords(__('رقم الطلب'))}}</th>
                    <th> {{ucwords(__('السعر'))}}</th>
{{--                    <th> {{ucwords("الحالة من البداية")}}</th>--}}
                    <th> {{ucwords("الحالة من عند اصحاب")}}</th>
                    <th> {{ucwords("تم الرد الى الزبون")}}</th>
                        <th> {{ucwords(__('cp.created_at'))}}</th>
                    <th> {{ucwords(__('cp.action'))}}</th>
                
                </tr>
                </thead>
                <tbody>
                @forelse(@$ashab_request as $item)
                    <tr class="odd gradeX" id="tr-{{$item->id}}">
                        <td>
                            {{$item->id}}
                        </td>
                        <td>
                            {{@$item->user->name}}
                        </td>
                        <td>
                            {{@$item->order_product}}
                        </td>
                        <td>
                            {{@$item->order_id}}
                        </td>
                        <td class="center">
                            {{$item->price}}
                        </td>

                        <td class="center">
                            <?php
                            if($item->status_from_ashab == "completed")
                            {
                                echo "مكتمل";
                            }else
                                if($item->status_from_ashab == "processing")
                                {
                                    echo "قيد المعالجة";
                                }
                                else
                                {
                                    if($item->status_from_ashab == "failed")
                                    {
                                        echo "ملغي";
                                    }
                                    else
                                    {
                                        echo $item->status_from_ashab;
                                    }
                                }
                            ?>
                        </td>
                        <td class="center">
                            @if($item->status == 0)
                            {{$item->replay}}
                            @else
                                تم الغاء الطلب
                            @endif
                        </td>
                        <td class="center">
                            {{$item->created_at}}

                        </td>
                        <td>
                            <div class="btn-group btn-action">
                                <a href="{{url(getLocal().'/admin/AshabRequest/'.$item->id.'/edit')}}"
                                   class="btn btn-xs blue tooltips" data-container="body" data-placement="top"
                                   data-original-title="{{__('cp.edit')}}"><i class="fa fa-eye"></i></a>

                                <a href="{{url(getLocal().'/admin/AshabRequest/create?ashab_id='.$item->id)}}"
                                   class="btn btn-xs blue tooltips" data-container="body" data-placement="top"
                                   data-original-title="{{__('ارسال نص')}}"><i class="fa fa-edit"></i></a>
                                </div>
                        </td>
                    </tr>
                @empty
                    {{__('cp.no')}}
                @endforelse
                </tbody>
            </table>
                                    {{$ashab_request->appends($_GET)->links("pagination::bootstrap-4") }}

        </div>
    </div>
@endsection
@section('js')
@endsection
@section('script')
@endsection
