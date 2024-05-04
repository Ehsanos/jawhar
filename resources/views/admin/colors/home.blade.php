@extends('layout.adminLayout')
@section('title') {{__('cp.colors')}}
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
                            <a href="{{url(app()->getLocale().'/admin/colors/create')}}" style="margin-right: 5px" class="btn sbold btn-primary">
                                {{__('cp.add')}} <i class="fa fa-plus"></i>
                            </a>
                            <button class="btn sbold btn-info btn--filter">{{__('cp.filter')}}
                                <i class="fa fa-search"></i>
                            </button>

                            <button class="btn sbold btn-success event"  data-action="active" href="#activation" role="button"  data-toggle="modal">{{__('cp.active')}}
                                <i class="fa fa-check"></i>
                            </button>
                            <button class="btn sbold btn-warning event"href="#cancel_activation" role="button"  data-toggle="modal" data-action="not_active">{{__('cp.not_active')}}
                                <i class="fa fa-minus"></i>
                            </button>

                            <button class="btn sbold btn-danger event" href="#deleteAll" role="button"  data-toggle="modal">{{__('cp.delete')}}
                                <i class="fa fa-times"></i>
                            </button>
                            

                        </div>
                    </div>

                </div>
                
            </div>
            <table class="table table-striped table-bordered table-hover table-checkable order-column" id="toolsTable">
                <thead>
                <tr>
                    <th>

                    </th>
                    <th> {{ucwords(__('cp.id'))}}</th>
                    <th> {{ucwords(__('cp.name'))}}</th>
                    <th> {{ucwords(__('cp.color'))}}</th>
                    <th> {{ucwords(__('cp.status'))}}</th>
                    <th> {{ucwords(__('cp.created'))}}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($items as $one)
                    <tr class="odd gradeX" id="tr-{{$one->id}}">
                        <td>
                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                <input type="checkbox" class="checkboxes chkBox" value="{{$one->id}}" name="chkBox"/>
                                <span></span>
                            </label>
                        </td>
                        <td>{{$one->id}}</td>

                            <td>{{$one->name}}</td>
                        <td  style="background-color:{{$one->color_hexa}}"></td>

                        <td> <span class="label label-sm {{($one->status == "active")
                                ? "label-info" : "label-danger"}}" id="label-{{$one->id}}">

                                {{__('cp.'.$one->status)}}
                            </span>
                        </td>

                        <td class="center">{{$one->created_at}}</td>
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
