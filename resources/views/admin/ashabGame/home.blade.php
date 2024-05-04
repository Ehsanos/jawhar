@extends('layout.adminLayout')
@section('title') {{__('العاب اصحاب')}}
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
                            <a href="{{url(getLocal().'/admin/ashab/create')}}" style="margin-right: 5px"
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
                    <th> </th>
                    <th> {{ucwords(__('cp.name'))}}</th>
                    <th> {{ucwords(__('الاسم بالعربي'))}}</th>
                    <th> {{ucwords(__('cp.status'))}}</th>
                    <th> {{ucwords(__('لوحة العرض'))}}</th>
                    <th> {{ucwords(__('النوع'))}}</th>
                    <th> {{ucwords(__('نص الحقل'))}}</th>
                    <th> {{ucwords(__('cp.action'))}}</th>
                </tr>
                </thead>
                <tbody>
                @forelse(@$games as $item)
                    <tr class="odd gradeX" id="tr-{{$item->id}}">
                        <td>
                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                <input type="checkbox" class="checkboxes chkBox" value="{{$item->id}}" name="chkBox"/>
                                <span></span>
                            </label>
                        </td>
                        <td>
                            @php
                                $tt = getAshabGameInfo(@$item->game_id);
                            @endphp
                            {{@$item->game_id}} - {{isset($tt->name) ? $tt->name : "" }}
                        </td>
                        <td>{{@$item->game_name}}</td>
                        <td class="center">
                            <?php
                            if($item->game_status == 1) {
                                $status = 'غير فعال ';
                                $cls    = 'label-danger';
                            }
                            else {
                                $status = '  فعال';
                                $cls    = 'label-success';
                            }
                            ?>
                            <span class="label label-sm {{$cls}}" id="label-{{@$item->game_id}}">
                                {{__(''.$status)}}
                            </span>
                        </td>
                        <td class="center">
                            <?php
                            if($item->game_tap == 1) {
                                $status = 'قائمة العاب';
                                $cls    = 'label-success';
                            }
                            elseif($item->game_tap == 2) {
                                $status = 'قائمة 2';
                                $cls    = 'label-danger';
                            }
                            elseif ($item->game_tap == 3) {
                                $status = 'قائمة 3';
                                $cls    = 'label-info';
                            }
                            ?>
                            <span class="label label-sm {{$cls}}" id="label-{{@$item->game_id}}">
                                {{__(''.$status)}}
                            </span>
                        </td>
                        <td class="center">
                         {{$item->game_num}}
                        </td>
                        <td class="center">
                            {{$item->game_text}}
                        </td>
                        <td>
                            <div class="btn-group btn-action">
                                <a href="{{url(getLocal().'/admin/ashab/'.$item->id.'/edit')}}"
                                   class="btn btn-xs blue tooltips" data-container="body" data-placement="top"
                                   data-original-title="{{__('cp.edit')}}"><i class="fa fa-edit"></i></a>
                                <a href="{{url(getLocal().'/admin/gamecard/'.@$item->game_id)}}"
                                   class="btn btn-xs red tooltips" data-container="body" data-placement="top"
                                   data-original-title="{{__('cp.cards')}}"><i class="fa {{ \App\Models\AshabGamesCards::where("ashab_game_id",$item->game_id)->get()->count() > 0 ? "fa-eye" : "fa-eye-slash" }}"></i></a>
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
