@extends('layout.adminLayout')
@section('title') {{__('cp.soldServices')}}
@endsection
@section('css')
@endsection

@section('content')

    <div class="portlet light bordered">
        <div class="portlet-body">
            <div class="table-toolbar">
                <div class="row">

                </div>
            </div>
            <table class="table table-striped table-bordered table-hover order-column" id="toolsTable">
                <thead>
                <tr>
                    <th> {{ucwords(__('cp.id'))}}</th>
                    <th> {{ucwords(__('cp.name'))}}</th>
                    <th> {{ucwords(__('cp.city'))}}</th>
                    <th> {{ucwords(__('cp.service'))}}</th>
                    <th> {{ucwords(__('cp.product'))}}</th>
                    <th> {{ucwords(__('cp.cards'))}}</th>
                    <th> {{ucwords(__('cp.price'))}}</th>
                    <th id="lolo"> {{ucwords(__('cp.date'))}}</th>
                    <th> ID</th>
                    <th> {{ucwords(__('cp.status'))}}</th>
                    <th> {{ucwords(__('cp.action'))}}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($items as $one)
                    @if($one->type == 2)<!-- ServiceCardsRequest  -->
                    <?php
                    $one = \App\Models\ServiceCardsRequest::where("id",$one->id)->first();
                    ?>
                    <tr class="odd gradeX" id="tr-{{$one->id}}">
                        <td>{{$one->id}}</td>
                        <td>{{@$one->user->name}}</td>
                        <td>{{@$one->city->name}}</td>
                        <td>{{@$one->service->name}}</td>
                        <td>{{@$one->product->name}}</td>
                        <td>{{@$one->cards->card_id}} {{@$one->cards->pin}}</td>
                        <td>{{$one->price}}</td>
                        <td class="center">{{$one->created_at}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endif
                    @if($one->type == 1)<!-- GameRequest  -->
                    <?php
                    $item = \App\Models\GameRequest::where("id",$one->id)->first();
                    ?>
                    <tr class="odd gradeX" id="tr-{{$item->id}}">
                        <td>{{$item->id}}</td>
                        <td> {{@$item->user->name}}</td>
                        <td> {{@$item->city->name}}</td>
                        <td> {{@$item->game->name}}</td>
                        <td> {{@$item->servies->size}}</td>
                        <td> {{$item->id}}</td>
                        <td>{{$item->price}}</td>
                        <td class="center">{{$item->created_at}}</td>
                        <td> {{@$item->user_game_id}} </td>

                        <td>
                            <?php $status = '';
                            $cls = '';
                            if($item->status == -1) {
                                $status = 'new';
                                $cls    = 'label-danger';
                            }
                            elseif($item->status == 0) {
                                $status = 'preparing';
                                $cls    = 'label-info';
                            }
                            elseif ($item->status == 1) {
                                $status = 'onDelivery';
                                $cls    = 'label-warning';
                            }
                            elseif ($item->status == 2) {
                                $status = 'complete';
                                $cls    = 'label-success';
                            }
                            elseif ($item->status == 3) {
                                $status = 'cancel';
                                $cls    = 'label-default';
                            }
                            else {
                                $status = 'refund';
                                $cls    = 'label-default';
                            }
                            ?>
                            <span class="label label-sm {{$cls}}" id="label-{{$item->id}}">
                                {{__('cp.'.$status)}}
                            </span>

                        </td>

                        <td>

                            <div class="btn-group btn-action">



                                <a href="{{url(getLocal().'/admin/gameRequest/'.$item->id.'/edit')}}"
                                   class="btn btn-xs blue tooltips" data-container="body" data-placement="top"
                                   data-original-title="{{__('cp.edit')}}"><i class="fa fa-edit"></i>
                                </a>


                            <!--<a href="#" onclick="openWindow('{{url(getLocal().'/admin/gameRequest/printOrder/'.$item->id)}}')" class="btn btn-xs red tooltips" data-container="body" data-placement="top"-->
                            <!--   data-original-title="{{__('cp.print')}}"><i class="fa fa-print"></i>-->
                                <!--</a>-->




                                <div id="myModal{{$item->id}}" class="modal fade" tabindex="-1" role="dialog"

                                     aria-labelledby="myModalLabel1" aria-hidden="true">
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
                                                <a href="#" onclick="delete_adv('{{$item->id}}','{{$item->id}}',event)">
                                                    <button class="btn btn-danger">{{__('cp.delete')}}</button>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endif
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
        $(document).ready(function() {
        while(true)
        {
            if($( "#lolo" ).hasClass( "sorting_desc" ))
            {

                break;
            }
            else
            {
                $("#lolo").click();
            }
        }
        });
    </script>
@endsection
