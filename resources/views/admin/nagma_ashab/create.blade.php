@extends('layout.adminLayout')
@section('title') {{__('أصحاب نجمة')}}
@endsection
@section('css')
    {{-- <style>
        ::placeholder {
      color: red;
      opacity: 1; /* Firefox */
    }

    :-ms-input-placeholder { /* Internet Explorer 10-11 */
     color: red;
    }

    ::-ms-input-placeholder { /* Microsoft Edge */
     color: red;
    }
    </style> --}}
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
                              style="color: #e02222 !important;">{{__('cp.add')}} {{__('نجمة اصحاب')}}</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <form method="post" action="{{url(app()->getLocale().'/admin/nagma_ashab')}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form" id="form">
                        {{ csrf_field() }}
                        <div class="form-body">

                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="name">
                                        {{__('cp.name')}}
{{--                                        <span class="symbol">*</span>--}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="name"
                                               id="name"
                                               value="{{ old('name')}}" required>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="average">
                                        {{__('نسبة الخصم')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" type="number" min="0" max="100" class="form-control" name="average" required>
                                    </div>
                                </div>
                            </fieldset>

{{--                            <fieldset>--}}
{{--                                <div class="form-group">--}}
{{--                                    <label class="col-sm-2 control-label"  for="ashab_games_id">{{__('قائمة المنتجات')}}--}}

{{--                                    </label>--}}
{{--                                    <div class="col-md-6">--}}
{{--                                        <select id="ashab_games_id" class="form-control select "--}}
{{--                                                name="ashab_games_id">--}}
{{--                                            <option value="" >{{__('cp.select')}}</option>--}}
{{--                                            @foreach($ashab_games_ids as $one)--}}
{{--                                                <option value="{{$one->id}}">{{$one->id}} - {{$one->game_name}}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </fieldset>--}}

                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"  for="status">{{__('cp.status')}}

                                        </label>
                                        <div class="col-md-6">
                                            <select id="status" class="form-control select "
                                                    name="status">
                                                <option value="0">{{__('cp.active')}}</option>
                                                <option value="1">{{__('cp.not_active')}}</option>
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
                                        <select class="form-control select2" id="nagma_ashab_cards_ids" name="nagma_ashab_cards_ids[]" multiple="multiple">
                                            @foreach($nagma_ashab_cards_ids as$one)
                                                <option value="{{$one->id}}">{{$one->card_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">{{__('cp.submit')}}</button>
                                        <a href="{{url(getLocal().'/admin/nagma_ashab')}}"
                                           class="btn default">{{__('cp.cancel')}}</a>
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

    <script type="text/javascript">

        $('#ashab_games_id').change(function(){
            var tid = $(this).val();
            if(tid) {
                $.ajax({
                    type: "get",
                    url: "{{url('admin/nagma_ashab/get_all_cards')}}/" + tid,
                    success: function (res) {
                        console.log(res);
                        $('#nagma_ashab_cards_ids').empty();
                        if (res) {
                            $.each(res, function (key, value) {
                                $('#nagma_ashab_cards_ids').append("<option value='" + value + "'>" + key + "</option>");
                            });
                        }
                    }

                });
            }});

    </script>
@endsection
