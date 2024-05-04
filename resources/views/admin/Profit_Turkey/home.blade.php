@extends('layout.adminLayout')
@section('title') {{__('ارباح التركي يدوي')}}
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
                            <a href="{{url(getLocal().'/admin/profit_tr/create')}}" style="margin-right: 5px"
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
                    <th> {{ucwords(__('cp.username'))}} </th>
                    <th> {{ucwords(__('cp.city'))}} </th>
                    <th> {{ucwords(__('cp.service'))}} </th>
                    <th style="width: 200px"> {{ucwords(__('cp.details'))}} </th>
                    <th> {{ucwords(__('الربح'))}} </th>
                    <th> {{ucwords(__('cp.purchasing_price'))}} </th>
                    <th> {{ucwords(__('العامل'))}} </th>
                    <th> {{ucwords(__('ربح العامل'))}} </th>
                    <th> {{ucwords(__('cp.created'))}} </th>
                    <th> {{ucwords(__('cp.action'))}} </th>
                </tr>
                </thead>
                <tbody>
                @forelse(@$profit as $item)
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
                        <td> {{getServicesName($item->service_name,request()->type)}}</td>
                        <td> {{$item->details}}</td>
                        <td> {{number_format($item->profit, 2, '.', '')}}</td>
                        <td> {{number_format($item->purchasing_price, 2, '.', '')}}</td>
                        <td> {{$item->worker_name()}}</td>
                        <td> {{number_format($item->worker_profit, 2, '.', '') == 0 ? "" : number_format($item->worker_profit, 2, '.', '')}}</td>
                        <td style="direction: ltr"> {{$item->created_at}}</td>
                        <td>
                            <div class="btn-group btn-action">
                                <a href="{{url(getLocal().'/admin/profit_tr/'.$item->id.'/edit')}}"
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
