@extends('layout.adminLayout')
@section('title'){{__('cp.chat')}}@endsection
@section('page-style')

@endsection
@section('content-header')
@endsection
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-body">
            <div class="table-toolbar">
                                <div class="table-toolbar">

                <div class="row">

                    <div class="col-sm-9">

                        <div class="btn-group">

                            <button class="btn sbold blue btn--filter">{{__('cp.filter')}}

                                <i class="fa fa-search"></i>

                            </button>
                            

                        </div>

                    </div>



                </div>

                <div class="box-filter-collapse">

                    <form class="form-horizontal" method="get" action="{{url(getLocal().'/admin/chat')}}">



                        <div class="row">

                            <div class="col-md-5">
                                <div class="form-group row">
                                    <label class="col-md-3 control-label">{{__('cp.customer_name')}}</label>

                                    <div class="col-md-9">

                                        <input type="text" class="form-control" name="userName"

                                               placeholder="{{__('cp.customer_name')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">


                            <div class="col-md-4">

                                <div class="row">

                                    <div class="col-md-offset-3 col-md-9">

                                        <button type="submit" class="btn sbold blue">{{__('cp.search')}}

                                            <i class="fa fa-search"></i>

                                        </button>

                                        <a href="{{url(app()->getLocale().'/admin/chat')}}" type="submit"

                                           class="btn sbold btn-default ">{{__('cp.reset')}}

                                            <i class="fa fa-refresh"></i>

                                        </a>

                                    </div>

                                </div>

                            </div>

                        </div>                        

                    </form>

                </div>

            </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{__('cp.usersinchat')}}</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <hr>
                    <table id="example2" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th> {{ucwords(__('cp.profile_image'))}}</th>
                            <th>{{__('cp.name')}}</th>
                            <th>{{__('cp.unread_msg')}}</th>
                            <th>{{__('cp.action')}}</th>
                        </tr>
                        </thead>
                        <tbody>

                        
                     
                        @forelse($users as $user)
                            <tr id="issue-{{$user->id}}">
                              <td>{{$user->id}}</td>
                         <td> <img src="{{@$user->user->image_profile}}"style="width: 50px;"> </td>
                             <td>{{@$user->user->name}}
                             
                            <br>
                              {{@$user->user->email}}
                            <br>
                              {{@$user->user->mobile}}
                             </td>
                             <td><span class="badge badge-danger">{{$user->unread_count}}</span></td>
                              <td>
                                  <a href="{{url(app()->getLocale().'/admin/new_message/'. $user->user_id . '/response')}}"
                                     class="btn btn-primary" title="{{__('cp.start_chat')}}">
                                      <i class="fa fa-pencil"></i> <strong>{{__('cp.start_chat')}}</strong>
                                  </a>
                              </td>
                            </tr>
                        @empty
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
            </div>
        </div>
    </div>
@endsection

@section('js-plugins')


    <script src="https://cdn.ckeditor.com/4.7.3/full-all/ckeditor.js"></script>
@endsection

