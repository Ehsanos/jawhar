@extends('layout.adminLayout')
@section('title') {{__('cp.azkar')}}  {{__('cp.sort')}} 
@endsection
@section('css')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <style>
  #sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
  #sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; }
  #sortable li span { position: absolute; margin-left: -1.3em; }
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
                              style="color: #e02222 !important;">{{__('cp.sort')}}</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <form method="post" action="{{url(app()->getLocale().'/admin/newss/sort')}}"
                          enctype="multipart/form-data" class="form-horizontal" role="form" id="formId">
                        {{ csrf_field() }}
                        <div class="form-body">
    
    
                                    <ul id="sortable">
                                    @foreach($items as $item)
                                          <li class="ui-state-default"><span class="idQuantity" data-id="{{$item->id}}"></span>{{$item->title}}</li>
                                          
                                    @endforeach      
                                  </ul>

                          <input type="hidden" id="inputArrayproducts" name="inputArrayproducts">
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green" id="submitForm">{{__('cp.edit')}}</button>
                                        <a href="{{url(getLocal().'/admin/news')}}{{request()->segment("5") != null ? "/".request()->segment("5") :""}}" class="btn default">{{__('cp.cancel')}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    
  
</ul>
 


                            
         </div>
        </div>
    </div>
@endsection

@section('js')
@endsection
@section('script')
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
    $( "#sortable" ).sortable();
    $( "#sortable" ).disableSelection();
  } );
  
  
   var str = '';

     $(document).on('click','#submitForm',function (e) {
                  e.preventDefault();
                  var ele = $(this);

                 $( ".idQuantity" ).each(function() {
                      
                      str += $(this).data("id")+','; //$(this).val()+'#$#';

                    });

                 $('#inputArrayproducts').val(str);
                 
                 /***************** Validation ********************/
                 

                 $('#formId').submit() ;

               });
               
               
  </script>
 
@endsection
