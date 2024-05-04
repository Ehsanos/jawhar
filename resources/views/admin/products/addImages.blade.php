@extends('layout.adminLayout')
@section('title') {{ucwords(__('cp.addImages'))}}
@endsection

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/dropzone.js"></script>
    
@section('css')
<style>
    
   .gallery{ margin-top: -8px;
    overflow: auto;
    width: 100%;
    display: flex;
    padding-top: 10px;
    flex-wrap: wrap;
   }
    
    margin-bottom: 5px;
    background-color: #ffffff;
    border: 2px dashed rgba(112, 112, 112, 0.3);
    border-radius: 8px;
    display: flex;
    align-items: center;
    align-content: center;
    width: 80px;
    object-fit: cover;
    height: 80px;
    cursor: pointer;
    
    
</style>
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
                              style="color: #e02222 !important;"> {{__('cp.addImages')}} </span>
                    </div>
                </div>
                <div class="portlet-body form">
                        <form method="post" action="{{url(app()->getLocale().'/admin/storeImage')}}" enctype="multipart/form-data" 
                  class="dropzone" id="dropzone">
    @csrf
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
        Dropzone.options.dropzone =
         {
            maxFilesize: 20,
            // renameFile: function(file) {
            //     var dt = new Date();
            //     var time = dt.getTime();
            //   return time+file.name;
            // },
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            addRemoveLinks: true,
            timeout: 10000,
            
                removedfile: function(file) 
            {
                var name = file.upload.filename;
                $.ajax({
                    headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            },
                    type: 'get',
                    url: '{{url(app()->getLocale().'/admin/removeImageFromDropZone')}}', 
                    data: {filename: name},
                    success: function (data){
                        console.log("File has been successfully removed!!");
                    },
                    error: function(e) {
                        console.log(e);
                    }});
                    var fileRef;
                    return (fileRef = file.previewElement) != null ? 
                    fileRef.parentNode.removeChild(file.previewElement) : void 0;
            },
            success: function(file, response) 
            {
                console.log(response);
            },
            error: function(file, response)
            {
               return false;
            }
};
</script>


@endsection
