@extends('layout.adminLayout')
@section('title') {{ucwords(__('cp.manage_users'))}}
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
                            <a href="{{url(app()->getLocale().'/admin/users/create')}}" style="margin-right: 5px"
                               class="btn sbold green">{{__('cp.add')}}
                                <i class="fa fa-plus"></i>
                            </a>
                            <button class="btn sbold blue btn--filter">{{__('cp.filter')}}
                                <i class="fa fa-search"></i>
                            </button>
                            <a href="{{url(app()->getLocale().'/userexport')}}" style="margin-right: 5px" class="btn sbold btn-default">
                                {{__('cp.export')}} <i class="fa fa-file-excel-o"></i>
                            </a>
                            <button class="btn sbold green event"  data-action="active" href="#activation" role="button"  data-toggle="modal">{{__('cp.active')}}
                                <i class="fa fa-check"></i>
                            </button>
                            <button class="btn sbold default event"href="#cancel_activation" role="button"  data-toggle="modal" data-action="not_active">{{__('cp.not_active')}}
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>

                </div>
                <div class="box-filter-collapse">
                    <form class="form-horizontal" method="get" action="{{url(getLocal().'/admin/users')}}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label class="col-md-3 control-label">{{__('cp.name')}}</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="name"
                                              >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 control-label">{{__('cp.email')}}</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="email"
                                              >
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label class="col-md-3 control-label">{{__('cp.status')}}</label>
                                    <div class="col-md-9">
                                        <select id="multiple2" class="form-control"
                                                name="statusUser">
                                            <option value="">{{__('cp.all')}}</option>
                                            <option value="active">
                                                {{__('cp.active')}}
                                            </option>
                                            <option value="not_active">
                                                {{__('cp.not_active')}}
                                            </option>

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 control-label">{{__('cp.sortBy')}}</label>
                                    <div class="col-md-9">
                                        <select id="multiple2" class="form-control"
                                                name="sortBy">
                                            <option value="">{{__('cp.select')}}</option>
                                            <option value="created_at">
                                                {{__('cp.created')}}
                                            </option>
                                            <option value="name">
                                                {{__('cp.name')}}
                                            </option>

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 control-label">{{__('cp.sortBy')}}</label>
                                    <div class="col-md-9">
                                        <select id="multiple2" class="form-control"
                                                name="arrangBy">
                                            <option value="">{{__('cp.select')}}</option>
                                            <option value="desc">
                                               تصاعدي
                                            </option>
                                            <option value="asc">
                                                تنازلي
                                            </option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn sbold blue">{{__('cp.search')}}
                                            <i class="fa fa-search"></i>
                                        </button>
                                        <a href="{{url('admin/users')}}" type="submit"
                                           class="btn sbold btn-default ">{{__('cp.reset')}}
                                            <i class="fa fa-refresh"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label class="col-md-3 control-label">{{__('cp.mobile')}}</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="mobile"
                                               placeholder="{{__('cp.mobile')}}">
                                    </div>
                                </div>
                            </div>


                        </div>
                    </form>
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover table-checkable order-column" id="toolsTable1">
                <thead>
                <tr>
                    <th>

                    </th>
                    <th> الكود</th>
                    <th> {{ucwords(__('cp.profile_image'))}}</th>
                    <th> {{ucwords(__('cp.name'))}}</th>
                    <th> {{ucwords(__('cp.email'))}}</th>
                    <th> {{ucwords(__('cp.mobile'))}}</th>
                    <th> {{ucwords(__('cp.total_price'))}}</th>
                    <th> {{ucwords(__('cp.status'))}}</th>
                    <th> {{ucwords(__('cp.created'))}}</th>
                    <th> {{ucwords(__('cp.action'))}}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($items as $item)
                    <tr class="odd gradeX" id="tr-{{$item->id}}">
                        <td>
                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                <input type="checkbox" class="checkboxes chkBox" value="{{$item->id}}" name="chkBox"/>
                                <span></span>
                            </label>
                        </td>
                        <td> {{@$item->user_code}}</td>
                        <td> <img src="{{@$item->image_profile}}"style="width: 50px;"> </td>
                        <td> {{@$item->name}}</td>
                        <td><a href="mailto:{{$item->email}}">{{@$item->email}}</a></td>
                        <td> {{@$item->mobile}}</td>
                        <td ><a href="{{url(getLocal().'/admin/users/'. $item->id .'/wallet')}}"> {{$item->wallet->where('type',0)->sum('total_price') - $item->wallet->where('type',1)->sum('total_price')}}</td>



                        <td> <span class="label label-sm {{ ($item->status == "active")
                                ? "label-info" : "label-danger"}} " id="label-{{$item->id}}">

                                {{__('cp.'.$item->status)}}
                            </span></td>
                        <td class="center">{{$item->created_at}}</td>
                        <td>
                            <div class="btn-group btn-action">
                                <a href="{{url(app()->getLocale().'/admin/new_message/'. $item->id . '/response')}}"
                                   class="btn btn-xs green tooltips" data-container="body" data-placement="top"
                                   data-original-title="{{__('cp.chat')}}"><i class="fa fa-comment"></i></a>
                                <a href="{{url(getLocal().'/admin/users/'.$item->id.'/edit')}}"
                                   class="btn btn-xs blue tooltips" data-container="body" data-placement="top"
                                   data-original-title="{{__('cp.edit')}}"><i class="fa fa-edit"></i></a>
                                <a href="{{url(getLocal().'/admin/users/'.$item->id.'/wallet')}}"
                                   class="btn btn-xs red tooltips" data-container="body" data-placement="top"
                                   data-original-title="{{__('cp.wallet')}}"><i class="fa fa-money"></i></a>

                                <a href="{{url(getLocal().'/admin/users/'.$item->id.'/edit_password')}}"
                                   class="btn btn-xs blue tooltips" data-container="body" data-placement="top"
                                   data-original-title="{{__('cp.edit_password')}}"><i class="fa fa-expeditedssl"></i></a>

                            </div>
                        </td>
                    </tr>

                @empty
                    {{__('cp.no')}}
                @endforelse
                </tbody>
            </table>
                        {{$items->appends($_GET)->links("pagination::bootstrap-4") }}

        </div>
    </div>
@endsection

@section('js')
@endsection
@section('script')
    <script>


    </script>
@endsection
