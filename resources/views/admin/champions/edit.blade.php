@extends('layout.adminLayout')
@section('title') {{__('cp.champions')}}
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
                              style="color: #e02222 !important;">{{__('cp.edit')}} {{__('cp.champions')}}</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <form method="post" action="{{url(app()->getLocale().'/admin/champions/'.$item->id)}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form">
                        {{ csrf_field() }}
                        {{ method_field('PATCH')}}
                        <div class="form-body">
                               <fieldset>
                                @foreach($locales as $locale)
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="title_{{$locale->lang}}">
                                            {{__('cp.name_'.$locale->lang)}}
                                            <span class="symbol">*</span>
                                        </label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="name_{{$locale->lang}}"
                                                   placeholder=" {{__('cp.name')}} {{$locale->lang}}"
                                                   id="name_{{$locale->lang}}"
                                          value="{{ old('name_'.$locale->lang, $item->translate($locale->lang)->name) }}"
 required>
                                        </div>
                                    </div>

                                @endforeach
                            </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="start_date">
                                    {{__('cp.champion_date')}}
                                    <span class="symbol">*</span>
                                </label>
                                <div class="col-md-6">
                                    <input type="date" class="form-control" name="start_date" placeholder="{{__('cp.champion_date')}}"
                                           value="{{($item->start_date)}}" required>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="end_guess">
                                    {{__('cp.end_guess')}}
                                    <span class="symbol">*</span>
                                </label>
                                <div class="col-md-6">
                                    <input type="date" class="form-control" name="end_guess" placeholder="{{__('cp.end_guess')}}"
                                           value="{{($item->end_guess)}}" required>
                                </div>
                            </div>
                   
                        </fieldset>
                            <fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="end_date">
                                    {{__('cp.champion__end_date')}}
                                    <span class="symbol">*</span>
                                </label>
                                <div class="col-md-6">
                                    <input type="date" class="form-control" name="end_date" placeholder="{{__('cp.champion__end_date')}}"
                                           value="{{($item->end_date)}}" required>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="order">
                                    {{__('cp.winner_point')}}
                                </label>
                                <div class="col-md-6">
                                    <input   type="text" onkeyup=" if (/\D/g.test(this.value)) this.value = this.value.replace(/[^0-9.]/g,'')"
                                             class="form-control" name="winner_point" value="{{($item->winner_point)}}" id="winner_point"
                                             placeholder=" {{__('cp.winner_point')}}"  required>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="order">
                                    {{__('cp.winner_prize')}} $
                                </label>
                                <div class="col-md-6">
                                    <input   type="text" onkeyup=" if (/\D/g.test(this.value)) this.value = this.value.replace(/[^0-9.]/g,'')"
                                             class="form-control" name="winner_prize" value="{{($item->winner_prize)}}" id="winner_prize"
                                             placeholder=" {{__('cp.winner_prize')}}"  required>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="order">
                                    {{__('cp.order_priority')}} 
                                </label>
                                <div class="col-md-6">
                                    <input   type="text" onkeyup=" if (/\D/g.test(this.value)) this.value = this.value.replace(/[^0-9.]/g,'')"
                                             class="form-control" name="order_priority" value="{{($item->order_priority)}}" id="order_priority"
                                             placeholder=" {{__('cp.order_priority')}}"  required>
                                </div>
                            </div>
                        </fieldset>

                            <fieldset>
                                <div class="form-group" id="chamiopnName">
                                    <label class="col-sm-2 control-label" >{{__('cp.chamiopnName')}}
                                    </label>
                                    <div class="col-md-6">
                                        <select id="multiple2" class="form-control select2"
                                                name="chamiopnName" >
                                            <option value="">{{__('cp.select')}}</option>
                                            @foreach($championClub as $club)
                                          <option value="{{$club->club_id}}"{{ (($item->champion_id) == $club->club_id) ? "selected":"" }}>
                                      {{ $club->club->name }}</option>
                                               
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                                <fieldset>
                                    <legend>{{__('cp.image')}}</legend>
                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-3">
                                            <div class="fileinput-new thumbnail"
                                                 onclick="document.getElementById('edit_image').click()"
                                                 style="cursor:pointer">
                                                <img src="{{url($item->image)}}" id="editImage">
                                            </div>
                                            <div class="btn red"
                                                 onclick="document.getElementById('edit_image').click()">
                                                <i class="fa fa-pencil"></i>
                                            </div>
                                            <input type="file" class="form-control" name="image"
                                                   id="edit_image"
                                                   style="display:none">
                                        </div>
                                    </div>
                                </fieldset>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">{{__('cp.edit')}}</button>
                                        <a href="{{url(getLocal().'/admin/champions')}}" class="btn default">{{__('cp.cancel')}}</a>
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

    <script>

        $('#edit_image').on('change', function (e) {

            readURL(this, $('#editImage'));

        });

    </script>

@endsection

