@extends('layout.adminLayout')
@section('title') {{__('cp.products_management')}}
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
                              style="color: #e02222 !important;">{{ucwords(__('cp.edit_product'))}}</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <form method="post" action="{{url(app()->getLocale().'/admin/products/'.$product->id)}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form" id="form">
                        {{ csrf_field() }}
                        {{ method_field('PATCH')}}

                        <fieldset>
                            @foreach($locales as $locale)
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="name_{{$locale->lang}}">
                                        {{__('cp.name_'.$locale->lang)}}
                                        <span class="symbol">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="name_{{$locale->lang}}"
                                           
                                               id="name_{{$locale->lang}}"
                                               value="{{ old('name_'.$locale->lang, $product->translate($locale->lang)->name) }}" required>
                                    </div>
                                </div>
                            @endforeach
                        </fieldset>

                        <fieldset>
                            @foreach($locales as $locale)
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="description_{{$locale->lang}}">
                                        {{__('cp.description_'.$locale->lang)}}
                                        <span class="symbol">*</span>
                                    </label>
                                    <div class="col-md-6">
                                       

                                            <textarea rows="6" class="form-control" name="description_{{$locale->lang}}"  id="description_{{$locale->lang}}" required="" aria-required="true">{{ old('description_'.$locale->lang, $product->translate($locale->lang)->description) }}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        </fieldset>


                                      <fieldset>
                                    <div class="form-group" id="category">
                                        <label class="col-sm-2 control-label" >{{__('cp.category')}}
                                            
                                        </label>
                                        <div class="col-md-6">
                                        <select id="category" class="form-control select category"
                                              name="category_id">
                                            <option value="" >{{__('cp.select')}}</option>
                                           @foreach($categories as $one)
                                             <option value="{{$one->id}}" @if($product->category_id==$one->id) selected @endif>{{$one->name}}</option>
                                           @endforeach
                                      </select>
                                        </div>
                                    </div>
                                </fieldset>                           

                                
                                <fieldset>
                                <div class="form-group" id="sub_category">
                                    <label class="col-sm-2 control-label" >{{__('cp.subcategory')}}
                                        <span class="symbol">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <select class="form-control select sub_category" id="sub_category" required name="sub_category" data-init-plugin="select2">
                                             <option value="{{@$subcategories->id}}">{{@$subcategories->name}}</option>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                        <fieldset>
                            <div class="form-group">

                            <fieldset>
                                <div class="form-group" id="is_dollar">
                                    <label class="col-sm-2 control-label" >العملة
                                    </label>
                                    <div class="col-md-6">
                                        <select id="multiple2" class="form-control select is_dollar"
                                                name="is_dollar" required>
                                            <option value="">{{__('cp.select')}}</option>
                                            
                                            <option value="0" @if($product->is_dollar == 0) selected @endif >  سعر المنتج بالتركي     </option>
                                            <option value="1"@if($product->is_dollar == 1) selected @endif >  سعر المنتج بالدولار     </option>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>                                <div class="form-group">


                                <label class="col-sm-2 control-label" for="order">
                                    {{__('cp.price')}}
                                    <span class="symbol">*</span>
                                </label>

                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="price" min="0"   value="@if($product->is_dollar == 1){{$product->real_price}}@else {{$product->price}}@endif" required> @if($product->is_dollar == 1){{$product->real_price}} $  = ( {{$product->result($product->real_price)}} )₺
                            @else
                                {{$product->price}} ₺
                            @endif
                                </div>
                            </div>
                            </div>
                        </fieldset>
                        
                       <fieldset>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="order">
                                {{__('cp.count')}}
                                <span class="symbol">*</span>
                            </label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="count" min="0" value="{{$product->count}}" >
                            </div>

                        </div>
                    </fieldset>

                        

                        <fieldset>
                            <div class="form-group" id="gover_option">
                                <label class="col-sm-2 control-label" >{{__('cp.most_selling')}}
                                 
                                </label>
                                <div class="col-md-6">                                      
                                   <input type="checkbox" name="most_selling" value="1" @if($product->most_selling==1) checked @endif>                                        
                                </div>
                            </div>
                        </fieldset>
                        
                          <fieldset>
                            <div class="form-group" id="gover_option">
                                <label class="col-sm-2 control-label" >{{__('cp.new_product')}}
                                    
                                </label>
                                <div class="col-md-6">                                      
                                   <input type="checkbox" name="newest" value="1" @if($product->newest==1) checked @endif>                                        
                                </div>
                            </div>
                        </fieldset>
                        
                        
                            <!--<fieldset>-->
                            <!--    <legend>{{__('cp.discount')}}</legend>-->
                            <!--</fieldset>-->

                            <!-- <fieldset>-->
                            <!--    <div class="form-group">-->
                            <!--        <label class="col-sm-2 control-label" for="order">-->
                            <!--            {{__('cp.discount')}} %-->
                            <!--        </label>-->
                            <!--        <div class="col-md-6">-->
                            <!--            <input type="text" class="form-control" name="discount" value="{{$product->discount}}"-->
                            <!--                   placeholder=" {{__('cp.discount')}}"/>-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--</fieldset>-->

                            <!--<fieldset>-->
                            <!--    <div class="form-group">-->
                            <!--        <label class="col-sm-2 control-label" >-->
                            <!--            {{__('cp.offer_time')}}-->
                            <!--            <span class="symbol">*</span>-->
                            <!--        </label>-->
                            <!--        <label class="col-sm-1 control-label">-->
                            <!--            {{__('cp.from')}}-->
                            <!--        </label>-->
                            <!--        <div class="col-md-2">-->
                            <!--            <input type="date" class="form-control" name="offer_from"-->
                            <!--                  value="{{$product->offer_from}}"  placeholder="  {{__('cp.from')}}" >-->
                            <!--        </div>-->

                            <!--        <label class="col-sm-1 control-label">-->
                            <!--            {{__('cp.to')}}-->
                            <!--        </label>-->
                            <!--        <div class="col-md-2">-->
                            <!--            <input type="date" class="form-control" name="offer_to"-->
                            <!--                  value="{{$product->offer_to}}" placeholder="  {{__('cp.to')}}" >-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--</fieldset>-->
                        
                        <fieldset>
                            <legend>{{__('cp.image')}}</legend>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-3">
                                    <div class="fileinput-new thumbnail"
                                         onclick="document.getElementById('edit_image').click()"
                                         style="cursor:pointer">
                                        <img src="{{url($product->image)}}" id="editImage">
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


 <fieldset>
                            <legend>{{__('cp.images')}}</legend>
                            <div class="form-group {{ $errors->has('image') ? ' has-error' : '' }}">
                                <div class="col-md-6 col-md-offset-3">
                                    @if ($errors->has('image'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('image') }}</strong>
                                        </span>
                                    @endif
                                    <div class="imageupload" style="display:flex;flex-wrap:wrap">
                                        @foreach($product->attachments as $one)
                                            <div class="imageBox text-center" style="width:150px;height:190px;margin:5px">
                                                <img src="{{$one->product_img}}" style="width:150px;height:150px">
                                                <button class="btn btn-danger deleteImage" type="button">{{__("cp.remove")}}</button>
                                                <input class="attachedValues" type="hidden" name="oldImages[]" value="{{$one->id}}">
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="input-group control-group increment" >
                                        <div class="input-group-btn"  onclick="document.getElementById('all_images').click()"> 
                                          <button class="btn btn-success" type="button"><i class="glyphicon glyphicon-plus"></i>{{__("cp.addImages")}}</button>
                                        </div>
                                        <input type="file" class="form-control hidden"  accept="image/*" id="all_images"  multiple />
                                    </div>
                                </div>
                            </div>
                        </fieldset>


                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn green">{{__('cp.submit')}}</button>
                                    <a href="{{url(getLocal().'/admin/products')}}" class="btn default">{{__('cp.cancel')}}</a>
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
    <script>
      $('#all_images').on('change', function (e) {
            readURLMultiple(this, $('.imageupload'));
        });
        
        $('#edit_image').on('change', function (e) {

            readURL(this, $('#editImage'));

        });

    </script>

              <script>
  
    $(document).on('change','.category',function(e){
    var category_id = $(this).val();
    var url = "{{ url(app()->getLocale().'/admin/subcategoryByCategory/') }}";

      if(category_id){
        $.ajax({
          type: "GET",
          url: url+'/'+category_id,
          success: function (response) {
              if(response)
              {
                $(".sub_category").empty();
                $(".sub_category").append('<optgroup label="{{__('cp.subcategory')}}">');
                $.each(response, function(index, value){
                  $(".sub_category").append('<option value="'+value.id+'">'+ value.name +'</option>');
                  $(".sub_category").append('</optgroup>');
                });
              }
          }
        });
      }
      else{
        $(".gate").empty();
      }
});
    </script>

@endsection