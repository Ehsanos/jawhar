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
                              style="color: #e02222 !important;">{{__('cp.add')}}</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <form method="post" action="{{url(app()->getLocale().'/admin/ashab')}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form" id="form_city">
                        {{ csrf_field() }}
                        <div class="form-body">

                            <fieldset>
                                <div class="form-group" id="game_id">
                                    <label class="col-sm-2 control-label" >{{__('المنتج')}}
                                        <span class="symbol"></span>
                                    </label>
                                    <div class="col-md-6">
                                        <select id="game_id" class="form-control select "
                                                name="game_id" required>
                                            <option value="">{{__('cp.select')}}</option>
                                            @foreach($lolo as $game)
                                                <option value="{{$game->id}}" service="{{$game->service}}" {{ old('$game') == $game->id ? 'selected' : '' }}>
                                                    {{$game->name}}- {{$game->id}}
                                                </option>
                                            @endforeach
                                        </select>
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
                                               value="{{ old('name') }}" >
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group" id="game_status">
                                    <label class="col-sm-2 control-label" >{{__('حالة الباقة')}}
                                        <span class="symbol"></span>
                                    </label>
                                    <div class="col-md-6">
                                        <select id="game_status" class="form-control select "
                                                name="game_status" required>
                                            <option value="0">فعال</option>
                                            <option value="1">غير فعال</option>
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
                                                <option value="{{$loop->index+1}}">{{$one_as}}</option>
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
                                               value="{{ old('game_text') }}" >
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group" id="game_num">
                                    <label class="col-sm-2 control-label" >{{__('الأنواع')}}
                                        <span class="symbol"></span>
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" readonly class="form-control game_num" name="game_num"
                                               value="{{ old('game_num') }}" >
                                    </div>
                                </div>
                            </fieldset>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">{{__('cp.add')}}</button>
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
    <script>
        $('#game_id').change(function(){
            $('.game_num').val($('#game_id').find(":selected").attr("service"));
        });

        $('#permissions').on('select2:select', function (e) {
            if(e.valueOf() == "1")
            {
                $('#game_tabe').css({display: 'block'});
            }
        });
        $('#permissions').on('select2:unselect', function (e) {
            if(e.valueOf() == "1")
            {
                $('#game_tabe').css({display: 'none'});
            }
        });
        $(document).ready(function() {
            $('#multiple2').change(function(){
                var tid = $('#multiple2').val();
                if(tid){
                    $.ajax({
                        type:"get",
                        url:"{{url('admin/admins/get_cities_public_services')}}/"+tid,
                        success:function(res)
                        {
                            $('#public_services_id').empty();
                            $('#public_services_id').append("<option value=''>{{__('cp.all')}}</option>");
                            if(res)
                            {
                                $.each(res,function(key,value){
                                    $('#public_services_id').append("<option value='"+value+"'>"+key+"</option>");
                                });
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection

