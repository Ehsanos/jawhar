@extends('layout.adminLayout')
@section('title') {{__('cp.productImages')}}
@endsection
@section('css')
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-title">
                    
                    <div class="row">
                    <div class="col-sm-9">
                        <div class="btn-group">
                            <a href="{{url(getLocal().'/admin/addImages')}}" style="margin-right: 5px"
                               class="btn sbold green">{{__('cp.add')}}
                                <i class="fa fa-plus"></i>
                            </a>
                            
                        </div>
                    </div>

                </div>
                
                 
                <div class="portlet-body form">
                    <form method="post" action="{{url(app()->getLocale().'/admin/ads')}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form" id="form">
                        {{ csrf_field() }}

                        <div class="form-body">


                            <!--<fieldset>-->
                            <!--    <div class="form-group {{ $errors->has('image') ? ' has-error' : '' }}">-->
                            <!--        <div class="col-md-12 col-md-offset-0">-->
                            <!--            @if ($errors->has('image'))-->
                            <!--                <span class="help-block">-->
                            <!--                    <strong>{{ $errors->first('image') }}</strong>-->
                            <!--                </span>-->
                            <!--            @endif-->
                            <!--            <div class="imageupload" style="display:flex;flex-wrap:wrap">-->
                            <!--                @foreach($files as $one)-->
                            <!--                    <div class="imageBox text-center" style="width:150px;height:190px;margin:5px">-->
                            <!--                    <img src="{{url($one)}}" style="width:150px;height:150px" alt="{{$one}}" >-->
                            <!--                        <button class="btn btn-danger deleteImage1" type="button" data-id="{{$one}}" >{{__("cp.remove")}}</button>-->
                            <!--                        <input class="attachedValues" type="hidden" name="oldImages[]" >-->
                            <!--                    </div>-->
                            <!--                @endforeach-->
                            <!--            </div>-->
                                        <!--<div class="input-group control-group increment" >-->
                                        <!--    <div class="input-group-btn"  onclick="document.getElementById('all_images').click()"> -->
                                        <!--      <button class="btn btn-success" type="button"><i class="glyphicon glyphicon-plus"></i>{{__("cp.addImages")}}</button>-->
                                        <!--    </div>-->
                                        <!--    <input type="file" class="form-control hidden"  accept="image/*" id="all_images"  multiple />-->
                                        <!--</div>-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--</fieldset>-->
            <table class="table table-striped table-bordered table-hover table-checkable order-column" id="toolsTable">
                <thead>
                <tr>
                    <th width="30%"> {{ucwords(__('cp.image'))}}</th>
                    <th> {{ucwords(__('cp.name'))}}</th>
                    <th> {{ucwords(__('cp.action'))}}</th>
                </tr>
                </thead>
                <tbody>
                @forelse(@$files as $one)
                    <tr class="odd gradeX" id="">
                        <td width="30%"><img src="{{url($one)}}" width="100px" height="100px"></td>
                        <td>{{$one}}</td>
                        <td class="center">    <button class="btn btn-danger deleteImage1" type="button" data-id="{{$one}}" >{{__("cp.remove")}}</button>
</td>
                        </td>
                    </tr>
                @empty
                    {{__('cp.no')}}
                @endforelse
                
                </tbody>
            </table>


                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <!--<button type="submit" class="btn green">{{__('cp.add')}}</button>-->
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

@endsection

@section('script')

    <script>

     $(document).on("click", ".deleteImage1", function () {
        var image=$(this).data('id');
        
        if(confirm("Are you sure")) {
             $(this).parent().parent().remove();
           $.ajax({
		headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '{{url(app()->getLocale().'/admin/deleteImage')}}', 
        method: "get", 
        data: {      
            image: image,
          },
        success: function (response) {
	    	
        }
     }); 
        }
    });

 
 

    </script>





    <script>

$('#all_images').on('change', function (e) {
            readURLMultiple(this, $('.imageupload'));
        });

          $('#type').on('change', function() {

            var gover = this.value ;

            sessionStorage.setItem("type",  this.value);



            if(gover == 1){

                $('#gover_option').removeClass('hidden');

                $('#options').prop('required',true);

            }else{

                $('#gover_option').addClass('hidden');

                $('#options').prop('required',false);

            }



           

        });







   


    </script>

@endsection

