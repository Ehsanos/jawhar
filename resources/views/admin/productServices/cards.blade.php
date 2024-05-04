@extends('layout.adminLayout')
@section('title') {{__('cp.cards')}}
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
                            <a href="{{url(getLocal().'/admin/productServices/'.$id.'/addCards')}}" style="margin-right: 5px" class="btn sbold btn-primary">
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
            <table class="table table-striped table-bordered table-hover table-checkable order-column" id="toolsTable">
                <thead>
                <tr>
                    <th>

                    </th>
                    <th> {{ucwords(__('cp.addBy'))}}</th>
                    @php
                        $for_balance = true;
                        $for_whatsapp = true;
                    @endphp
                    @forelse($serviceCards as $one_now)
                        @if(isset($one_now->serviceName->id) && ($one_now->serviceName->id == 4 || $one_now->serviceName->id == 2))
                            @php
                                $for_balance = false;
                            @endphp
                            @break
                        @endif
                        @if(isset($one_now->serviceName->id) && $one_now->serviceName->id == 3)
                            @php
                                $for_whatsapp = false;
                            @endphp
                            @break
                        @endif
                    @endforeach
                    @if($for_whatsapp)
                    <th> كود البطاقة</th>
                    @endif
                    @if($for_balance)
                    <th> الرقم</th>
                    <th> كود التفعيل</th>
                    @endif
                    <th> منتج الخدمة</th>
                    <th> الخدمة</th>
                    <th> {{ucwords(__('cp.status'))}}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($serviceCards as $one)
                    <tr class="odd gradeX" id="tr-{{$one->id}}">
                        <td>
                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                <input type="checkbox" class="checkboxes chkBox" value="{{$one->id}}" name="chkBox"/>
                                <span></span>
                            </label>
                        </td>



                        <td>{{$one->admin->name}}</td>
                        @if($for_whatsapp)
                        <td>{{$one->card_id}}</td>
                        @endif
                        @if($for_balance)
                        <td>{{$one->pin}}</td>
                        <td>{{@$one->password}}</td>
                        @endif
                        <td>{{$one->productName->name}}</td>
                        <td>{{@$one->serviceName->name}}</td>
                        <td> <span class="label label-sm {{(@$one->Status == "active")
                                ? "label-info" : "label-danger"}}" id="label-{{$one->id}}">
                                {{__('cp.'.$one->Status)}}
                            </span>
                        </td>

                    </tr>
                @empty
                    {{__('cp.no')}}
                @endforelse
                </tbody>
            </table>
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

            var url = '{{url(getLocal()."/admin/azkar")}}/' + id;
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
