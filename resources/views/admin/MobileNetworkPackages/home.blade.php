@extends('layout.adminLayout')
@section('title') {{__('باقات شبكات الموبيل ')}}
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
                            <a href="{{url(getLocal().'/admin/MobileNetworkPackages/create')}}" style="margin-right: 5px"
                               class="btn sbold green">{{__('cp.add')}}
                                <i class="fa fa-plus"></i>
                            </a>


                            <button class="btn sbold red event" href="#deleteAll" role="button"  data-toggle="modal">{{__('cp.delete')}}
                                <i class="fa fa-times"></i>
                            </button>


                            <button class="btn sbold green event"  href="#activation" role="button"  data-toggle="modal">{{__('cp.active')}}
                                <i class="fa fa-check"></i>
                            </button>
                            <button class="btn sbold default event"  href="#cancel_activation" role="button"  data-toggle="modal">{{__('cp.not_active')}}
                                <i class="fa fa-minus"></i>
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
                    <th> {{ucwords(__('cp.name'))}}</th>
                    <th> {{ucwords(__('اسم الشبكة'))}}</th>
                    <th> {{ucwords(__('cp.purchasing_price'))}}</th>
                    <th> {{ucwords(__('cp.price'))}}</th>
                    <th> {{ucwords(__('cp.action'))}}</th>
                </tr>
                </thead>
                <tbody>
                @forelse(@$mobilenetworkpackages as $item)
                    <tr class="odd gradeX" id="tr-{{$item->id}}">
                        <td>
                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                <input type="checkbox" class="checkboxes chkBox" value="{{$item->id}}" name="chkBox"/>
                                <span></span>
                            </label>
                        </td>
                        <td>{{@$item->name}}</td>
                        <td>{{@$item->MobileCompany->name}}</td>
                        <td>@if($item->is_dollar == 1)
                                {{$item->purchasing_price}} $ <br>
                                ( {{$item->result($item->purchasing_price)}} ) ₺
                            @else
                                {{$item->purchasing_price}} ₺
                            @endif
                        </td>

                        <td>@if($item->is_dollar == 1)
                                {{$item->price}} $ <br>
                                ( {{$item->result($item->price)}} ) ₺
                            @else
                                {{$item->price}} ₺
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-action">
                                <a href="{{url(getLocal().'/admin/MobileNetworkPackages/'.$item->id.'/edit')}}"
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
