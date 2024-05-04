@extends('layout.adminLayout')
@section('title') {{ucwords(__('اقسام الشبكات'))}}
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
                            <a href="{{url(getLocal().'/admin/NetworkSections/create')}}{{request()->segment("4") != null ? "?my_store_id=".request()->segment("4") :""}}" style="margin-right: 5px"
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
                    <th> {{ucwords(__('cp.networks'))}}</th>
                    <th> {{ucwords(__('cp.username'))}}</th>
                    <th> {{ucwords(__('نسبة العامة'))}}</th>
                    <th> {{ucwords(__('نسبة تجديد الاشترك'))}}</th>
                    <th> {{ucwords(__('الزاوية الاولى'))}}</th>
                    <th> {{ucwords(__('الزاوية الثانية'))}}</th>
                    <th> {{ucwords(__('الزاوية الثالث'))}}</th>
                    <th> {{ucwords(__('الزاوية الارابع'))}}</th>
                    <th> {{ucwords(__('cp.action'))}}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($networksections as $item)
                    <tr class="odd gradeX" id="tr-{{$item->id}}">
                        <td>
                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                <input type="checkbox" class="checkboxes chkBox" value="{{$item->id}}" name="chkBox"/>
                                <span></span>
                            </label>
                        </td>
                        <td> {{@$item->store->store_name}}</td>
                        <td> {{@$item->user->name}}</td>
                        <td> {{@$item->app_percent}}</td>
                        <td> {{@$item->reNewNetwork_percent}}</td>
                        <td> {{@$item->top_point}}</td>
                        <td> {{@$item->bottom_point}}</td>
                        <td> {{@$item->right_point}}</td>
                        <td> {{@$item->left_point	}}</td>
                        <td>
                            <div class="btn-group btn-action">
                                <a href="{{url(getLocal().'/admin/NetworkSections/'.$item->id.'/edit')}}{{ "?my_store_id=".$item->store->id }}"
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
