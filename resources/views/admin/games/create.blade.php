@extends('layout.adminLayout')
@section('title') {{__('cp.games')}}
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
                    <form method="post" action="{{url(app()->getLocale().'/admin/games')}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form" id="form_city">
                        {{ csrf_field() }}

                        <div class="form-body">
                            
                            
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            {{__('cp.name')}}
                                        </label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="name"
                                                   placeholder=" {{__('cp.name')}}"
                                                   value="{{ old('name') }}" >
                                        </div>
                                    </div>
                            </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label ">
                                            {{__('cp.city')}}
                                        </label>
                                        <div class="col-md-6">
                                            <select name="city_id" class="form-control" required>
                                                <option value="0">{{ __('cp.all') }}</option>
                                                @foreach($cities as $one)
                                                    <option value="{{$one->id}}">{{$one->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label ">
                                            {{__('cp.is_game')}}
                                        </label>
                                        <div class="col-md-6">
                                            <select name="is_game_player" class="form-control" required>
                                                <option
                                                    value="1" {{old('is_game_player') == '1'?'selected':''}}>
                                                    {{__('cp.yes')}}
                                                </option>
                                                <option
                                                    value="0" {{old('is_game_player') == '0'?'selected':''}}>
                                                    {{__('cp.No')}}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label ">
                                            {{__('cp.quantity')}}
                                        </label>
                                        <div class="col-md-6">
                                            <select name="is_quantity" class="form-control" required>
                                                <option
                                                    value="1" {{old('is_quantity') == '1'?'selected':''}}>
                                                    {{__('cp.yes')}}
                                                </option>
                                                <option
                                                    value="0" {{old('is_quantity') == '0'?'selected':''}}>
                                                    {{__('cp.No')}}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        {{__('cp.min_quantity')}}
                                    </label>
                                    <div class="col-md-6">
                                        <input type="number" min='1' class="form-control" name="min_quantity"
                                               placeholder=" {{__('cp.min_quantity')}}"
                                               value="1" >
                                    </div>
                                </div>
                            </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label ">
                                            {{__('cp.status')}}
                                        </label>
                                        <div class="col-md-6">
                                            <select name="status" class="form-control" required>
                                                <option
                                                    value="active" {{old('status') == 'active'?'selected':''}}>
                                                    {{__('cp.active')}}
                                                </option>
                                                <option
                                                    value="not_active" {{old('status') == 'not_active'?'selected':''}}>
                                                    {{__('cp.not_active')}}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>


                            <fieldset>
                                <legend>{{__('cp.logo')}}</legend>
                                <div class="form-group {{ $errors->has('logo') ? ' has-error' : '' }}">
                                    <div class="col-md-6 col-md-offset-3">
                                        @if ($errors->has('logo'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('logo') }}</strong>
                                            </span>
                                        @endif
                                        <div class="fileinput-new thumbnail"
                                             onclick="document.getElementById('edit_image').click()"
                                             style="cursor:pointer">
                                            <img src=" {{url(admin_assets('/images/ChoosePhoto.png'))}}"  id="editImage" >

                                        </div>
                                        <div class="btn red" onclick="document.getElementById('edit_image').click()">
                                            <i class="fa fa-pencil"></i>
                                        </div>
                                        <input type="file" class="form-control" name="image"
                                               id="edit_image" required
                                               style="display:none" >
                                    </div>
                                </div>
                            </fieldset>


                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">{{__('cp.add')}}</button>
                                        <a href="{{url(getLocal().'/admin/games')}}" class="btn default">{{__('cp.cancel')}}</a>
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
                $('.show_price').attr('disabled',true);
                var id = $(this).find('option:selected').data('id');
                var url = $(this).find('option:selected').data('url');
                $.ajax({
                    url: "{{ url(getLocal().'/admin/getDataFromApi') }}/"+id,
                    type: "get",
                    data: {
                        // _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (data) {
                        // console.log(JSON.parse(data.message));
                        $('#game').show();
                        $('#target_id').empty();
                        $('#target_id').append('<option> @lang("cp.choose") </option>');
                        if(id == 1){
                            $.each(JSON.parse(data.message), function (index, one) {
                               $('#target_id').append('<option value="' + one.id + '"  data-price="' + one.price + '">' + one.name + '</option>');
                            })
                        }else if(id == 2){
                            $('.show_price').attr('disabled',false);

                            $.each(JSON.parse(data.message).products, function (index, one) {
                              $('#target_id').append('<option value="' + one.id + '"  data-price="' + 0 + '">' + one.name + '</option>');
                            })
                        }else if(id == 3){
                            $.each(JSON.parse(data.message), function (index, one) {
                               $('#target_id').append('<option value="' + one.id + '"  data-price="' + one.price + '">' + one.name + '</option>');
                            })
                        }else if(id == 4){
                          
                          
                        }else if(id == 5){
                               $('.show_price').attr('disabled',false);

                               $('#target_id').append('<option value="2"  data-price="0">Ahlan chat</option>');
                               $('#target_id').append('<option value="7"  data-price="0">Soul shail</option>');
                               $('#target_id').append('<option value="8"  data-price="0">Oohla chat</option>');
                               $('#target_id').append('<option value="9"  data-price="0">LIKE- LIVE</option>');
                               $('#target_id').append('<option value="10"  data-price="0">BIGO- LIVE</option>');
                               $('#target_id').append('<option value="11"  data-price="0">Lama chat</option>');
                               $('#target_id').append('<option value="12"  data-price="0">AZAL LIVE</option>');
                               $('#target_id').append('<option value="13"  data-price="0">Lami chat</option>');
                               $('#target_id').append('<option value="14"  data-price="0">Light chat</option>');
                               $('#target_id').append('<option value="15"  data-price="0">Haki chat</option>');
                               $('#target_id').append('<option value="17"  data-price="0">Haya chat</option>');
                               $('#target_id').append('<option value="18"  data-price="0">UP LIVE</option>');
                               $('#target_id').append('<option value="19"  data-price="0">Hawa chat</option>');
                               $('#target_id').append('<option value="21"  data-price="0">Sama chat</option>');
                               $('#target_id').append('<option value="23"  data-price="0">talk talk</option>');
                               $('#target_id').append('<option value="24"  data-price="0">sango chat</option>');
                               $('#target_id').append('<option value="47"  data-price="0">جواكر توكين</option>');

                        }
                    }
                })
            });
            
            
     $(document).on('change' , '#target_id' , function (e){
        //  alert('123');
        var price = $(this).find('option:selected').data('price');
        $('.price').val(price);
        $('.show_price').val(price);
    });
</script>
    
  
@endsection

