@extends('layout.adminLayout')
@section('title') {{__('cp.gameServies')}}
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
                    <form method="post" action="{{url(app()->getLocale().'/admin/gameServies')}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form" id="form_city">
                        {{ csrf_field() }}

                        <div class="form-body">
                          <fieldset>
                           <div class="form-group">
                                    <label class="col-sm-2 control-label" >{{__('cp.game')}}
                                        <span class="symbol">*</span>
                                    </label>
                                    <div class="col-md-3">
                                        <select id="multiple2" class="form-control select"
                                                name="game_id" required>
                                            <option value="">{{__('cp.select')}}</option>
                                            @foreach($games as $game)
                                                <option value="{{$game->id}}" {{ old('game_id') == $game->id ? 'selected' : '' }}>
                                                    {{@$game->name}} ----> {{@$game->city_id == 0 ? __('cp.all') : @$game->city->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                            </div>
                            </fieldset>
                            
                            <fieldset>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label ">
                                            {{__('cp.type')}}
                                        </label>
                                        <div class="col-md-6">
                                            <select name="api_id" id="api_id" class="form-control">
                                                <option data-id = "0" value="0">{{ __('cp.not_from_api') }}</option>
                                                @foreach($apis as $one)
                                                    <option data-id = "{{$one->id}}" data-url="{{$one->url}}" value="{{$one->id}}">{{$one->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                            </fieldset>
                            
                            <input type="text" class="form-control price" name="price"
                                               style="display:none" >
                            
                            <fieldset id="game" style="display:none;">
                                    <div class="form-group" >
                                        <label class="col-sm-2 control-label ">
                                            {{__('cp.target')}}
                                        </label>
                                        <div class="col-md-6">
                                            <select name="target_id" id="target_id" class="form-control" >
                                                
                                            </select>
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="form-group" id="ashab" style="display:none;">
                                        <label class="col-sm-2 control-label">
                                            {{__('cp.denomination_id')}}
                                        </label>
                                        <div class="col-md-6">
                                            <select name="denomination_id" id="denomination_id" class="form-control" >
                                                
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <!--<div class="form-group">-->
                                    <!--    <label class="col-sm-2 control-label">-->
                                    <!--        {{__('cp.price')}}-->
                                    <!--    </label>-->
                                    <!--    <div class="col-md-6">-->
                                    <!--        <input type="text" class="form-control show_price" name="price"-->
                                    <!--               placeholder=" {{__('cp.price')}}"-->
                                    <!--               value="{{ old('price') }}" disabled="disabled">-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            {{__('cp.commission')}}
                                        </label>
                                        <div class="col-md-6">
                                            <input type="number" class="form-control" name="commission"
                                                   placeholder=" {{__('cp.commission')}}"
                                                   value="{{ old('commission') }}" >
                                        </div>
                                    </div>
                                    
                            </fieldset>
                            
                            
                            <fieldset>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            {{__('cp.size')}}
                                        </label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="size"
                                                   placeholder=" {{__('cp.size')}}"
                                                   value="{{ old('size') }}" >
                                        </div>
                                    </div>
                            </fieldset>
              
                            <fieldset>
                                <div class="form-group" id="is_dollar">
                                    <label class="col-sm-2 control-label" >العملة
                                    </label>
                                    <div class="col-md-6">
                                        <select id="multiple2" class="form-control select is_dollar"
                                                name="is_dollar" required>
                                            <option value="">{{__('cp.select')}}</option>
                                            <option value="0">
                                                سعر المنتج بالتركي
                                            </option>
                                            <option value="1">
                                                سعر المنتج بالدولار
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group" id="purchasing_price">
                                    <label class="col-sm-2 control-label">
                                        {{__('cp.purchasing_price')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="number"   class="form-control purchasing_price"  name="purchasing_price"
                                               placeholder=" {{__('cp.purchasing_price')}}"
                                               value="{{ old('purchasing_price') }}" >
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            {{__('cp.price')}}
                                        </label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control show_price" name="price"
                                                   placeholder=" {{__('cp.price')}}"
                                                   value="{{ old('price') }}">
                                        </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group" id="api_product_name">
                                        <label class="col-sm-2 control-label">
                                        </label>
                                        <div class="col-md-6">
                                            <input type="hidden" class="form-control show_name"  name="api_product_name"
                                                   placeholder=" {{__('cp.name')}}"
                                                   value="" >
                                        </div>
                                </div>
                            </fieldset>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">{{__('cp.add')}}</button>
                                        <a href="{{url(getLocal().'/admin/gameServies')}}" class="btn default">{{__('cp.cancel')}}</a>
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

<script>
    $(document).on('change' , '#api_id' , function (e){
                e.preventDefault();
                var id = $(this).find('option:selected').data('id');
                var url = $(this).find('option:selected').data('url');
                $('#ashab').hide();
                if(id == 0){
                    $('.show_price').attr('disabled',false);
                }else{
                    $('.show_price').attr('disabled',true);
                }
                
                $.ajax({
                    url: "{{ url(getLocal().'/admin/getDataFromApi') }}/"+id,
                    type: "get",
                    data: {
                        // _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (data) {
                        // console.log(JSON.parse(data.message));
                        
                        if(id == 0){
                            $('#game').hide();
                            $('#api_product_name').hide();
                        }else{
                            $('#game').show();
                            $('#api_product_name').show();
                           $('#purchasing_price').hide();

                        }
                        
                        $('#target_id').empty();
                        $('#target_id').append('<option> @lang("cp.choose") </option>');
                        if(id == 1){
                            $.each(JSON.parse(data.message), function (index, one) {
                               $('#target_id').append('<option value="' + one.id + '"  data-price="' + one.price + '" data-name="' + one.name + '">' + one.name + '</option>');
                            })
                        }else if(id == 2){
                            $('#ashab').show();
                            // $('.show_price').attr('disabled',false);

                            $.each(JSON.parse(data.message).products, function (index, one) {
                              $('#target_id').append('<option value="' + one.id + '"  data-price="' + 0 + '" >' + one.name + '</option>');
                            })
                            
                        }else if(id == 3){
                            $.each(JSON.parse(data.message), function (index, one) {
                               $('#target_id').append('<option value="' + one.id + '"  data-price="' + one.price+ '" data-name="' + one.name + '">' + one.name + '</option>');
                            })
                        }else if(id == 4){
                          
                          
                        }else if(id == 5){
                               $('.show_price').attr('disabled',false);

                               $('#target_id').append('<option value="2"  data-price="0" data-name="Ahlan chat" >Ahlan chat</option>');
                               $('#target_id').append('<option value="7"  data-price="0" data-name="Soul shail">Soul shail</option>');
                               $('#target_id').append('<option value="8"  data-price="0" data-name="Oohla chat">Oohla chat</option>');
                               $('#target_id').append('<option value="9"  data-price="0" data-name="LIKE- LIVE">LIKE- LIVE</option>');
                               $('#target_id').append('<option value="10"  data-price="0" data-name="BIGO- LIVE">BIGO- LIVE</option>');
                               $('#target_id').append('<option value="11"  data-price="0" data-name="Lama chat">Lama chat</option>');
                               $('#target_id').append('<option value="12"  data-price="0" data-name="AZAL LIVE">AZAL LIVE</option>');
                               $('#target_id').append('<option value="13"  data-price="0" data-name="Lami chat">Lami chat</option>');
                               $('#target_id').append('<option value="14"  data-price="0" data-name="Light chat">Light chat</option>');
                               $('#target_id').append('<option value="15"  data-price="0" data-name="Haki chat">Haki chat</option>');
                               $('#target_id').append('<option value="17"  data-price="0" data-name="Haya chat">Haya chat</option>');
                               $('#target_id').append('<option value="18"  data-price="0" data-name="UP LIVE">UP LIVE</option>');
                               $('#target_id').append('<option value="19"  data-price="0" data-name="Hawa chat">Hawa chat</option>');
                               $('#target_id').append('<option value="21"  data-price="0" data-name="Sama chat">Sama chat</option>');
                               $('#target_id').append('<option value="23"  data-price="0" data-name="talk talk">talk talk</option>');
                               $('#target_id').append('<option value="24"  data-price="0" data-name="sango chat">sango chat</option>');
                               $('#target_id').append('<option value="47"  data-price="0" data-name="جواكر توكين">جواكر توكين</option>');

                        }else if(id == 8){
                            $.each(JSON.parse(data.message), function (index, one) {
                               $('#target_id').append('<option value="' + one.id + '"  data-price="' + one.price + '" data-name="' + one.name + '">' + one.name + '</option>');
                            })
                        }
                    }
                })
            });
            
            
            
            
    //////////////////// for ashab only////////////////////////////
    $(document).on('change' , '#target_id' , function (e){
                if($('#api_id').find('option:selected').val() == 2){
                    e.preventDefault();
                    var id = $(this).find('option:selected').val();
                    var url = $('#api_id').find('option:selected').data('url')+'/'+id;
                    
                    $.ajax({
                            url: "{{ url(getLocal().'/admin/getDataFromApi') }}/"+2+'?url='+url,
                            type: "get",
                            data: {
                                // _token: '{{csrf_token()}}'
                            },
                            dataType: 'json',
                            success: function (data) {
                                // console.log(data);
                                $('#denomination_id').empty();
                                $('#denomination_id').append('<option> @lang("cp.choose") </option>');
                                
                                    // console.log(JSON.parse(data.message).products);
                                    $.each(JSON.parse(data.message).products, function (index, one) {
                                        var is_available = '';
                                        if(one.product_available == true){
                                            is_available = '@lang("website.available")';
                                        }else{
                                            is_available =  '@lang("website.not_available")';
                                        }
                                        // alert(is_available);
                                      $('#denomination_id').append('<option value="' + one.denomination_id + '"  data-price="' + one.product_price + '" data-name="' + one.product_name + '">' + one.product_name +' - '+ is_available+ '</option>');
                                    })
                               
                            }
                        })
                }
               
            });
  
            
     $(document).on('change' , '#target_id' , function (e){
        //  alert('123');
        var price = $(this).find('option:selected').data('price');
        var name = $(this).find('option:selected').data('name');
        $('.price').val(price);
        $('.show_price').val(price);
        $('.show_name').val(name);
    });
  
            
     $(document).on('change' , '#denomination_id' , function (e){
        //  alert('123');
        var price = $(this).find('option:selected').data('price');
        var name = $(this).find('option:selected').data('name');
        $('.price').val(price);
        $('.show_price').val(price);
        $('.show_name').val(name);
    });
</script>
    


@endsection

