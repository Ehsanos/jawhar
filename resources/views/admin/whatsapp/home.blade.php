@extends('layout.adminLayout')
@section('title') {{ucwords(__('ارقام الواتس '))}}
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
                            <a href="{{url(getLocal().'/admin/Whatsapp/create')}}" style="margin-right: 5px"
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
                    <th> {{ucwords(__('cp.phone'))}}</th>
                    <th> {{ucwords(__('cp.username'))}}</th>
                    <th> {{ucwords(__('نوع الاجر'))}}</th>
                    <th> {{ucwords(__('القيمة'))}}</th>
                    <th> {{ucwords(__('cp.action'))}}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($whatsapp as $item)
                    <tr class="odd gradeX" id="tr-{{$item->id}}">
                        <td>
                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                <input type="checkbox" class="checkboxes chkBox" value="{{$item->id}}" name="chkBox"/>
                                <span></span>
                            </label>
                        </td>
                        <td> {{@$item->phone}}</td>
                        <td> {{@$item->user->name}}</td>
                        <td>  @if($item->status == 1)
                             نسبة
                            @else
                                بدون
                            @endif</td>
                        <td> @if($item->status == 1)
                                {{@$item->percent}}%
                            @else

                            @endif</td>
                        <td>
                            <div class="btn-group btn-action">
                                <a href="{{url(getLocal().'/admin/Whatsapp/'.$item->id.'/edit')}}"
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
@endsection
