@extends('layout.adminLayout')
@section('title') {{__('أصحاب نجمة ألعاب')}}
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
                            <a href="{{url(getLocal().'/admin/nagma_game/create')}}" style="margin-right: 5px"
                               class="btn sbold green">{{__('cp.add')}}
                                <i class="fa fa-plus"></i>
                            </a>
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

                    <th> {{ucwords(__('cp.name'))}}</th>
                    <th> {{ucwords(__('cp.status'))}}</th>
                    <th> {{ucwords(__('cp.discount%'))}}</th>
                    <th> {{ucwords(__('cp.details'))}}</th>
                    <th> {{ucwords(__('cp.action'))}}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($nagma as $one)
                    <tr class="odd gradeX" id="tr-{{$one->id}}">
                        <td>
                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                <input type="checkbox" class="checkboxes chkBox" value="{{$one->id}}" name="chkBox"/>
                                <span></span>
                            </label>
                        </td>
                        <td>{{$one->id}} - {{@$one->user->name}}</td>
                        <td>{{@$one->status == 0 ? __("cp.active") : __("cp.not_active")}}</td>
                        <td>{{@$one->average}} %</td>
                        <td>
                            @foreach(explode(',',$one->nagma_game_ids) as $one_id)
                           <a class="btn btn-danger">
                          
                          @php
 $GameServies=   \App\Models\GameServies::find($one_id)
@endphp
    {{@$GameServies->api_name}} / {{@$GameServies->api_product_name}}     </a>
                            @endforeach
                        </td>
                        <td>
                            <div class="btn-group btn-action">
                                <a href="{{url(getLocal().'/admin/nagma_game/'.$one->id.'/edit')}}"
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
    <script>

    </script>
@endsection
