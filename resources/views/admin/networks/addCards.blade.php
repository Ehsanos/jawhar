@extends('layout.adminLayout')
@section('title'){{__('cp.add')}} {{__('cp.cards')}}
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
                              style="color: #e02222 !important;">{{__('cp.add')}} {{__('cp.cards')}}</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <form method="post" action="{{url(app()->getLocale().'/admin/networks/'.$network->id.'/addCards')}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form" id="form">
                        {{ csrf_field() }}
                        <div class="form-body">
                            

                            <fieldset>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="name">
                                            {{__('cp.cardId')}}
                                            <span class="symbol">*</span>
                                        </label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="card_id"
                                                   id="card_id"
                                                   value="{{ old('card_id')}}" required>
                                        </div>
                                    </div>
                            </fieldset>
                            <fieldset>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="pin">
                                            {{__('cp.pin')}}
                                            <span class="symbol">*</span>
                                        </label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="pin"

                                                   id="pin"
                                                   value="{{ old('pin') }}" required>
                                        </div>
                                    </div>

                            </fieldset>
                            <fieldset>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="password">
                                            {{__('cp.password')}}
                                            <span class="symbol">*</span>
                                        </label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="password"

                                                   id="password"
                                                   value="{{ old('password') }}" required>
                                        </div>
                                    </div>

                            </fieldset>


                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">{{__('cp.submit')}}</button>
                                        <a href="{{url(getLocal().'/admin/networks/'.$network->id.'/cards')}}" class="btn default">{{__('cp.cancel')}}</a>
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
    $(document).on('change','.category',function(e){
        var category = $(this).val();
        var url = "{{ url(app()->getLocale().'/admin/getSubCategoryByCategoryId/') }}";

        if(category){
            $.ajax({
                type: "GET",
                url: url+'/'+category,
                success: function (response) {
                    if(response)
                    {
                        $(".sub_category").empty();
                        $(".sub_category").append('<optgroup label="{{__('cp.area')}}">');
                        $.each(response, function(index, value){
                            $(".sub_category").append('<option value="'+value.id+'">'+ value.name +'</option>');
                            $(".sub_category").append('</optgroup>');
                        });
                    }
                }
            });
        }
        else{
            $(".area").empty();
        }
    });

$(document).ready(function(){
    $('form').ajaxForm({
        beforeSend:function(){
            $('#success').empty();
            $('.progress-bar').text('0%');
            $('.progress-bar').css('width', '0%');
        },
        uploadProgress:function(event, position, total, percentComplete){
            $('.progress-bar').text(percentComplete + '0%');
            $('.progress-bar').css('width', percentComplete + '0%');
        },
        success:function(data)
        {
            if(data.success)
            {
                $('#success').html('<div class="text-success text-center"><b>'+data.success+'</b></div><br /><br />');
                $('#success').append(data.image);
                $('.progress-bar').text('Uploaded');
                $('.progress-bar').css('width', '100%');
            }
        }
    });
});
</script>

<script type="text/javascript">

    $(document).ready(function() {

      $(".btn-success").click(function(){
          var html = $(".clone").html();
          $(".increment").after(html);
      });

      $("body").on("click",".btn-danger",function(){
          $(this).parents(".control-group").remove();
      });

    });

</script>
    <script>

        $('#edit_image').on('change', function (e) {

            readURL(this, $('#editImage'));

        });



    </script>
@endsection
