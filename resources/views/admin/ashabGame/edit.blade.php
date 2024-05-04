@extends('layout.adminLayout')
@section('title') {{__('العاب اصحاب')}}
@endsection
@section('css')
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-settings font-dark"></i>
                        <span class="caption-subject font-dark sbold uppercase"
                              style="color: #e02222 !important;">{{__('cp.edit')}}</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <form method="post" action="{{url(app()->getLocale().'/admin/ashab/'.$item->id)}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form" id="form_city">
                        {{ csrf_field() }}
                        {{ method_field('PATCH')}}
                        <div class="form-body">
                            <fieldset>
                                <div class="form-group" id="game_id">
                                    <label class="col-sm-2 control-label" >{{__('المنتج')}}
                                        <span class="symbol"></span>
                                    </label>
                                    <div class="col-md-6">
                                        <input type="hidden" id="game_id" name="game_id" value="{{$item->game_id }}">
                                        <input type="hidden" id="game_num" name="game_num" value="{{$item->game_num }}">
                                        <input type="text" class="form-control" name="uouo" readonly value="{{$item->game_id." - ".getAshabGameInfo($item->game_id)->name }}" >
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('cp.name')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="game_name"
                                               placeholder=" {{__('cp.name')}}"
                                               value="{{@$item->game_name}}" >
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group" id="game_status">
                                    <label class="col-sm-2 control-label" >{{__('حالة الباقة')}}
                                        <span class="symbol"></span>
                                    </label>
                                    <div class="col-md-6">
                                        <select class="form-control select " id="game_status" name="game_status">
                                            <option {{ @$item->game_status == "0" ? "selected" : ""}}  value="0"> {{__('فعال')}}</option>
                                            <option {{ @$item->game_status == "1" ? "selected" : ""}} value="1"> {{__('غير فعال')}}</option>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <div class="form-group" id="game_tap">
                                    <label class="col-sm-2 control-label" >{{__(' لوحة العرض')}}
                                        <span class="symbol"></span>
                                    </label>
                                    <div class="col-md-6">
                                        <select id="game_tap" class="form-control select "
                                                name="game_tap" required>
                                            <?php $as = getAllAshabPages(); ?>
                                            @foreach($as as $one_as)
                                                <option {{ @$item->game_tap == ($loop->index+1) ? "selected" : ""}} value="{{$loop->index+1}}">{{$one_as}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('نص الحقل')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="game_text"
                                               placeholder=" {{__('نص الحقل')}}"
                                               value="{{@$item->game_text}}" >
                                    </div>
                                </div>
                            </fieldset>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">{{__('cp.edit')}}</button>
                                        <a href="{{url(getLocal().'/admin/ashab')}}" class="btn default">{{__('cp.cancel')}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
@endsection
@section('script')

@endsection

