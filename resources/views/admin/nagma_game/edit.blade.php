@extends('layout.adminLayout')
@section('title') {{__('أصحاب نجمة العاب')}}
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
                              style="color: #e02222 !important;">{{ucwords(__('cp.edit'))}} {{__('نجمة اصحاب العاب')}}</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <form method="post" action="{{url(app()->getLocale().'/admin/nagma_game/'.$nagma->id)}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form" id="form">
                        {{ csrf_field() }}
                        {{ method_field('PATCH')}}

                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="name">
                                    {{__('cp.name')}}
                                </label>
                                    <div class="col-md-6">
                                        <select class="form-control select2" id="user_id" name="user_id" >
                                            @foreach($users as $one)
                                                <option value="{{$one->id}}"   @if($one->id ==  $nagma->user_id)
                                                        selected @endIf >{{$one->id}} - {{$one->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="average">
                                    {{__('نسبة الخصم')}}
                                </label>
                                <div class="col-md-6">
                                    <input onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" type="number" min="0" max="100" class="form-control" name="average" value="{{ old('average', $nagma->average) }}" required>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"  for="status">{{__('cp.status')}}

                                </label>
                                <div class="col-md-6">
                                    <select id="status" class="form-control select "
                                            name="status">
                                        <option value="0" {{$nagma->status == 0 ? "selected" : ""}}>{{__('cp.active')}}</option>
                                        <option value="1" {{$nagma->status == 1 ? "selected" : ""}}>{{__('cp.not_active')}}</option>
                                    </select>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="nagma_ashab_cards_ids">
                                    {{__('اصحاب كرت ')}}
                                </label>
                                <div class="col-md-6">
                                    <select class="form-control select2" id="nagma_ashab_cards_ids" name="nagma_ashab_cards_ids[]" multiple="multiple" required>
                                        @foreach($nagma_ashab_cards_ids as$one)
                                            <option value="{{$one->id}}"
                                            @foreach(explode(',',$nagma->nagma_game_ids) as $one_id)
                                                @if($one_id ==  $one->id)
                                                        selected
                                                    @break
                                                @endif
                                            @endforeach
                                            >{{$one->game->name}} ----> {{$one->size}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </fieldset>

                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn green">{{__('cp.submit')}}</button>
                                    <a href="{{url(getLocal().'/admin/nagma_game')}}" class="btn default">{{__('cp.cancel')}}</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>







@endsection
@section('script')

@endsection