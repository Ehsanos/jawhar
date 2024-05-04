@extends('layout.adminLayout')
@section('title') ملف التحميل المباشر
@endsection
@section('css')
@endsection
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-body">
            <div class="table-toolbar">
                <div class="row">
                    <div class="col-sm-9">
                        <div class="btn-group">

                            <a href="{{url(app()->getLocale().'/admin/apk/create')}}" style="margin-right: 5px"
                               class="btn sbold green">{{__('cp.add')}}
                                <i class="fa fa-plus"></i>
                            </a>
                        
                           
                        </div>
                    </div>

                </div>
         
            </div>
            <table class="table table-striped table-bordered table-hover table-checkable order-column" id="toolsTable">
                <thead>
                <tr>

                    <th> {{ucwords(__('cp.name'))}}</th>
                </tr>
                </thead>
                <tbody>
                    <tr class="odd gradeX" >
                        <td> <a href="{{url('uploads/apk/Jawhar.apk')}}"</a>الملف  </td>

                    </tr>

                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('js')
@endsection
@section('script')
@endsection
