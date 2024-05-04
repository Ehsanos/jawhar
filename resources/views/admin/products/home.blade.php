@extends('layout.adminLayout')
@section('title') {{__('cp.products_management')}}
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
                            <a href="{{url(app()->getLocale().'/admin/products/create')}}" style="margin-right: 5px" class="btn sbold btn-primary">
                                {{__('cp.add')}} <i class="fa fa-plus"></i>
                            </a>
                            <button class="btn sbold btn-info btn--filter">{{__('cp.filter')}}
                                <i class="fa fa-search"></i>
                            </button>

                            <button class="btn sbold btn-success event"  data-action="active" href="#activation" role="button"  data-toggle="modal">{{__('cp.active')}}
                                <i class="fa fa-check"></i>
                            </button>
                            <button class="btn sbold btn-warning event"href="#cancel_activation" role="button"  data-toggle="modal" data-action="not_active">{{__('cp.not_active')}}
                                <i class="fa fa-minus"></i>
                            </button>

                            <button class="btn sbold btn-danger event" href="#deleteAll" role="button"  data-toggle="modal">{{__('cp.delete')}}
                                <i class="fa fa-times"></i>
                            </button>

                        </div>
                    </div>

                </div>
                
                <div class="box-filter-collapse">
                    <form class="form-horizontal" method="get" action="{{url(getLocal().'/admin/products')}}">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group row">
                                    <label class="col-md-3 control-label">{{__('cp.category')}}</label>

                                    <div class="col-md-9">
                                        <select id="multiple2" class="form-control select"
                                                name="categoryId">
                                            <option value="">{{__('cp.select')}}</option>
                                            @foreach(App\Models\Category::get() as $category)
                                                <option value="{{$category->id}}" {{(Request::get('categoryId') == $category->id) ? 'selected' : ''}}>
                                                    {{$category->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="form-group row">
                                    <label class="col-md-3 control-label">{{__('cp.status')}}</label>
                                    <div class="col-md-9">
                                        <select id="multiple2" class="form-control"
                                                name="statusUser">
                                            <option value="">{{__('cp.all')}}</option>
                                            <option value="active">
                                                {{__('cp.active')}}
                                            </option>
                                            <option value="not_active">
                                                {{__('cp.not_active')}}
                                            </option>

                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            
                            <div class="col-md-5">
                                <div class="form-group row">
                                    <label class="col-md-3 control-label">{{__('cp.availability')}}</label>
                                    <div class="col-md-9">
                                        <select id="available" class="form-control"
                                                name="available">
                                            <option value="">{{__('cp.all')}}</option>
                                            <option value="1">{{__('cp.available')}}</option>
                                            <option value="2">{{__('cp.not_available')}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>



                            <div class="col-md-5">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn sbold blue">{{__('cp.search')}}
                                            <i class="fa fa-search"></i>
                                        </button>
                                        <a href="{{url('admin/products')}}" type="submit"
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
            <table class="table table-striped table-bordered table-hover table-checkable order-column" id="toolsTable1">
                <thead>
                <tr>
                    <th>

                    </th>
                    <th> {{ucwords(__('cp.image'))}}</th>
                    <th> {{ucwords(__('cp.name_product'))}}</th>
                    <th> {{ucwords(__('cp.category'))}}</th>
                    <th> {{ucwords(__('cp.price'))}}</th>
                    <th> {{ucwords(__('cp.availability'))}}</th>
                    <th> {{ucwords(__('cp.status'))}}</th>
                    <th> {{ucwords(__('cp.created'))}}</th>
                    <th> {{ucwords(__('cp.action'))}}</th>
                </tr>
                </thead>
                <tbody>
                @forelse(@$products as $product)
                    <tr  class="odd gradeX" id="tr-{{$product->id}}">
                        <td>
                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                <input type="checkbox" class="checkboxes chkBox" value="{{$product->id}}" name="chkBox"/>
                                <span></span>
                            </label>
                        </td>

                        <td><img src="{{$product->image}}" width="50px" height="50px"></td>
                        <td><span style="{{($product->available == 2) ? 'color:red; text-decoration: line-through;' : ''}}">{{@$product->name}}</span></td>
                        <td>{{@$product->subcategory->name}} <br> - {{@$product->category->name}}</td>
                        <td>@if($product->is_dollar == 1)
                                {{$product->price}} $ <br>
                                ( {{$product->result($product->price)}} ) ₺
                            @else
                                {{$product->price}} ₺
                            @endif
                        </td>
                        <td> 
                        @if($product->available == "2")  <span class="symbol"> {{__('cp.available')}}</span> @else {{__('cp.available')}} @endIf
                        </td>
                        <td> <span class="label label-sm {{ ($product->status == "active")
                                ? "label-info" : "label-danger"}} " id="label-{{$product->id}}">

                                {{__('cp.'.$product->status)}}
                            </span></td>

                        <td class="center">{{$product->created_at}}</td>
                        <td>
                            <div class="btn-group btn-action">
                                <div class="btn-group btn-action">
                                    <a href="{{url(getLocal().'/admin/products/'.$product->id.'/edit')}}"
                                       class="btn btn-xs blue tooltips" data-container="body" data-placement="top"
                                       data-original-title="{{__('cp.edit')}}"><i class="fa fa-edit"></i></a>

                                    <!--<a href="#myModal{{$product->id}}" role="button"  data-toggle="modal" class="btn btn-xs red tooltips" data-placement="top"
                                       data-original-title="{{__('cp.delete')}}">
                                        &nbsp;&nbsp;<i class="fa fa-times" aria-hidden="true" ></i>
                                    </a>--></div></div>

                                    <div id="myModal{{$product->id}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                    <h4 class="modal-title">{{__('cp.delete')}}</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <p>{{__('cp.confirm')}} </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn default" data-dismiss="modal" aria-hidden="true">{{__('cp.cancel')}}</button>
                                                    <a href="#" onclick="delete_adv('{{$product->id}}','{{$product->id}}',event)"><button class="btn btn-danger">{{__('cp.delete')}}</button></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </td>
                    </tr>
                @empty
                    {{__('cp.no')}}
                @endforelse
                
                </tbody>
            </table>
                        {{$products->appends($_GET)->links("pagination::bootstrap-4") }}
        </div>
    </div>
@endsection

@section('js')
@endsection
@section('script')
    <script>
        function delete_adv(id, iss_id, e) {
            //alert(id);
            e.preventDefault();

            var url = '{{url(getLocal()."/admin/products")}}/' + id;
            var csrf_token = '{{csrf_token()}}';
            $.ajax({
                type: 'delete',
                headers: {'X-CSRF-TOKEN': csrf_token},
                url: url,
                data: {_method: 'delete'},
                success: function (response) {
                    console.log(response);
                    if (response === 'success') {
                        $('#tr-' + id).hide(500);
                        $('#myModal' + id).modal('toggle');

                    } else {
                        swal('Error', {icon: "error"});
                    }
                },
                error: function (e) {

                }
            });

        }


    </script>
@endsection
