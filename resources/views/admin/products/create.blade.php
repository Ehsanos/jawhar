@extends('layout.adminLayout')
@section('title'){{__('cp.products_management')}}
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
                              style="color: #e02222 !important;">{{__('cp.add_product')}}</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <form method="post" action="{{url(app()->getLocale().'/admin/products')}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form" id="form">
                        {{ csrf_field() }}
                        <div class="form-body">

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
                                                   value="{{ old('name_'.$locale->lang) }}" required>
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
                                            <textarea rows="6" class="form-control" name="description_{{$locale->lang}}"  id="description_{{$locale->lang}}" required="" aria-required="true"></textarea>

                                            {{-- <textarea rows="4" cols="50" class="form-control" name="description_{{$locale->lang}}"

                                                   id="description_{{$locale->lang}}"
                                                    required>
                                                </textarea> --}}


                                        </div>
                                    </div>
                                @endforeach
                            </fieldset>

                            <fieldset>
                                <div class="form-group" id="category">
                                    <label class="col-sm-2 control-label" >{{__('cp.mainCategory')}}
                                        <span class="symbol">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <select id="multiple2" class="form-control select category"
                                                name="category" required>
                                            <option value="">{{__('cp.select')}}</option>
                                            @foreach($categories as $category)
                                                <option value="{{$category->id}}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{$category->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group" id="sub_category_id">
                                    <label class="col-sm-2 control-label" >{{__('cp.subcategory')}}
                                        <span class="symbol">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <select class="form-control select sub_category_id" id="sub_category_id" required name="sub_category_id" data-init-plugin="select2">
                                        </select>
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
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="order">
                                        {{__('cp.price')}}
                                        <span class="symbol">*</span>
                                    </label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="price" min="0"  value="{{old('price')}}" required>
                                    </div>

                                </div>
                            </fieldset>
                            <fieldset>
                                    <label class="col-sm-2 control-label" for="order">
                                        {{__('cp.count_product')}}
                                        <span class="symbol">*</span>
                                    </label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="product_count" min="0"  value="{{old('count')}}" >
                                    </div>

                                </div>


                        <fieldset>
                            <div class="form-group" id="gover_option">
                                <label class="col-sm-2 control-label" >{{__('cp.most_selling')}}

                                </label>
                                <div class="col-md-6">
                                   <input type="checkbox" name="top_selling" value="1">
                                </div>
                            </div>
                        </fieldset>

                                                <fieldset>
                            <div class="form-group" id="gover_option">
                                <label class="col-sm-2 control-label" >{{__('cp.new_product')}}

                                </label>
                                <div class="col-md-6">
                                   <input type="checkbox" name="newest" value="1">
                                </div>
                            </div>
                        </fieldset>


                            <fieldset>
                                <legend>{{__('cp.discount')}}</legend>
                            </fieldset>

                             <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="order">
                                        {{__('cp.discount')}} %
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="discount" value="{{ old('discount',0) }}"
                                               placeholder=" {{__('cp.discount')}}"/>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" >
                                        {{__('cp.offer_time')}}
                                        <span class="symbol">*</span>
                                    </label>
                                    <label class="col-sm-1 control-label">
                                        {{__('cp.from')}}
                                    </label>
                                    <div class="col-md-2">
                                        <input type="date" class="form-control" name="offer_from"
                                               placeholder="  {{__('cp.from')}}" >
                                    </div>

                                    <label class="col-sm-1 control-label">
                                        {{__('cp.to')}}
                                    </label>
                                    <div class="col-md-2">
                                        <input type="date" class="form-control" name="offer_to"
                                               placeholder="  {{__('cp.to')}}" >
                                    </div>
                                </div>
                            </fieldset>




                            <fieldset>
                                <legend>{{__('cp.image')}}</legend>
                                <div class="form-group {{ $errors->has('image') ? ' has-error' : '' }}">
                                    <div class="col-md-6 col-md-offset-3">
                                        @if ($errors->has('image'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('image') }}</strong>
                                            </span>
                                        @endif
                                        <div class="fileinput-new thumbnail"
                                             onclick="document.getElementById('edit_image').click()"
                                             style="cursor:pointer">
                                            <img src=" {{url(admin_assets('/images/ChoosePhoto.png'))}}"  id="editImage" value="{{old('image')}}" >

                                        </div>
                                        <div class="btn red" onclick="document.getElementById('edit_image').click()">
                                            <i class="fa fa-pencil"></i>
                                        </div>
                                        <input type="file" class="form-control" name="image" value="{{old('image')}}"
                                               id="edit_image" required
                                               style="display:none" >
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
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
 <script>
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
                $(".sub_category_id").empty();
                $(".sub_category_id").append('<optgroup label="{{__('cp.subcategory')}}">');
                $.each(response, function(index, value){
                  $(".sub_category_id").append('<option value="'+value.id+'">'+ value.name +'</option>');
                  $(".sub_category_id").append('</optgroup>');
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



