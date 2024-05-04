@extends('layout.adminLayout')
@section('title') {{__('cp.promo_codes')}}
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
                              style="color: #e02222 !important;">{{__('cp.addquestion')}}</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <form method="post" action="{{url(app()->getLocale().'/admin/promo_codes')}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form">
                        {{ csrf_field() }}

                        <div class="form-body">
                            <fieldset>
                                <div class="form-group" id="category">
                                    <label class="col-sm-2 control-label" >{{__('cp.type')}}
                                        <span class="symbol">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <select id="multiple2" class="form-control select target_type"
                                                name="target_type" required>
                                            <option value="">{{__('cp.select')}}</option>
                                            <option value="1" data-type="1" data-items="{{$product_servics}}" >{{__('cp.product_servic')}}</option>
                                            <option value="2" data-type="2" data-items="{{$networks}}" >{{__('cp.networks')}}</option>
                                            <option value="3" data-type="3" data-items="{{$game_servies}}" >{{__('cp.game_services')}}</option>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <div class="form-group" id="category">
                                    <label class="col-sm-2 control-label" >{{__('cp.items')}}
                                        <span class="symbol">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <select id="multiple2" class="form-control select select2 items"
                                                name="items[]" required multiple="multiple">
                                            <option value="">{{__('cp.select')}}</option>
                                            
                                            
                                        </select>
                                    </div>
                                </div>
                            </fieldset>


                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('cp.percent')}}
                                        <span class="symbol">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <input type="number" class="form-control" step='any' min="0" name="percent" required>
                                    </div>
                                </div>
                            </fieldset>


                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('cp.max_usage')}}
                                        <span class="symbol">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <input type="number" class="form-control" step='any' min="0" name="max_usage" required>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('cp.end_date')}}
                                        <span class="symbol">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <input type="date" class="form-control" name="end_date" required>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('cp.quantity')}}
                                        <span class="symbol">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <input type="number" class="form-control" name="quantity" min="0" required>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <div class="form-group" id="category">
                                    <label class="col-sm-2 control-label" >{{__('cp.status')}}
                                        <span class="symbol">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <select  class="form-control select"
                                                name="status" required>
                                            <option value="active">{{__('cp.active')}}</option>
                                            <option value="not_active">{{__('cp.not_active')}}</option>
                                    </select>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">{{__('cp.add')}}</button>
                                        <a href="{{url(getLocal().'/admin/promo_codes')}}" class="btn default">{{__('cp.cancel')}}</a>
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
        $(document).on('change' , '.target_type' , function (e){
            var items = $(this).find('option:selected').data('items');
            var  type = $(this).find('option:selected').data('type');
            
            $('.items').empty();
            
            if(type == 1){
                $.each(items, function (index, one) {
                    $('.items').append('<option value="' + one.id + '"  data-id="' + one.id + '">' + one.name + '</option>');
                })
            }else if(type == 2){
                $.each(items, function (index, one) {
                    $('.items').append('<option value="' + one.id + '"  data-id="' + one.id + '">' + one.name + '</option>');
                })
            }else if(type == 3){
                $.each(items, function (index, one) {
                    $('.items').append('<option value="' + one.id + '"  data-id="' + one.id + '">' + one.size+" - "+one.api_name+" - "+one.api_product_name  + '</option>');
                })
            }
            
        });

</script>

@endsection

